<div id="footer-top"></div>
		<div id="widget-wrapper">
			<div id="widgets" class="clearfix">
				<div class="widget-column">
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widget Area Left') ) : else : ?>		
					<?php endif; ?>
				</div>
				
				<div class="widget-column">
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widget Area Center') ) : else : ?>		
					<?php endif; ?>
				</div>
				
				<div class="widget-column">
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widget Area Right') ) : else : ?>		
					<?php endif; ?>
				</div>
			</div>
			
			<div class="widget-footer">
				<p class="copyright" style="display:none;">&copy; <?php echo date("Y"); ?> <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a> | <?php bloginfo('description'); ?></p>
				
				<?php get_search_form(); ?>
				
				<?php wp_nav_menu(array('menu_id' => 'menu-footer', 'theme_location' => 'footer', 'menu_class' => 'footer-nav')); ?>
			</div>
		</div><!-- widget wrapper -->	
	</div><!-- wrapper -->

	<!-- google analytics code -->
	<?php if ( of_get_option('of_tracking_code') ) { ?>
		<?php echo of_get_option('of_tracking_code'); ?>
	<?php } ?>
	
	<?php wp_footer(); ?>

</body>
</html>