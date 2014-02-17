<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_story extends CI_Model
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
    	if($this->db->insert('stories', $data)) {
    		return $this->db->insert_id();
    	} else {
    		return false;
    	}
    }

    public function update($id, $data)
    {
    	$data->update_time = date('Y-m-d H:i:s', mktime());

    	$this->db->where('id', $id);
    	$this->db->update('stories', $data);
    }
    
    function update_count($id, $field)
    {
        if(is_array($field)) {
            foreach($field as $f)
                $this->db->set($f, $f.'+1', FALSE);
        } else {
            $this->db->set($field, $field.'+1', FALSE);
        }
        
        $this->db->where('id', $id);
        $this->db->update('stories');
    }

    function get($id, $join_user = true) {
        if($join_user)
    	   return $this->db->from('stories')->join('story_tag_relations','story_tag_relations.story_id = stories.id','left')->join('story_tags','story_tags.id = story_tag_relations.tag_id','left')->join('users','stories.user_id = users.id', 'left')->where('stories.id', $id)->select('stories.*, GROUP_CONCAT(story_tags.name) as tags, users.profile as user_profile, users.name as user_name, users.permalink as user_permalink, users.host as user_host')->group_by('stories.id')->get()->row();
        else 
           return $this->db->from('stories')->join('story_tag_relations','story_tag_relations.story_id = stories.id','left')->join('story_tags','story_tags.id = story_tag_relations.tag_id','left')->where('stories.id', $id)->select('stories.*, GROUP_CONCAT(story_tags.name) as tags')->group_by('stories.id')->get()->row();
    }

    function get_count_all($view_user_id = false)
    {
        $this->db->from('stories');

        if($view_user_id !== false) {
            $this->db->where('(stories.is_publish = "yes" OR (stories.is_publish = "no" AND stories.user_id = ' . $view_user_id . '))');
        } else {
            $this->db->where('stories.is_publish', 'yes');
        }

        return $this->db->count_all_results();
    }

	function gets_all($count = null, $index = 1, $view_user_id = false)
	{
		$this->db->from('stories')->join('story_tag_relations','story_tag_relations.story_id = stories.id','left')->join('story_tags','story_tags.id = story_tag_relations.tag_id','left')->join('users','stories.user_id = users.id', 'left');

        if($index > 1) {
            $this->db->limit($count, ($index - 1));
        } else {
            $this->db->limit($count);
        }

        if($view_user_id !== false) {
            $this->db->where('(stories.is_publish = "yes" OR (stories.is_publish = "no" AND stories.user_id = ' . $view_user_id . '))');
        } else {
            $this->db->where('stories.is_publish', 'yes');
        }
		
		return $this->db->select('stories.*, GROUP_CONCAT(story_tags.name) as tags, users.profile as user_profile, users.name as user_name, users.permalink as user_permalink, users.host as user_host')->group_by('stories.id')->order_by('stories.create_time DESC')->get()->result();
	}

    function get_count_by_user_id($user_id, $view_user_id = false) {

        $this->db->from('stories');
        
        if($view_user_id !== false) {
            $this->db->where('(stories.is_publish = "yes" OR (stories.is_publish = "no" AND stories.user_id = ' . $view_user_id . '))');
        } else {
            $this->db->where('stories.is_publish', 'yes');
        }

        return $this->db->where('user_id', $user_id)->count_all_results();
    }

	function gets_all_by_user_id($user_id, $count = null, $index = 1, $view_user_id = false)
	{
		$this->db->from('stories')->join('story_tag_relations','story_tag_relations.story_id = stories.id','left')->join('story_tags','story_tags.id = story_tag_relations.tag_id','left')->join('users','stories.user_id = users.id', 'join')->where('stories.user_id', $user_id);

        if($index > 1) {
            $this->db->limit($count, ($index - 1));
        } else {
            $this->db->limit($count);
        }

        if($view_user_id !== false) {
            $this->db->where('(stories.is_publish = "yes" OR (stories.is_publish = "no" AND stories.user_id = ' . $view_user_id . '))');
        } else {
            $this->db->where('stories.is_publish', 'yes');
        }
		
		return $this->db->select('stories.*, GROUP_CONCAT(story_tags.name) as tags, users.profile as user_profile, users.name as user_name, users.permalink as user_permalink, users.host as user_host')->group_by('stories.id')->order_by('stories.create_time DESC')->get()->result();
	}

    function gets_all_by_tag($tag, $count, $index = 1, $user_id = false) {
        $this->db->from('stories')->join('story_tag_relations','story_tag_relations.story_id = stories.id','left')->join('story_tags','story_tags.id = story_tag_relations.tag_id','left')->join('users','stories.user_id = users.id', 'join');

        if($user_id !== false) $this->db->where('stories.user_id', $user_id);

        $this->db->where('story_tags.name', $tag);

        if($index > 1) {
            $this->db->limit($count, ($index - 1));
        } else {
            $this->db->limit($count);
        }       
        
        $this->db->where('stories.is_publish', 'yes');

        return $this->db->select('stories.*, GROUP_CONCAT(story_tags.name) as tags, users.profile as user_profile, users.name as user_name, users.permalink as user_permalink, users.host as user_host')->group_by('stories.id')->order_by('stories.create_time DESC')->get()->result();
    }

    function clear_and_add_tags($story_id, $tags) {
        $this->db->delete('story_tag_relations', array('story_id'=>$story_id));
        
        if(!count($tags)) return false;
        
        $not_exsits_tags = array();
        $not_exsits_tags_names = array();
        
        $check_tags = array();
        $result = $this->db->from('story_tags')->where_in('name', $tags)->get()->result();
        if($result) {
            foreach($result as $item) {
                $check_tags[$item->name] = $item;
            }
        }
        
        foreach($tags as $tag) {
            if(!isset($check_tags[$tag])) {
                $tag = trim($tag);
                
                $not_exsits_tags[] = array('name'=>$tag);
                $not_exsits_tags_names[] = $tag;
            }
        }
        
        if(count($not_exsits_tags)) {
            $this->db->insert_batch('story_tags', $not_exsits_tags, true);
            
            $result = $this->db->from('story_tags')->where_in('name', $not_exsits_tags_names)->get()->result();
            if($result) {
                foreach($result as $item) {
                    $check_tags[$item->name] = $item;
                }
            }
        }
        
        if(count($check_tags)) {
            $add_tag_relations = array();
            foreach($check_tags as $tag) {
                $add_tag_relations[] = array('story_id'=>$story_id, 'tag_id'=>$tag->id);
            }
            
            $this->db->insert_batch('story_tag_relations', $add_tag_relations, true);
        }
        
        return $check_tags;
    }

	function __convert_for_view($story) {
		if(!empty($story->user_host)) {
            $story->user_url = 'http://' . $story->user_host . '/';
        } else if(!empty($story->user_permalink)) {
            $story->user_url = site_url('/' . $story->user_permalink);
        } else {
        	$story->user_url = site_url('/user/'.$story->user_id);
        }

        $server_url = $this->config->item('server_url');
        
        if(!empty($story->cover)) {
            $story->cover_url =  $story->cover;

            if(substr($story->cover_url,0, strlen($server_url)) == $server_url) {
                $versions = $this->upload_handler->get_image_versions();
                array_shift($versions);
                foreach($versions as $version) {
                    $file_name = urldecode(str_replace($server_url.'files/uploads/', '', $story->cover_url));
                    $story->{'cover_' . $version . '_url'} = 'http://s3.withstories.com/uploads/'.$version.'_'.str_replace('%','%25',rawurlencode($file_name));
                }
            }
            
            $file_name = urldecode(str_replace($server_url.'files/uploads/', '', $story->cover_url));
            $story->{'cover_url'} = 'http://s3.withstories.com/uploads/'.str_replace('%', '%25',rawurlencode($file_name));
        }
		
		// story view (화면보기용 S3 변환)
		//$server_url = site_url('/');

		$story->content = str_replace($server_url.'files/uploads/','http://s3.withstories.com/uploads/view_',$story->content);
        
        if($count = preg_match_all('/!\[(.*)\]\((.*?)\)/', $story->content, $matches)) {
            for($i=0;$i<$count;$i++) {
                $story->content = str_replace($matches[2][$i], str_replace('%','%25',$matches[2][$i]), $story->content);
            }
        }

		return $story;
	}

    function convert_for_view($stories) {
        $this->load->library('upload_handler');

    	if(is_array($stories)) {
	        foreach($stories as $key=>$story) {
	            $stories[$key] = $this->__convert_for_view($story);
	        }
	
	        return $stories;
		} else {
			return $this->__convert_for_view($stories);
		}
    }
		

}