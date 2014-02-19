
	<div class="story-wrap<?php echo !empty($story->cover) ? ' have-cover' : '';?>">
		<div class="story-header">
			<?php echo !empty($story->cover) ? '<div class="story-header-background" style="background-image:url(' . ($mobile_mode ? $story->cover_view_url : $story->cover_url) . ');"></div>' : '';?>
			<div class="story-header-wrap">
			<?php
				if(!empty($story->tags)) {
					$tags = explode(',', $story->tags);
			?>
				<div class="story-tags">
					<?php 
						$tags_text = array();
						foreach($tags as $tag) { 
							$tag = trim($tag); 
							if(empty($tag)) continue;

							$tags_text[] = '<a href="' . site_url('/tag/' . urlencode($tag) . '/' . $story->user_id) . '">' . $tag . '</a>';
						} 

						echo implode(', ', $tags_text);
					?>
				</div>
			<?php
				}
			?>
	          	<h1><span><?php echo $story->title;?></span></h1>
	          	<?php if(!empty($story->sub_title)) { ?>
	          	<h2><?php echo nl2br(trim($story->sub_title));?></h2>
	          	<?php } ?>
	          	<p class="extra">
					<a class="author" href="<?php echo $story->user_url;?>">
						<?php if(!empty($story->user_profile)) { ?>
							<img src="<?php echo $story->user_profile;?>" alt="profile" />
						<?php } else { ?>
							<span class="char_profile"><?php echo mb_substr($story->user_name, 0,1);?></span>
						<?php } ?>
						
						<span><?php echo $story->user_name;?></span>
					</a>
	          		<span class="sep">·</span> 
	          		<span class="date"><?php echo $story->publish_time == '0000-00-00 00:00:00' ? $this->date->string_from_now($story->create_time) :  $this->date->string_from_now($story->publish_time);?></span>
	          	</p>
	        </div>

	      	<?php if(!empty($story->cover)) { ?>
	      		<a class="story-view-more" href="#" onclick="gotoContent(); return false;">내용 읽기</a>
	      	<?php } ?>
	    </div>
		<div class="story-content-wrap">
			<?php echo $story->content_html;?>
		</div>
	</div>

	<?php
		$base_url = $server_url = trim_slashes(site_url('/'));
		if(!empty($story->user_host)) $server_url = trim_slashes('http://' . $story->user_host);
        
        $like_url = empty($story->user_host) ? $base_url . '/' . $story->id :  $server_url . '/' . (!empty($story->permalink) ? $story->permalink . '/' : '') . $story->id;
	?>
	
	<div class="facebook_wrap">
		<hr />
		<div class="permalink_view">
			주소 : <?php echo !empty($story->permalink) ? $server_url . '/' . $story->permalink . '/' . $story->id : $server_url . '/' . $story->id;?>
		</div>
		<div class="fb-like" data-href="<?php echo $like_url;?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
	</div>

<?php
	if(isset($stories_by_user) && count($stories_by_user)) {
?>
	<div class="stories_by_user_wrap">
		<div class="stories_wrap">
			<ul class="stories">
		<?php
			foreach($stories_by_user as $story_by_user) {
		?>
			<?php echo $this->view('slices/story_in_list', array('story'=>$story_by_user));?>
		<?php
			}
		?>
			</ul>
		</div>
	</div>
<?php
	}
?>

<script type="text/javascript">
	var is_mobile = false;
	if(typeof window.orientation !== 'undefined') {
		is_mobile = true;
	}

	var $window = $(window);
	var $content = $("#content");
	var $scroll_block = $(".scroll-block");

	function gotoContent() {
		$("html, body").animate({ scrollTop: $window.height() }, 600);
	}

	function onResize() {
		var size = Math.floor(($(window).width() - $content.outerWidth()) / 2);
		var window_height = $window.height();

		$scroll_block.css('marginLeft', '-'+size+'px');
		$scroll_block.css('marginRight', '-'+size+'px');

		// cover
		$(".have-cover .story-header").height(window_height);

		$(".have-cover .story-header").css('marginLeft', '-'+size+'px').css('marginRight', '-'+size+'px');
		$(".have-cover .story-header .story-header-wrap").css('paddingLeft', size+'px').css('paddingRight', size+'px');

		// image panorama
		$(".story-content-wrap p.panorama_wrap").css('marginLeft', '-'+size+'px').css('marginRight', '-'+size+'px');
	}

	$window.ready(function() {		
        onResize();
        
		// image class wrap;
		$('.story-content-wrap img').each(function(index, image) {
			var $image = $(image);

			var inputClassNames = new Array();
			var classNames = image.className.split(' ');
			if(classNames.length > 0) {
				$.each(classNames, function(i, className) {
					var className = className.replace(' ','');
					if(className != '')
						inputClassNames.push("image_" + className + "_wrap");
				});

				var $parent = $image.parents('p');
				$parent.addClass('image_wrap ' + inputClassNames.join(' ') + ' have_container_count_' + $parent.find('span.image_container').length);
			}
		});

		// have_description
		$('.story-content-wrap img.have_description').each(function(index, image) {
			var $image = $(image);
			$image.parents('p').addClass('image_have_description_wrap')
		});
		

		// image block
		$(".story-content-wrap blockquote p img").each(function(index, image) {			
			var $image = $(image);
			var className = $image.attr('class');

			if(typeof(className) == 'undefined' || className == '') {
				className = 'default';
			}

			var p_classNames = new Array();
			var classNames = className.split(' ');
			for(var i=0;i<classNames.length;i++) {
				p_classNames[i] = 'image_' + classNames[i] + '_wrap';
				classNames[i] = 'image_' + classNames[i] + '_block';
			}

			$image.parents('p').addClass(p_classNames.join(' ')).parent('blockquote').addClass('image_block ' + classNames.join(' '));
		});
	});

	$window.resize(function() {
		onResize();
	});

<?php
	if(!empty($story->cover)) {
?>
		var $story_header_background = $('.story-header-background');
		var $story_view_more = $('.story-view-more');
		var $story_header = $('.story-header');
		var $story_header_wrap = $('.story-header-wrap');

		var story_header_wrap_offset = $story_header_wrap.offset();
<?php
	}
?>
	$window.scroll(function() {
		if(is_mobile) return false;

		var window_scroll = $window.scrollTop();
		var window_height = $window.height();

		$(".scroll-block").each(function(index, block) {
			var $now = $(block);
			var offset = $now.offset();

			var yPos = -(($window.scrollTop() - offset.top) / 6);
			var coords = '50% '+ yPos + 'px';

			$now.css({ backgroundPosition: coords });
		});
<?php
	if(!empty($story->cover)) {
?>
		if(window_scroll < window_height) {
			var offset = $story_header_wrap.offset();
			var remain_header_height = $story_header.height() - window_scroll;
			var story_height = $story_header_wrap.outerHeight();

			var v = (($story_header.height()+window_scroll) / 2) - (story_height / 2) - story_header_wrap_offset.top;
			if(v >= 0) {
				if(story_height <= remain_header_height) {
					$story_header_wrap.css('margin-bottom', '-' + Math.round(v) + 'px');
				} else {
				}
					var pos = (1 - ((story_height - remain_header_height) / story_height)) * 0.3;
					$story_header_wrap.css('opacity', pos);
			} else {
				$story_header_wrap.css('margin-bottom', 0).css('opacity', 1);
			}


			var pos = (1-(window_scroll / window_height / 3)) - 0.4;
			$story_header_background.css('opacity', pos);

			pos = (1-(window_scroll / (window_height / 3)));

			if(pos == 0) {
				$story_view_more.hide();
			} else {
				$story_view_more.show();
			}
			$story_view_more.css('opacity', pos - 0.2 );
		}
<?php
	}
?>
	})
</script>