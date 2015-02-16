<div class="post-content">
	<?php if ( has_post_thumbnail() ) { ?>
		<a class="post-image" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'post-image' ); ?></a>
	<?php } ?>
	
	<?php the_content(); ?>
</div>