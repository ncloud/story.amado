<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Log extends CI_Model 
{
    public function __construct()
    {
    }
    
    function add($story_id, $type, $session_id, $user_agent, $ip_address, $referrer = '', $value = false) {
        $data = new StdClass;
        $data->story_id = $story_id;
        $data->type = $type;
        if($value !== false) $data->value = $value;
        $data->user_session_id = $session_id;
        $data->user_agent = $user_agent;
        $data->user_ip_address = $ip_address;
        $data->user_referrer = $referrer;
        
        $data->insert_time = date('Y-m-d H:i:s', mktime());
        
        return $this->db->insert('story_logs', $data);
    }
    
    function check($story_id, $type, $session_id, $value = false) {
        if($value) {
            return $this->db->from('story_logs')->where(array('story_id'=>$story_id, 'type'=>$type, 'value'=>$value, 'user_session_id'=>$session_id))->get()->row();
        } else {
            return $this->db->from('story_logs')->where(array('story_id'=>$story_id, 'type'=>$type, 'user_session_id'=>$session_id))->get()->row();
        }
    }
    
    function get_story_logs($story_id, $session_id)
    {
        $story_logs = array();
        $logs = $this->db->from('story_logs')->where('story_id', $story_id)->where('user_session_id', $session_id)->get()->result();
        if($logs) {
            foreach($logs as $log) {
                $story_logs[$log->type] = $log;
            }
        }
        
        return $story_logs;
    }
    
    function get_story_log($story_id, $type, $session_id)
    {
        return $this->db->from('story_logs')->where('story_id', $story_id)->where('user_session_id', $session_id)->where('type',$type)->get()->row();
    }
    
    function gets_referrer_by_story_id($story_id, $type, $referrer_base, $count = 30, $index = 1) 
    {
        $this->db->from('story_logs')->where('type',$type)->like('user_referrer', 'http://' . $referrer_base . '%');
        
        if($story_id) $this->db->where('story_id', $story_id);
        
        if($index > 1) {
            $this->db->limit($count, ($index - 1));
        } else {
            $this->db->limit($count);
        }
        
        return $this->db->order_by('insert_time DESC')->get()->result();
    }
    
    function gets_by_story_id($story_id, $type, $count = 30, $index = 1)
    {
        $this->db->from('story_logs')->where('type',$type);
        
        if($story_id) $this->db->where('story_id', $story_id);
        
        if($index > 1) {
            $this->db->limit($count, ($index - 1));
        } else {
            $this->db->limit($count);
        }
        
        return $this->db->order_by('insert_time DESC')->get()->result();
    }
}
    