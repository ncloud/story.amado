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
    
    <link type="text/css" rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo site_url("/css/bootstrap-custom.css");?>">
    <link rel="stylesheet" href="<?php echo site_url("/css/bootstrap-white.css");?>">

	<link type="text/css" rel="stylesheet" href="<?php echo site_url("/css/user.css");?>" />    
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.0.1/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="<?php echo site_url("/js/basic.js");?>"></script>
	<script type="text/javascript" src="<?php echo site_url("/js/action/login.js");?>"></script>
	
	<script type="text/javascript">
		var service = {url: "<?php echo site_url('/');?>"};
	</script>
	        
	<?php echo $styles_for_layout;?>
	<?php echo $scripts_for_layout;?>
</head>
<body>		
    <div id="fb-root"></div> 
    <script type="text/javascript" src="http://connect.facebook.net/ko_KR/all.js"></script>
    <script type="text/javascript"> 
        FB.init({
             appId  : '<?php echo $this->config->item('facebook_appid');?>',
             channelUrl : '<?php echo site_url('/files/channel.php');?>', // Channel File
             status : true, // check login status
             cookie : true // enable cookies to allow the server to access the session
         });
    </script>
    
	<div id="content">
		<?php echo $content_for_layout;?>
	</div>
</body>
</html>