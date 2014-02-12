<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title><?php echo $title_for_layout;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="title" content="">
	<meta name="description" content="">
	<meta name="keywords" content="">
	
<?php if(isset($og_title)) { ?><meta property="og:title" content="<?php echo $og_title;?>" /><?php } ?>
<?php if(isset($og_description)) { ?><meta property="og:description" content="<?php echo $og_description;?>" /><?php } ?>
<?php if(isset($og_url)) { ?><meta property="og:url" content="<?php echo $og_url;?>" /><?php } ?>
<?php if(isset($og_map_name)) { ?><meta property="og:map_name" content="<?php echo $og_map_name;?>" /><?php } ?>
<?php if(isset($og_image)) { ?><meta property="og:image" content="<?php echo $og_image;?>" /><?php } ?>
    
    <!--<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.0.1-p7/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="<?php echo site_url('/offline/bootstrap.css');?>">
    <link rel="stylesheet" href="<?php echo site_url("/css/bootstrap-custom.css");?>">
    <link rel="stylesheet" href="<?php echo site_url("/css/bootstrap-white.css");?>">

    <link rel="stylesheet" href="<?php echo site_url("/css/layout.css");?>">

	<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
	<script type="text/javascript" src="<?php echo site_url('/offline/jquery.js');?>"></script>
	<!--<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>-->
	<script type="text/javascript" src="<?php echo site_url('/offline/underscore.js');?>"></script>

	<!--<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.0.1-p7/js/bootstrap.min.js"></script>-->
	<script type="text/javascript" src="<?php echo site_url('/offline/bootstrap.js');?>"></script>

	<script type="text/javascript" src="<?php echo site_url("/js/plugin/jquery.placeholder.js");?>"></script>

	<script type="text/javascript" src="<?php echo site_url("/js/basic.js");?>"></script>

	<script type="text/javascript">
		var service = {
			url: "<?php echo site_url('/');?>"
		};

		$(function() {
			$(".tip").tooltip({container:'body',placement:'bottom'});
			$("input, textarea").placeholder();
		})
	</script>
	        
<?php echo $styles_for_layout;?>
<?php echo $scripts_for_layout;?>
</head>
<body class="<?php echo isset($body_class) ? $body_class . ' ' : '';?><?php echo isset($body_full) && $body_full ? 'full' : '';?>">        
	<div id="wrap">
	    <div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/ko_KR/all.js#xfbml=1&appId=706477449383479";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		
		<div class="navbar navbar-simple navbar-static-top<?php echo isset($black_base_navbar) && $black_base_navbar ? ' navbar-black-base' : '';?>">	
      	<?php
      		$nav_in_container = isset($nav_in_container) ? $nav_in_container : false;
      		if($nav_in_container) {
      	?>
		<div class="container">
		<?php
			}
		?>	
	        <?php if($header_visible) { ?>
	        <div class="navbar-header"><a class="navbar-brand" href="<?php echo $header_link;?>"><?php echo $header_title;?></a></div>
	        <?php } ?>
			
			<?php 
				$hide_nav_buttons = isset($hide_nav_buttons) ? $hide_nav_buttons : false;
				$hide_make_button = isset($hide_make_button) ? $hide_make_button : false;
				$hide_setting_button = isset($hide_setting_button) ? $hide_setting_button : false;
				
				$show_logout_button = isset($show_logout_button) ? $show_logout_button : false;

				if(!$hide_nav_buttons) {
			?>
			<ul class="nav nav-pills pull-right">
	           <?php
		        	if($current_user->id) {
		        		if(isset($can_edit) && $can_edit) {
		        ?>
		        <li><a class="btn" href="<?php echo $edit_url;?>">편집</a></li>
		        <?php
		        		} else if(!$hide_make_button) {
		        ?>
		        <li><a class="btn" href="<?php echo site_url('/edit');?>">설정</a></li>
		        <?php
		        		}

		        		if($show_logout_button) {
				?>		        
				<li><a class="btn" href="<?php echo site_url('/logout');?>">로그아웃</a></li>
				<?php		        			
		        		}

		        		if(!$hide_make_button) {
		        ?>
		       	<li><a class="btn btn-primary" href="<?php echo site_url('/write');?>">스토리 만들기</a></li>
		        <?php 		
		        		}

		        	} else {
		        ?>
		        <li><a class="btn" href="<?php echo site_url('/login');?>">로그인</a></li>
		        <li><a class="btn btn-primary" href="<?php echo site_url('/join');?>">회원가입</a></li>
		        <?php
					}
				?>
	        </ul>
	        <?php
	        	}
	        ?>
	    <?php
	    	if($nav_in_container) {
      	?>
			</div>
		<?php
			}
		?>	
	    </div>
      
      	<?php
      		$in_container = isset($in_container) ? $in_container : false;
      		if($in_container) {
      	?>
		<div class="container">
		<?php
			}
		?>
		    <div id="content" class="main-content">

		    	<?php echo $content_for_layout;?>
		    </div>
		<?php
			if($in_container) {
		?>
		</div>
		<?php
			}
		?>
		<div class="container">
		    <div id="footer">
		    	<ul>
		    		<li>
		    			© <a href="<?php echo $this->config->item('server_url');?>">WithStories</a>
		    		</li>
		    	</ul>
		    </div>
		</div>
	</div>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-24163191-3', 'amado.kr');
	  ga('send', 'pageview');

	</script>
</body>
</html>