<div class="post-content">
	<div class="post-video">
		<div class="post-video-holder">
			<?php echo get_post_meta($post->ID, 'video', true) ?>
		</div>
		<?php the_content(); ?>
	</div>
</div>