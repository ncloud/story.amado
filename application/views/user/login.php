    <style type="text/css">
        @import url("<?php echo site_url('/css/user.css');?>");
    </style>

    <form class="sign_or_join_form <?php echo $join_mode ? 'join_mode' : 'login_mode';?>" method="POST" action="<?php echo $join_mode ? site_url('/join/do') : site_url('/login/do');?>" onsubmit="on_submit_login_mode(); return false;">
        <div class="input_wrap">
        	<h3><?php echo $join_mode ? '<a href="' . site_url('/') . '">' . $this->config->item('title') . '</a>를 시작하세요' : '<a href="' . site_url('/') . '">' . $this->config->item('title') . '</a>를 시작하세요';?></h3>
    	    
    	    <input type="text" class="input-fluid email input-gray" placeholder="이메일 주소" />
    	    <input type="password" class="input-fluid password input-gray" placeholder="비밀번호" />
    	    <input type="text" class="input-fluid username" placeholder="이름" />
    	    
    	    <button class="btn btn-fluid btn-primary" onclick="submit_login_mode(); return false;"><?php echo $join_mode ? '회원 가입' : '로그인';?></button>
    	</div>
    	
    	<div class="input_option_wrap">             
            <div class="or">또는</div>

    	    <div class="alternative_wrap">
    	        <button class="btn btn-fluid btn-gray <?php echo $join_mode ? 'login_button' : 'join_button';?>" onclick="toggle_login_mode(); return false;"><?php echo $join_mode ? '로그인' : '회원가입';?></button>
    	    </div>
        <?php
            if($this->config->item('facebook_use')) {
        ?>    
    	    <div class="social_wrap">    
    	        <a href="#" class="facebook_button have_title" onclick="login.facebook(); return false;"><span><?php echo $join_mode ? '페이스북으로 가입하세요' : '페이스북으로 로그인하세요';?></span></a>
    	    </div>
        <?php
            }
        ?>
    	</div>    
  </form>
    
    <script type="text/javascript" src="<?php echo site_url('/js/action/login.js');?>"></script>
    <script type="text/javascript">
        function on_submit_login_mode()
        {
            var $form = $("form");
            var $button = $form.find('.button');
            var $email = $form.find('.email');
            var $password = $form.find('.password');
        
            if($email.val() == "") { $email.focus(); return false; }
            else if($password.val() == "") { $password.focus(); return false; }

            if($form.hasClass('login_mode')) { // 로그인하기
                login.amado($email.val(), $password.val(), function(data) {
                    if(data.success) { // 로그인 성공
                        go('<?php echo $redirect_url;?>');
                    } else {
                        alert(data.message);
                    }
                });
            } else { // 가입하기
                var $username = $form.find('.username');
                if($username.val() == "") { $username.focus(); return false; }

                login.amado_join($email.val(), $password.val(), $username.val(), function(data) {
                    if(data.success) { // 회원가입 성공
                        go('<?php echo $redirect_url;?>');
                    } else {
                        alert(data.message);
                    }
                });
            }        
            
            return true;
        }

        function submit_login_mode()
        {
            var $form = $("form");
            var $button = $form.find('.btn-primary');
            var $email = $form.find('.email');
            var $password = $form.find('.password');
        
            if($email.val() == "") { $email.focus(); return false; }
            else if($password.val() == "") { $password.focus(); return false; }

            if($form.hasClass('login_mode')) { // 로그인하기
                $form.submit();
            } else { // 가입하기
                var $username = $form.find('.username');
                if($username.val() == "") { $username.focus(); return false; }
                
                $form.submit();
            }
            
            return true;
        }
        
        function toggle_login_mode()
        {
            var $form = $("form");
            var $button = $form.find('.input_wrap button');
            var $toggle_button = $form.find(".alternative_wrap button");
            
            var $facebook_button = $form.find(".social_wrap .facebook_button");

            if($form.hasClass('login_mode')) {
                $form.attr('action',  '/join/do');
                
            //    $form.find('h3').text('아마도.노트를 시작하세요');

                $form.removeClass('login_mode').addClass('join_mode');
                $button.removeClass('login-button').removeClass('btn-gray').addClass('btn-primary').addClass('join-button').html('회원 가입');
                $toggle_button.removeClass('join-button').removeClass('btn-primary').addClass('btn-gray').addClass('login-button').html('로그인');
                
                $facebook_button.find('span').text('페이스북으로 가입하세요');
            } else {
                $form.attr('action', '/login/do');
                
            //    $form.find('h3').text('아마도.노트를 시작하세요');
                
                $form.removeClass('join_mode').addClass('login_mode');
                $button.removeClass('join-button').removeClass('btn-gray').addClass('btn-primary').addClass('login-button').html('로그인');
                $toggle_button.removeClass('login-button').removeClass('btn-primary').addClass('btn-gray').addClass('join-button').html('회원가입');
                
                $facebook_button.find('span').text('페이스북으로 로그인하세요');
            }
            
            $form.find('.email').focus();                
        }

        $(function() {
            $('.email').focus();
        });
    </script>