<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head> 
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" /> 
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" />
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/includes/jplayer/skin/okay.white/jplayer-white.css" type="text/css" media="screen" />
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); wp_head(); ?>
	
	<!-- google fonts -->
	<link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css' />
	
	<link href='http://fonts.googleapis.com/css?family=Vast+Shadow' rel='stylesheet' type='text/css' />
	
	<link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light' rel='stylesheet' type='text/css' />
	
	<!-- custom css -->
	<style type="text/css">
		<?php if ( of_get_option('of_theme_css') ) { ?>
			<?php echo of_get_option('of_theme_css'); ?>
		<?php } ?>
	</style>
	
</head>

<body <?php body_class( $class ); ?>>
	<div id="wrapper" class="clearfix">
		
		<div id="header-wrapper">
			
			<div id="header">
				<div id="tab">
					<div id="tab-top">
						<!-- social media icons -->
						<?php if ( of_get_option('twitter_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('twitter_icon'); ?>" title="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-twitter.png" alt="twitter" /></a>
						<?php } ?>
						
						<?php if ( of_get_option('dribbble_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('dribbble_icon'); ?>" title="pinterest"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-dribbble.png" alt="pinterest" /></a>
						<?php } ?>
						
						<?php if ( of_get_option('google_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('google_icon'); ?>" title="google"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-google.png" alt="google" /></a>
						<?php } ?>
						
						<?php if ( of_get_option('facebook_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('facebook_icon'); ?>" title="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-facebook.png" alt="facebook" /></a>
						<?php } ?>
						
						<?php if ( of_get_option('vimeo_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('vimeo_icon'); ?>" title="vimeo"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-vimeo.png" alt="vimeo" /></a>
						<?php } ?>
						
						<?php if ( of_get_option('tumblr_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('tumblr_icon'); ?>" title="tumblr"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-tumblr.png" alt="tumblr" /></a>
						<?php } ?>
						
						<?php if ( of_get_option('linkedin_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('linkedin_icon'); ?>" title="linkedin"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-linkedin.png" alt="linkedin" /></a>
						<?php } ?>
						
						<?php if ( of_get_option('flickr_icon') ) { ?>
							<a target="blank" href="<?php echo of_get_option('flickr_icon'); ?>" title="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-flickr.png" alt="flickr" /></a>
						<?php } ?>
					</div>
					
					<?php if(is_single() || is_page()) { ?>
					<?php } else { ?>
					<!-- floating nav/social icon tab -->
					<div id="tab-bottom">
						<a id="prev" href="#" title="scroll up"></a>
						<a id="next" href="#" title="scroll down"></a>
					</div>
					<?php } ?>
				</div>
				
				<div id="logo">
					<!-- grab the logo -->
					<?php if ( of_get_option('of_logo') ) { ?>
			        	<h1 class="logo-img">
							<a href="<?php echo home_url( '/' ); ?>"><img class="logo" src="<?php echo of_get_option('of_logo'); ?>" alt="<?php the_title(); ?>" /></a>
						</h1>
					
					<!-- otherwise show the site title and description -->	
			        <?php } else { ?>

		            <h1 class="logo-text">
		            	<a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name') ?></a>
		            </h1>
			        <?php } ?>
		        </div><!-- logo -->
		        
		        <div class="nav">The easiest way to instantly capture every moment at your wedding.</div>
		        <?php //wp_nav_menu(array('theme_location' => 'main', 'menu_class' => 'nav')); ?>
			</div><!-- header -->
		</div><!-- header wrapper -->
		<div id="header-bottom"></div>