<?php
class Page extends APP_Controller {
    function __construct() 
    {
        parent::__construct();
    }
    
    public function index($mode = '')
    {
        if($this->custom_host && empty($mode)) {
            $this->user($this->custom_host->permalink);
            return false;
        } else {
            if(empty($mode)) $mode = 'auto';
        }

    	$this->load->model('m_story');

        $index = 1;
        $count = 30;

    	if(!$this->user_data->id || $mode == 'all') {
            $mode = 'all';
            $stories = $this->m_story->gets_all($count, $index, $this->user_data->id ? $this->user_data->id : false);
            $total_count = $this->m_story->get_count_all($this->user_data->id ? $this->user_data->id : false);
    	} else {
            $mode = 'my';
            $stories = $this->m_story->gets_all_by_user_id($this->user_data->id, $count, $index, $this->user_data->id ? $this->user_data->id : false);
            $total_count = $this->m_story->get_count_by_user_id($this->user_data->id, $this->user_data->id ? $this->user_data->id : false);
    	}

        $paging = new StdClass;
        $paging->total_count = $total_count;

        $paging->index = $index;
        $paging->count = $count;

        $this->set('paging', $paging);

        $stories = $this->m_story->convert_for_view($stories);

        $this->set('mode', $mode);
    	$this->set('stories', $stories);

        $this->set('nav_in_container', true);
        $this->set('in_container', true);
    	
		$this->view('index');
    }
    
    function test()
    {
    	$this->view('test');
    }

    function user_login() 
    {
        if(!empty($this->user_data->id)) // 로그인 되어 있으면
        {
            redirect('/');
            return false;
        }
		
		$this->layout->setLayout('layouts/user');
        		
		$redirect = empty($this->queries['redirect_uri']) ? (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('/')) : $this->queries['redirect_uri'];
		$this->set('redirect_url', $redirect);
		
        $this->set('join_mode', false);
		
		$this->view('user/login');
    }

    function user_join()
    {
        if(!empty($this->user_data->id)) // 로그인 되어 있으면
        {
            redirect('/');
            return false;
        }
		
		$this->layout->setLayout('layouts/user');
		        
		$redirect = empty($this->queries['redirect_uri']) ? (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('/')) : $this->queries['redirect_uri'];
		$this->set('redirect_url', $redirect);
		
        $this->set('join_mode', true);        
        
		$this->view('user/login');
    }
	
	
    function user_edit()
    {
        if(empty($this->user_data->id)) // 로그인 되어 있지 않으면
        {
            redirect('/login');
            return false;
        }

        $this->set('nav_in_container', true);
        $this->set('hide_make_button', true);
        $this->set('hide_setting_button', true);
        $this->set('show_logout_button', true);

		$user_data = $this->m_user->get($this->user_data->id);
		$message = null;

		if($_POST && !empty($_POST)) {
			$this->load->helper('email');

			$message = new StdClass;

			$errors = array();

			if(!isset($_POST['name']) || empty($_POST['name'])) {
				$errors['name'] = '이름을 입력해주세요';
				$user_data->name = '';
			} else {
				$user_data->name = $_POST['name'];
			}

			if(!isset($_POST['email']) || empty($_POST['email'])) {
				$errors['email'] = '이메일을 입력해주세요';

				$user_data->email = '';
			} else if(!valid_email($_POST['email'])) {
				$errors['email'] = '이메일 형식이 잘못되었습니다. 다시 입력해주세요';
				$user_data->email = $_POST['email'];
			} else {
				$user_data->email = $_POST['email'];
			}

            if(isset($_POST['description'])) $user_data->description = $_POST['description'];

            if(isset($_POST['cover'])) $user_data->cover = $_POST['cover'];
            if(isset($_POST['profile'])) $user_data->profile = $_POST['profile'];

			if(!count($errors)) {
				$data = new StdClass;
				$data->name = $data->display_name = $_POST['name'];
                $data->email = $_POST['email'];

                $data->description = $user_data->description;

                $data->cover = $user_data->cover;
                $data->profile = $user_data->profile;

				if($this->m_user->update($this->user_data->id, $data)) {
					$message->type = 'success';
					$message->content = '변경사항이 수정되었습니다.';

					$this->auth->update_user($user_data);
				} else {
					$message->type = 'error';
					$message->content = array();
				}
			} else {
				$message->type = 'error';
				$message->content = $errors;
			}
		}

		$this->set('message', $message);
		$this->set('user_data', $user_data);

		$this->view('user/edit');
    }

    public function user($permalink) {
        $this->load->model('m_user');
        $this->load->model('m_story');

        if(is_numeric($permalink)) {
            $user = $this->m_user->get($permalink);
        } else {
            $user = $this->m_user->get_by_permalink($permalink);
        }

        if($user) {
            $this->set('user', $user);
            
            $count = 30;
            $index = 1;

            $stories = $this->m_story->gets_all_by_user_id($user->id, $count, $index, $this->user_data->id ? $this->user_data->id : false);
            $stories = $this->m_story->convert_for_view($stories);

            $this->set('stories', $stories);

            $this->set('nav_in_container', true);
            $this->set('black_base_navbar', true);

            $this->view('index_user');
        } else {
            $this->error('잘못된 접근입니다');
        }
    }

    public function tag($tag, $user_id = false)
    {
        $this->load->model('m_story');

        $count = 30;
        $index = 1;

        $tag = urldecode($tag);

        $stories = $this->m_story->gets_all_by_tag($tag, $count, $index, $user_id);
        $stories = $this->m_story->convert_for_view($stories);

        $this->set('stories', $stories);

        $this->set('nav_in_container', true);
        $this->set('black_base_navbar', false);

        $this->set('search_mode', 'Tag');
        $this->set('search_value', $tag);

        $this->view('index_search');
    }

    public function write()
    {
        if(empty($this->user_data->id)) // 로그인 되어 있지 않으면
        {
            redirect('/login');
            return false;
        }

        $this->load->model('m_story');

        $this->layout->addScript('plugin/jquery.autosize');

        $this->set('body_full', true);
        $this->set('edit_mode', false);
        
        $this->view('write');
    }

    public function edit($id)
    {
        if(empty($this->user_data->id)) // 로그인 되어 있지 않으면
        {
            redirect('/login');
            return false;
        }

        $this->load->model('m_story');

        $this->layout->addScript('plugin/jquery.autosize');
        $this->set('body_full', true);

        $story = $this->m_story->get($id);
        if($story) {
        	$this->set('story', $story);
			$this->set('edit_mode', true);

        	$this->view('write');
        } else {
        	$this->error('잘못된 접근입니다');
        }
    }

    function story($id, $mode = 'view') 
    {
        $this->load->model('m_story');
        $this->load->model('m_story_preview');
        $this->load->model('m_log');
        $this->load->library('date');

        $this->layout->addStyle('view');
/*
        $this->layout->addStyle('plugin/jquery.fancybox');
        $this->layout->addStyle('plugin/jquery.fancybox-thumbs');

        $this->layout->addScript('plugin/jquery.fancybox');
        $this->layout->addScript('plugin/jquery.fancybox-thumbs');
*/
        $this->set('mode', $mode);

        if($mode == 'preview') {
            $story = $this->m_story_preview->get_by_temporary_id($id);
            $story->publish_time = isset($story->publish_time) ? $story->publish_time : date('Y-m-d H:i:s', mktime());
        } else {
            $story = $this->m_story->get($id);
        }

        $black_base_navbar = false;

        if($story) {        	
            if($story->is_publish == 'no' && ($story->user_id != $this->user_data->id && $this->user_data->id != 1)) {
                $this->error('잘못된 접근입니다.');
            } else {
                require_once APPPATH . '/vendors/Michelf/Markdown.inc.php';
            	require_once APPPATH . '/vendors/Michelf/MarkdownExtra.inc.php';
            
				$story = $this->m_story->convert_for_view($story);
				
                // finding covers
                $covers = array();
                if($count = preg_match_all('/!\[cover\]\((.*?)\)/', $story->content, $matches)) {
                    for($i=0;$i<$count;$i++) {
                        $story->content = str_replace($matches[0][$i], '', $story->content);
                    }
                }

            	$story->content_html = \Michelf\MarkdownExtra::defaultTransform($story->content); 
                
                // story log
                $session_data = $this->session->all_userdata();
                $story_logs = $this->m_log->get_story_logs($id, $this->uid);
                $this->set('story_logs', $story_logs);
                
                $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                
                if(!isset($story_logs['view'])) {
                    $this->m_log->add($id, 'view.unique', $this->uid, $session_data['user_agent'], $this->input->ip_address(), $referrer);                
                    $this->m_story->update_count($id, 'unique_count');
                }
                
                $this->m_log->add($id, 'view', $this->uid, $session_data['user_agent'], $this->input->ip_address(), $referrer);
                $this->m_story->update_count($id, 'pageview_count');
                // story log end
                

            	if(!empty($story->cover)) {
                    $black_base_navbar = true;
            	}

            	$can_edit = $this->user_data->id == $story->user_id ? true : false;
            	if($can_edit) {
            		$this->set('edit_url', site_url('/edit/'.$story->id));
            	}
            	$this->set('can_edit', $can_edit);

                $load_count = 4;

                $stories_by_user = array();
                $result = $this->m_story->gets_all_by_user_id($story->user_id, $load_count + 1, 1, $this->user_data->id);
                foreach($result as $item) {
                    if($item->id == $story->id) continue;

                    $stories_by_user[] = $item;
                }

                if(count($stories_by_user) > $load_count) {
                    array_pop($stories_by_user); // 1개 빼기
                }
    
                $stories_by_user = $this->m_story->convert_for_view($stories_by_user);

            	$this->set('story', $story);
                $this->set('stories_by_user', $stories_by_user);

        		$this->set('in_container', true);
                $this->set('black_base_navbar', $black_base_navbar);

                $this->set('og_title', $story->title);
                $this->layout->setTitle($story->title);
                
                if(!empty($story->permalink)) {
                    $this->set('og_url', site_url('/' . $story->permalink . '/' . $story->id));
                } else {
                    $this->set('og_url', site_url('/' . $story->id));
                }

                if(!empty($story->sub_title)) {
                    $this->set('og_description', $story->sub_title);
                }

                if(!empty($story->cover_url)) {
                    $this->set('og_image', $story->cover_url);
                }

            	$this->view('view');
            }
        } else {
        	$this->error('잘못된 접근입니다.');
        }
    }
}
?>