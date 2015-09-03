=== Pinterest Image Pinner From Collective Bias ===
Contributors: chriswhittle,jaythornton,jonbeers,clintford,stephenprater,mattjacobson 
Donate link: http://collectivebias.com/
Tags: pinterest, collective bias
Requires at least: 3.0.0
Tested up to: 3.9
Stable tag: trunk

Adds Pin This button to all post images

== Description ==

Extremely lightweight jquery based plugin.  Adds "Pin This" button to all images.

Thanks to Elembee for this walkthrough http://elembee.com/blogkeeping-pin-it-for-images/

== Installation ==

1. Upload plugin's zip to the `/wp-content/plugins/` directory
2. Extract files
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Enjoy
5. Customize settings if needed

== Frequently Asked Questions ==

It's not working?  
2 things to check 
1) Is another plugin interfering?  The plugin uses jQuery on document ready which if it is interrupted by another pluggin will never fire here.
2) Check the selectors, does it reflect what the HTML is on your page?

Can I reload it?
call cb_pinterest_plugin_load() from javascript and it will repin everything that might not have been available on load

My style is still off ya jerk!
Well calm down, the plugin works by trying to clone style off the image but it can miss some.  To tweak what's missing you will need 
to add the following to your themes custom style sheet and add the missing style attributes (which you can use firebug or something similar to find)
.cb_pin_images {
	/*INSERT MISSING STYLE HERE*/
}

Wait the image should be centered!
find the image id using firebug and find the id #pin_images_2 is our example (but also remember it could change if multiple images are added to the page)
add the following to your themes custom style sheet

#pin_images_2 {
	margin-left:auto;
	margin-right:auto;
}
or if it's plugin specific use your plugin class

.zlrecipe-container-border .cb_pin_images {
    margin-left:auto;
	margin-right:auto;
}

== Screenshots ==

1. Example post
2. Admin Page

== Changelog ==

= 1.0 =
* Birthed

= 1.1 =
Fixed issue with dimensions not working.

= 1.2 = 
Fixed 2nd issue with dimenstions.
Added Settings Link To Plugins Screen

= 1.3 = 
Fixed issue with 0 dimensions

= 1.4 = 
Wrapped function so it could be called after load.  cb_pinterest_plugin_load()

= 1.5 =
Changed the way it got the width and height of the image.  

= 1.6 =
Added the move css attributes other than width and height to help with formatting(border,margin,padding).   

= 1.7 =
Added code to work with lazy loading images (thanks vitalvital!);

= 1.8 =
Added fix for descriptions (thanks vitalvital!);
Changed encodeURI to encodeURIComponent to fix issues with certin urls

= 1.9 =
Added fixed for 0 size images (thanks freerange)

= 1.91 =
Added misc support fixes

= 1.92 =
Added link to Elembee's walkthrough
replaced non validating php on plugin_action_links_

= 1.93 =
fixed $defines not being set originally

== Upgrade Notice ==

= 1.0 =
* Birthed