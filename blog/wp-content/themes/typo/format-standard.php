<div class="post-content">
	<div class="post-standard">
		<?php if(is_single()) { ?>
			<?php the_content(); ?>
		<?php } else { ?>
		
			<?php global  $more; $more = FALSE; ?>
			<?php the_content('Read more &raquo;'); ?>
		
		<?php } ?>
	</div>
</div>