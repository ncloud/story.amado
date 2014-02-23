
	function __default_full_theme_resize() {
		var size = Math.floor(($(window).width() - $content.outerWidth()) / 2);
		var window_height = $window.height();

		// cover
		$(".have-cover .story-header").height(window_height);

	}

	$(window).ready(__default_full_theme_resize);
	$(window).resize(__default_full_theme_resize);


	{{#if story.have_cover}}
		var $story_header_background = $('.story-header-background');
		var $story_view_more = $('.story-view-more');
		var $story_header = $('.story-header');
		var $story_header_wrap = $('.story-header-wrap');

		var story_header_wrap_offset = $story_header_wrap.offset();

		{{#unless global.is_mobile_mode}}
			$(window).scroll(function() {
				var window_scroll = $window.scrollTop();
				var window_height = $window.height();

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
			});
		{{/unless}}
	{{/if}}