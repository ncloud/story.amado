      <style type="text/css">
   		@import url("<?php echo site_url('/css/welcome.css');?>");
      </style>

      <div class="stories_wrap">   
      	<ul class="story_tab nav nav-simple">
            <?php
                  if($current_user->id) {
            ?>
		  <li<?php echo $mode == 'my' ? ' class="active"' : '';?>><a href="<?php echo site_url('/my');?>">내 스토리</a></li>
            <?php
                  } 
            ?>
		  <li<?php echo $mode == 'all' ? ' class="active"' : '';?>><a href="<?php echo site_url('/all');?>">전체 스토리</a></li>
		</ul>     

		<ul class="stories">
		<?php
			if(count($stories)) {
				foreach($stories as $story) {
		?>
			<?php echo $this->view('slices/story_in_list', array('story'=>$story));?>
		<?php
				}
		?>
			<!--<li class="more"><a href="#" onclick="morestory(0);">더보기</a></li>-->
		<?php
			} else {
		?>
			<li class="empty">스토리가 아직 없습니다.</li>
		<?php
			}
		?>
		</ul>
	  </div>