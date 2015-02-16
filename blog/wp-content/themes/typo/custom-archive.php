<?php 
/* 
Template Name: Custom Archive
*/ 
?>	
	
<?php get_header(); ?>
		
		<div id="content">
			
			<!-- grab the posts -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<div class="post-wrap">
				<div <?php post_class('post clearfix'); ?>>
					<div class="post-title">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					</div>
					
					<div id="archive">
						<div class="columnize">
							<div class="archive-box">
							<h4><?php _e('Archive By Day','okay'); ?></h4>
							<ul>
								<?php wp_get_archives('type=daily&limit=15'); ?>
							</ul>
							</div>
							
							<div class="archive-box">
							<h4><?php _e('Archive By Month','okay'); ?></h4>
							<ul>
								<?php wp_get_archives('type=monthly&limit=12'); ?>
							</ul>
							</div>
							
							<div class="archive-box">
							<h4><?php _e('Archive By Year','okay'); ?></h4>
							<ul>
								<?php wp_get_archives('type=yearly&limit=12'); ?>
							</ul>
							</div>
						</div><!-- column -->
						
						<div class="columnize">
							<div class="archive-box">
								<h4><?php _e('Latest Posts','okay'); ?></h4>
								<ul>
									<?php wp_get_archives('type=postbypost&limit=15'); ?>
								</ul>
							</div>
						</div><!-- column -->
						
						<div class="columnize">
							<div class="archive-box">
								<h4><?php _e('Contributors','okay'); ?></h4>
								<ul>
									<?php wp_list_authors('show_fullname=1&optioncount=1&orderby=post_count&order=DESC'); ?>
								</ul>
							</div>
							
							<div class="archive-box">
								<h4><?php _e('Pages','okay'); ?></h4>
								<ul>
									<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
								</ul>
							</div>
							
							<div class="archive-box">
								<h4><?php _e('Categories','okay'); ?></h4>
								<ul>
									<?php wp_list_categories('orderby=name&title_li='); ?> 
								</ul>
							</div>
						</div><!-- column -->
					</div><!-- archive -->
					
				</div><!-- post -->
			</div><!-- post wrap -->
			
			<?php endwhile; ?>
			<?php endif; ?>
			
		</div><!-- content -->
		

<?php get_footer(); ?>	
	