
	<div class="story-wrap<?php echo !empty($story->cover) ? ' have-cover' : '';?>">
		<div class="story-header">
			<?php echo $story->header_html;?>
	    </div>
	</div>

	<div class="container">
		<div class="story-content-wrap view_content">
			<?php echo $story->content_html;?>
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
	</div>
	
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

	$window.scroll(function() {
		if(is_mobile) return false;

		$(".scroll-block").each(function(index, block) {
			var $now = $(block);
			var offset = $now.offset();

			var yPos = -(($window.scrollTop() - offset.top) / 6);
			var coords = '50% '+ yPos + 'px';

			$now.css({ backgroundPosition: coords });
		});
	})
</script>