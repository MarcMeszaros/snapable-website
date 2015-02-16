<?php

//-----------------------------------  // Load Scripts //-----------------------------------//

add_action('wp_enqueue_scripts', 'ok_theme_js');
function ok_theme_js() {
	if (is_admin()) return;
	
	//Register jQuery
	wp_enqueue_script('jquery');
	
	//Custom JS
	wp_enqueue_script('custom_js', get_template_directory_uri() . '/includes/js/custom.js', false, false , true);
	
	//Flexslider
	wp_enqueue_script('flex_js', get_stylesheet_directory_uri() . '/includes/js/jquery.flexslider-min.js', false, false , true);
	
	//jPlayer
	wp_enqueue_script('jplayer_js', get_stylesheet_directory_uri() . '/includes/jplayer/js/jquery.jplayer.min.js', false, false , true);
	
	//Sticky Sidebar
	wp_enqueue_script('scroll_js', get_stylesheet_directory_uri() . '/includes/js/jquery.scrollTo-1.4.2-min.js', false, false , true);
	
	//Twitter
	wp_enqueue_script('twitter', 'http://widgets.twimg.com/j/2/widget.js', false, true);
}


//-----------------------------------  // Add Editor Styles //-----------------------------------//

require_once(dirname(__FILE__) . "/includes/editor/add-styles.php");


//-----------------------------------  // Auto Feed Links //-----------------------------------//

add_theme_support( 'automatic-feed-links' );


//-----------------------------------  // Add Widgets //-----------------------------------//

require_once(dirname(__FILE__) . "/includes/widgets/twitter.php");
require_once(dirname(__FILE__) . "/includes/widgets/flickr.php");


//-----------------------------------  // Custom Excerpt Limit //-----------------------------------//

function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}


//-----------------------------------  // Add Menus //-----------------------------------//

add_theme_support( 'menus' );
register_nav_menu('main', 'Main Menu');
register_nav_menu('footer', 'Footer Menu');
register_nav_menu('custom', 'Custom Menu');


//-----------------------------------  // Add Custom Image Sizes //-----------------------------------//

add_theme_support('post-thumbnails');
set_post_thumbnail_size( 150, 150, true ); // Default Thumb
add_image_size( 'post-image', 595, 274, true ); // Large Post Image
add_image_size( 'gallery-image', 615, 350, true ); // Gallery Post Image


//-----------------------------------  // Add Post Formats //-----------------------------------//

add_theme_support('post-formats', array( 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video', 'audio'));


//-----------------------------------  // Add Background Support //-----------------------------------//

add_custom_background();


//-----------------------------------  // Add Localization //-----------------------------------//

load_theme_textdomain( 'slate', TEMPLATEPATH . '/includes/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/includes/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );


//-----------------------------------  // Register Widget Areas //-----------------------------------//

if ( function_exists('register_sidebars') )

register_sidebar(array(
'name' => 'Footer Widget Area Left',
'description' => 'Widgets in this area will be shown in the widget drop-down area..',
'before_widget' => '<div class="widget">',
'after_widget' => '</div>'
));

register_sidebar(array(
'name' => 'Footer Widget Area Center',
'description' => 'Widgets in this area will be shown in the widget drop-down area..',
'before_widget' => '<div class="widget">',
'after_widget' => '</div>'
));

register_sidebar(array(
'name' => 'Footer Widget Area Right',
'description' => 'Widgets in this area will be shown in the widget drop-down area..',
'before_widget' => '<div class="widget">',
'after_widget' => '</div>'
));

/** If more than one page exists, return TRUE. */
function show_posts_nav() {
	global $wp_query;
	return ($wp_query->max_num_pages > 1);
}


//-----------------------------------  // Custom Comments //-----------------------------------//

function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
		
		<div class="comment-text" id="comment-<?php comment_ID(); ?>">
			<?php comment_text() ?>
		
			<p class="reply">
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</p>
		
			<?php if ($comment->comment_approved == '0') : ?>
			<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
			<?php endif; ?>    
		</div>
		
		<div class="comment-author vcard clearfix">
			<?php echo get_avatar( $comment->comment_author_email, 48 ); ?>
		
			<div class="comment-meta commentmetadata">
				<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
				<div style="clear:both;"></div>
				<a class="comment-time" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?>
			</div>
		</div>
		
		<div style="clear:both;"></div>
<?php
}


//-----------------------------------  // Options Framework Stuff — Leave It Alone! //-----------------------------------//

okay_options_check();
function okay_options_check()
{
  if ( !function_exists('optionsframework_activation_hook') )
  {
    add_thickbox(); // Required for the plugin install dialog.
    add_action('admin_notices', 'okay_options_check_notice');
  }
}

// The Admin Notice
function okay_options_check_notice()
{
?>
  <div class='updated fade'>
    <p>The Options Framework plugin is required for this theme to function properly. <a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=options-framework&TB_iframe=true&width=640&height=589'); ?>" class="thickbox onclick">Install now</a>.</p>
  </div>
<?php
}

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */

if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = false) {
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( isset($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
}

/*
* This is an example of how to override a default filter
* for 'textarea' sanitization and $allowedposttags + embed and script.
*/

add_action('admin_init','optionscheck_change_santiziation', 100);
function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'custom_sanitize_textarea' );
}
function custom_sanitize_textarea($input) {
    global $allowedposttags;
    $custom_allowedtags["embed"] = array(
      "src" => array(),
      "type" => array(),
      "allowfullscreen" => array(),
      "allowscriptaccess" => array(),
      "height" => array(),
          "width" => array()
      );
      $custom_allowedtags["script"] = array();
      $custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
      $output = wp_kses( $input, $custom_allowedtags);
    return $output;
}

//-----------------------------------  // Add Support Tab To Theme Options //-----------------------------------//

require_once(dirname(__FILE__) . "/includes/support/support.php");