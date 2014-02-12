<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_story_Preview extends CI_Model
{
    //----------------------- PUBLIC METHODS --------------------------//

    //----------------------- STATIC METHODS --------------------------//
	function __construct()
	{
	    // Call the Model constructor
	    parent::__construct();
	}
    //----------------------- PUBLIC METHODS --------------------------//

    public function add($data) {
    	if($this->db->insert('story_previews', $data)) {
    		return $this->db->insert_id();
    	} else {
    		return false;
    	}
    }

    public function update($id, $data)
    {
    	$data->update_time = date('Y-m-d H:i:s', mktime());

    	$this->db->where('id', $id);
    	$this->db->update('story_previews', $data);
    }

    function get($id, $join_user = true) {
        if($join_user)
    	   return $this->db->from('story_previews')->join('users','story_previews.user_id = users.id', 'left')->where('story_previews.id', $id)->select('story_previews.*, users.profile as user_profile, users.name as user_name, users.permalink as user_permalink')->get()->row();
        else
           return $this->db->from('story_previews')->where('id', $id)->get()->row();
    }

    function get_by_temporary_id($id, $join_user = true) {
        if($join_user)
            return $this->db->from('story_previews')->join('users','story_previews.user_id = users.id', 'left')->where('story_previews.temporary_id', $id)->select('story_previews.*, users.profile as user_profile, users.name as user_name, users.permalink as user_permalink')->get()->row();
        else
            return $this->db->from('story_previews')->where('temporary_id', $id)->get()->row();
    }
}