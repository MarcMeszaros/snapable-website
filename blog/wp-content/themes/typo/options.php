<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	
	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
	// Alignment
	$bg_image = array("light" => "Light","dark" => "Dark");
	
	// Backgrounds
	$bg_array = array("light_linen" => "Light Linen","dark_linen" => "Dark Linen","light_stone" => "Light Stone", "dark_stone" => "Dark Stone", "light_scales" => "Light Scales", "light_plaid" => "Light Plaid", "dark_plaid" => "Dark Plaid");
	
	// Multicheck Defaults
	$multicheck_defaults = array("one" => "1","five" => "1");
	
	// Background Defaults
	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_stylesheet_directory_uri() . '/images/';
		
	$options = array();
	
	// ------------- Basic Settings Tab  ------------- //	
		
	$options[] = array( "name" => "Basic Settings",
						"type" => "heading");
						
	$options[] = array( 'name' => __('Logo Upload', 'okay'),
						"desc" => __('Upload your image to use in the header.', 'okay'),
						"id" => "of_logo",
						"type" => "upload");
						
	$options[] = array( "name" => __('Link Color', 'okay'),
						"desc" => __('Select the color you would like your links to be. The demo site uses #fa4e4e.', 'okay'),
						"id" => "of_colorpicker",
						"std" => "#fa4e4e",
						"type" => "color");																				
						
	$options[] = array( "name" => __('Tracking Code', 'okay'),
						"desc" => __('Put your Google Analytics or other tracking code here.', 'okay'),
						"id" => "of_tracking_code",
						"std" => "",
						"type" => "textarea"); 	
						
						
	// ------------- Basic Settings Tab  ------------- //																																											
	$options[] = array( "name" => __('Social Media Links', 'okay'),
						"type" => "heading");														
	
	$options[] = array( "name" => __('Twitter URL', 'okay'),
						"desc" => __('Enter the full url to your Twitter profile.', 'okay'),
						"id" => "twitter_icon",
						"std" => "",
						"type" => "text");	
						
	$options[] = array( "name" => __('Google+ URL', 'okay'),
						"desc" => __('Enter the full url to your Google+ profile.', 'okay'),
						"id" => "google_icon",
						"std" => "",
						"type" => "text");	
	
	$options[] = array( "name" => __('Dribbble URL', 'okay'),
						"desc" => __('Enter the full url to your Dribbble profile.', 'okay'),
						"id" => "dribbble_icon",
						"std" => "",
						"type" => "text");		
	
	$options[] = array( "name" => __('Vimeo URL', 'okay'),
						"desc" => __('Enter the full url to your Vimeo profile.', 'okay'),
						"id" => "vimeo_icon",
						"std" => "",
						"type" => "text");						
	
	$options[] = array( "name" => __('Facebook URL', 'okay'),
						"desc" => __('Enter the full url to your Facebook profile.', 'okay'),
						"id" => "facebook_icon",
						"std" => "",
						"type" => "text");	
						
	$options[] = array( "name" => __('Flickr URL', 'okay'),
						"desc" => __('Enter the full url to your Flickr profile.', 'okay'),
						"id" => "flickr_icon",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('Tumblr URL', 'okay'),
						"desc" => __('Enter the full url to your Tumblr profile.', 'okay'),
						"id" => "tumblr_icon",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => __('LinkedIn URL', 'okay'),
						"desc" => __('Enter the full url to your LinkedIn profile.', 'okay'),
						"id" => "linkedin_icon",
						"std" => "",
						"type" => "text");	
						
	
	// ------------- Custom CSS Tab  ------------- //
	
	$options[] = array( "name" => __('Custom CSS', 'okay'),
						"type" => "heading");
	
	$options[] = array( "name" => __('Custom CSS', 'okay'),
						"desc" => __('If you would like to make styling modifications, you can do that here. Doing it here will prevent your modifications from being overwritten if/when you update the theme.', 'okay'),
						"id" => "of_theme_css",
						"std" => "",
						"type" => "textarea"); 						
						
	
	// ------------- Okay Themes Tab  ------------- //
						
	$options[] = array( "name" => __('Support', 'okay'),
						"type" => "heading");					
						
	$options[] = array( "name" => __('Theme Documentation & Support', 'okay'),
						"desc" => "<p class='okay-text'>Theme support and documentation is available for all customers. Visit <a target='blank' href='http://okaythemes.com/support'>Okay Themes Support Forum</a> to get started. Simply follow the ThemeForest or Okay user instructions to verify your purchase and get a support account.</p>
						
						<div class='okay-buttons'><a target='blank' class='okay-button video-button' href='https://vimeo.com/31999166'><span class='okay-icon icon-video'>Typo Install Video</span></a><a target='blank' class='okay-button help-button' href='http://themes.okaythemes.com/docs/typo/index.html'><span class='okay-icon icon-help'>Typo Help File</span></a><a target='blank' class='okay-button support-button' href='http://okaythemes.com/support'><span class='okay-icon icon-support'>Support Forum</span></a><a target='blank' class='okay-button custom-button' href='http://okaythemes.com/customization'><span class='okay-icon icon-custom'>Customize Theme</span></a></div>
						
						<h4 class='heading'>More Themes by Okay Themes</h4>
						
						<div class='embed-themes'></div>
						
						",
						"type" => "info");																													
								
	return $options;
}