
	<li class="story<?php echo !empty($story->cover) ? ' have_cover' : '';?><?php echo $story->is_publish == 'yes' ? ' is_publish' : ' is_not_publish';?>">
	<?php echo !empty($story->cover) ? '<div class="cover_background" style="background-image:url('.$story->cover_list_url.');"><a class="link" href="'.site_url('/view/'.$story->id).'"></a>' : '';?>
		<div class="info">
		<h3><?php echo $story->is_publish == 'no' ? '<span class="draft draft_icon"><span class="hidden">[임시저장]</span></span> ' : '';?><a href="<?php echo site_url('/view/'.$story->id);?>"><?php if(!empty($story->cover)) {?><span class="cover_image" style="background-image:url(<?php echo $story->cover_thumbnail_url;?>);"></span> <?php } ?><?php echo $story->title;?></a></h3>
		<?php if(!empty($story->sub_title)) { ?><p class="summary"><?php echo truncate($story->sub_title, 100);?></p><?php } ?>
		</div>
	<?php echo !empty($story->cover) ? '</div>' : '';?>
	
		<span class="author">
			<a href="<?php echo $story->user_url;?>"<?php echo !empty($story->user_profile) ? ' class="have_profile_image"' : '';?>>
				<?php if(empty($story->user_profile)) { ?><span class="char_profile"><?php echo mb_substr($story->user_name, 0,1);?></span><?php } else { ?><span class="char_profile_image" <?php if(!empty($story->user_profile)) { ?> style="background:url(<?php echo $story->user_profile;?>) no-repeat; background-size:cover;"<?php } ?>></span><?php } ?><span class="char_name"><?php echo $story->user_name;?></span>
			</a>
		</span>
	</li>