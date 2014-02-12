	<style style="text/css">
   		@import url("<?php echo site_url('/css/search_welcome.css');?>");
	</style>

	<div class="search_stories_wrap">
		<div class="container">
			<div class="search_wrap">
				<div class="title"><?php echo $search_mode;?></div>
				<div class="seq">/</div>
				<div class="value"><?php echo $search_value;?></div>
				<div class="clearfix"></div>
			</div>
			
			<div class="stories_wrap">
				<ul class="stories">
				<?php
					if(count($stories)) {
					 foreach($stories as $story) { 
				?>
							<?php echo $this->view('slices/story_in_list', array('story'=>$story));?>
				<?php } 
					} else {
				?>			
						<li class="empty">스토리가 아직 없습니다.</li>
				<?php
					}
				?>
				</ul>
			</div>
		</div>
	</div>