	<style style="text/css">
   		@import url("<?php echo site_url('/css/user_welcome.css');?>");
	</style>

	<div class="user_stories_wrap">
		<div class="user_wrap">
			<?php if(!empty($user->cover)) { ?>
			<div class="user_cover" style="background-image:url(<?php echo $user->cover;?>);"></div>
			<?php } ?>

			<div class="information">
				<div class="information_data">
					<h3><?php echo $user->display_name;?></h3>
					<p><?php echo auto_link($user->description);?></p>
				</div>
			</div>
		</div>
		<div class="container">
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