
<?php
class Ajax extends APP_Controller {
    function __construct() 
    {
        parent::__construct();

		$this->layout->setLayout('layouts/empty');
	}

	function preview($id = false) 
	{
        $this->load->model('m_story_preview');

		$output = new StdClass;
		$output->success = false;

		$errors = false;

		if($id !== false) {
			$output->ss = $id;
			$story = $this->m_story_preview->get_by_temporary_id($id, false);
			if(!$story) {
				$story = new StdClass;
				$story->temporary_id = $id;
			}
		} else {
    		$story = new StdClass;
		}

        if(isset($_POST) && !empty($_POST)) {
        	$errors = $this->__check_for_write_form($_POST, $story);

        	if(!$errors) {
				if(!isset($story->create_time)) $story->create_time = date('Y-m-d H:i:s', mktime());
				else $story->update_time = date('Y-m-d H:i:s', mktime());

				$story->user_id = $this->user_data->id;

				// finding covers
				$covers = array();
				if($count = preg_match_all('/!\[cover\]\((.*?)\)/', $story->content, $matches)) {
					for($i=0;$i<$count;$i++) {
						$covers[] = $matches[1][$i];
					}
				}

				if(empty($covers)) $story->cover = '';
				else $story->cover = $covers[0];

				// replace type {<1>}
				$story->content = preg_replace('/\{\<[0-9+]\>\}/', '', $story->content);

                if(isset($_POST['tags'])) {
                    $tags = explode(',', $_POST['tags']);
                    foreach($tags as $key=>$tag) $tags[$key] = trim($tag);
                    
                    $sotry->tags = implode(',', $tags);
                }

				if(isset($story->id)) {
					$this->m_story_preview->update($story->id, $story);
					$output->success = true;
					$output->content = $story;
				} else {

					if(!isset($story->temporary_id)) $story->temporary_id = date('YmdHis',mktime()).'_'.intval(microtime(true)).'_'.rand();
        			if($id = $this->m_story_preview->add($story)) {		
        				$story->id = $id;

        				$output->success = true;
        				$output->content = $story;
        			}
				}
			} else {
				$output->content = $errors;
			}
        }

		echo json_encode($output);
	}

	function upload()
	{		
		ini_set('memory_limit', '256M');

		$this->load->library('upload_handler');
        
        $this->upload_handler->set_option_value('script_url', trim_slashes($this->config->item('server_url')).'/');
        $this->upload_handler->set_option_value('upload_url', trim_slashes($this->config->item('server_url')).'/files/uploads/');
        
		$files = $this->upload_handler->execute();
		
		if($files && $this->config->item('enable_s3')) $this->__upload_s3($files[$this->upload_handler->get_option_value('param_name')]);
	}

	function save($id = false)
	{
        $this->load->model('m_story');

		$output = new StdClass;
		$output->success = false;

		$errors = false;

		if($id !== false) {
			$story = $this->m_story->get($id, false);
		} else {
    		$story = new StdClass;
		}

        if(isset($_POST) && !empty($_POST)) {
					
        	$errors = $this->__check_for_write_form($_POST, $story);

        	if(!$errors) {
				if(!isset($story->create_time)) $story->create_time = date('Y-m-d H:i:s', mktime());
				else $story->update_time = date('Y-m-d H:i:s', mktime());

				$story->user_id = $this->user_data->id;
				
				// finding covers
				$covers = array();
				if($count = preg_match_all('/!\[cover\]\((.*?)\)/', $story->content, $matches)) {
					for($i=0;$i<$count;$i++) {
						$covers[] = $matches[1][$i];
					}
				}

				if(empty($covers)) $story->cover = '';
				else $story->cover = $covers[0];

				// replace type {<1>}
				$story->content = preg_replace('/\{\<[0-9+]\>\}/', '', $story->content);

				if(isset($story->id)) {
					unset($story->tags);
					
					$this->m_story->update($story->id, $story);
					$output->success = true;
					$output->content = $story;
				} else {
        			if($id = $this->m_story->add($story)) {					
        				$output->success = true;
        				$output->content = $story;
        				$story->id = $id;
        			}
				}

				// tags
                if(isset($_POST['tags'])) {
                    $tags = explode(',', $_POST['tags']);
                    $output->content->tags = $this->m_story->clear_and_add_tags($story->id, $tags);
                }
				
			} else {
				$output->content = $errors;
			}
        }

		echo json_encode($output);
	}

	private function __check_for_write_form(&$form, &$story = null) 
	{
		$this->load->helper('string');

		$errors = array();

		if(!isset($form['title'])) {
			$errors['title'] = '잘못된 접근일 수 있습니다. 새로고침해주세요';

			if($story) $story->title = '';
		} else {
			if(empty($form['title'])) {
				$errors['title'] = '제목이 공백입니다. 제목을 입력해주세요';			
			} 
			
			if($story) $story->title = $form['title'];
		}

		if(!isset($form['content'])) {
			$errors['content'] = '잘못된 접근일 수 있습니다. 새로고침해주세요';

			if($story) $story->content = '';
		} else {
			if($story) $story->content = $form['content'];
		}

		if($story) {
			if(isset($form['sub_title']))
				$story->sub_title = $form['sub_title'];
			else
				$story->sub_title = '';
		}

		if($story) {
			if(isset($form['is_publish']))
				$story->is_publish = in_array($form['is_publish'], array('yes','no')) ? $form['is_publish'] : 'no';
			else
				$story->is_publish = 'no';
		}

		if($story->is_publish == 'yes' && (!isset($story->publish_time) || $story->publish_time == '0000-00-00 00:00:00')) {
			$story->publish_time = date('Y-m-d H:i:s', mktime());
		}

		if(isset($form['update_publish'])) {
			$update_publish = in_array($form['update_publish'], array('yes','no')) ? $form['update_publish'] : 'no';
			if($update_publish == 'yes') $story->publish_time = date('Y-m-d H:i:s', mktime());
		}

		if(count($errors) == 0) return false;
		return $errors;
	}	

	function update_user_data($user_id)
	{
		$error = false;

		$output = new StdClass;
		$output->success = true;

		if(!$this->user_data->id) {
			$error = '잘못된 접근입니다.';
		} else {
			if($this->user_data->id != $user_id) {
				$error = '잘못된 접근입니다.';
			} else if(empty($_POST) || !isset($_POST['old_password']) || !isset($_POST['new_password']) || !isset($_POST['new_password_re'])) {
				$error = '잘못된 접근입니다.';
			} else {
				$user_data = $this->m_user->get($this->user_data->id, true);

				if(empty($_POST['old_password'])) {
					$error = '현재 비밀번호를 입력해주세요.';
				} else if($this->auth->password($_POST['old_password']) != $user_data->password) {
					$error = '현재 비밀번호가 틀렸습니다.';
				} else if($_POST['new_password'] != $_POST['new_password_re']) {
					$error = '"새로운 비밀번호"와 "새로운 비밀번호 확인"을 같게 입력해주세요.';
				} else {
					$update_data = new StdClass;
					$update_data->password = $this->auth->password($_POST['new_password']);

					$this->m_user->update($user_id, $update_data);
				}
			}
		}

		if($error !== false) {
			$output->success = false;
			$output->message = $error;
		}

		echo json_encode($output);
	}
	
    private function __upload_s3($files) {        
        require_once APPPATH.'/vendors/AWSSDKforPHP/sdk.class.php';
        
        CFCredentials::set(array(
            'development' => array(
                'key' => $this->config->item('aws_key'),
                'secret' => $this->config->item('aws_secret'),
                'default_cache_config' => '',
                'certificate_authority' => false
            ),
            '@default' => 'development'
        ));
        
        $s3 = new AmazonS3();   
        $s3->path_style = true;
        $s3->set_hostname('s3-ap-northeast-1.amazonaws.com');
		$s3->allow_hostname_override(false);

        $output = new StdClass;
                
        // AWS upload
        $individual_filenames = array();
		$server_url = $this->config->item('server_url');
		
        foreach($files as $file) {
			foreach($this->upload_handler->get_image_versions() as $version) {
				if(empty($version)) $file_name = $file->url;
				else $file_name = $file->{$version.'Url'};
				
				$file_value = str_replace($server_url,'',$file_name);
				$file_name = APPPATH . '/webroot/'.$file_value;

				if(!empty($version)) $file_value = str_replace('/'.$version.'/', '/'.$version.'_',$file_value);
				$file_value = str_replace('files/uploads/', '', $file_value);

				$individual_filenames[] = array('from'=>urldecode($file_name), 'to'=>'uploads/'.$file_value);
			}
		}
		
		if(count($individual_filenames)) {
	        $bucket_name = 's3.withstories.com';
	        foreach($individual_filenames as $file) {
	            $s3->batch()->create_object($bucket_name, $file['to'], array(
	                'fileUpload' => realpath($file['from']),
	                'acl'=> $s3::ACL_PUBLIC
	            ));
	        }
		}
		    
        $s3->batch()->send();
        
        return $output;
    }
	
}