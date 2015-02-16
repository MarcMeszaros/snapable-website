<?php get_header(); ?>

		<div id="content" class="standard">
			
			<!-- conditional subtitles -->
			<?php if(is_search()) { ?>
				<div class="sub-title"><?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $count = $allsearch->post_count; _e(''); echo $count . ' '; wp_reset_query(); ?><?php _e('Search Results for','okay'); ?> "<?php the_search_query() ?>" </div>
			<?php } else if(is_tag()) { ?>
				<div class="sub-title"><?php _e('Tag:','okay'); ?> <?php single_tag_title(); ?></div>
			<?php } else if(is_day()) { ?>
				<div class="sub-title"><?php _e('Archive:','okay'); ?> <?php echo get_the_date(); ?></div>
			<?php } else if(is_month()) { ?>
				<div class="sub-title"><?php _e('Archive:','okay'); ?> <?php echo get_the_date('F Y'); ?></div>
			<?php } else if(is_year()) { ?>
				<div class="sub-title"><?php _e('Archive:','okay'); ?> <?php echo get_the_date('Y'); ?></div>
			<?php } else if(is_404()) { ?>
				<div class="sub-title"><?php _e('404 - Page Not Found!','okay'); ?></div>
			<?php } else if(is_category()) { ?>
				<div class="sub-title"><?php single_cat_title(); ?></div>
			<?php } else if(is_author()) { ?>
				<div class="sub-title"><?php the_author_posts(); ?> <?php _e('posts by','okay'); ?> <?php
				$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); echo $curauth->nickname; ?></div>		
			<?php } ?>
				
			<!-- grab the posts -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<div class="post-wrap">
				<div <?php post_class('post clearfix'); ?>>
					<div class="post-title">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						
						<?php if(is_page()) { } else { ?>
							<ul class="post-meta">
								<li class="date"><?php echo get_the_date(); ?></li>
								<li class="author"><?php the_author_link(); ?></li>
								<li class="title-comments"><a href="<?php the_permalink(); ?>/#comments" title="comments"><?php comments_number('No Comments','1 Comment','% Comments'); ?></a></li>
								<?php the_tags('<li class="tags">',', ','</li>'); ?>
								<?php if ( in_category( 'uncategorized' )) { } else { ?>
								<li class="category">
									<?php the_category(', '); ?>
								</li>
								<?php } ?>
							</ul>
						<?php } ?>
					</div>
					
					<!-- uses the post format -->
					<?php
						if(!get_post_format()) {
						   get_template_part('format', 'standard');
						} else {
						   get_template_part('format', get_post_format());
						};
					?>
					
					<!-- grab comments on single pages -->
					<?php if(is_single ()) { ?>
						<div class="comments">
							<?php comments_template(); ?>
						</div>
					<?php } ?>
					
				</div><!-- post -->
			</div><!-- post wrap-->
			
			<?php endwhile; ?>
			
			<!-- post navigation -->
			<?php if (show_posts_nav()) { ?>
			
				<?php if(is_single() || is_page()) { } else { ?>	
					<div class="post-nav">
						<div class="postnav-left"><?php previous_posts_link(__('&larr; Previous Page', 'okay')) ?></div>
						<div class="postnav-right"><?php next_posts_link(__('Next Page &rarr;', 'okay')) ?></div>	
						<div style="clear:both;"></div>
					</div>
				<?php } ?>
			
			<?php } ?>
			
			<?php endif; ?>
		</div><!-- content -->

<?php get_footer(); ?>	
