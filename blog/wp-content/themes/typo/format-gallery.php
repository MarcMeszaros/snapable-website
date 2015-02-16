<div class="post-content">
	<div class="post-gallery">
		
		<!-- grab the image attachments -->		
		<?php 
			//find images in the content with "wp-image-{n}" in the class name
			preg_match_all('/<img[^>]?class=["|\'][^"]*wp-image-([0-9]*)[^"]*["|\'][^>]*>/i', get_the_content(), $result);  
			
			$exclude_imgs = $result[1];
			
			$args = array(
				'order'          => 'ASC',
				'orderby'        => 'menu_order ID',
				'post_type'      => 'attachment',
				'post_parent'    => $post->ID,
				'exclude'		 => $exclude_imgs,
				'post_mime_type' => 'image',
				'post_status'    => null,
				'numberposts'    => -1,
			);
			
			$attachments = get_posts($args);
			if ($attachments) {
			
			echo "<div class='flexslider'><ul class='slides'>";
				foreach ($attachments as $attachment) {
					echo "<li> <a href='". get_attachment_link($attachment_id) ."'>";
					echo wp_get_attachment_image($attachment->ID, 'large-image', false, false);
					echo "</a></li>";
				}
			echo "</ul></div>"; 
			
			}
		?>
		
	</div>
	<?php the_content(); ?>
</div>