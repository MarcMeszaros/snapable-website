<?php

/**
 * Core class for SQ_Blocksearch
 */
class SQ_Loading extends SQ_BlockController {

    function hookHead() {
       global $sq_postID;
       
       
       parent::hookHead();
       $exists = false;
       $browser = false;

       /* Check the squirrly.js file if exists */
       $exists = SQ_Tools::sq_remote_head(_SQ_STATIC_API_URL_.SQ_URI.'/js/squirrly.js');
       $browser = SQ_Tools::getBrowserInfo();

       if((isset($exists) && is_array($exists) && $exists['headers']['content-type'] <> 'application/javascript') || (isset($browser) && $browser != false && is_array($browser) && $browser['name'] == 'IE' && (int)$browser['version'] < 9 && (int)$browser['version'] > 0) ) {
            echo '<script type="text/javascript">
                    jQuery("#sq_preloading").removeClass("sq_loading");
                    jQuery("#sq_preloading").addClass("sq_error")
                    '.
                    (($browser['name'] == 'IE' && (int)$browser['version'] < 9 && (int)$browser['version'] > 0) 
                    ? 'jQuery("#sq_preloading").html("'.__('For Squirrly to work properly you have to use a higher version of Internet Explorer. <br /> We recommend you to use Chrome or Mozilla.', _PLUGIN_NAME_).'");
                       jQuery("#sq_options").hide(); '
                    : 'jQuery("#sq_preloading").html("'.__('Can not connect to Squirrly. Error: ' . $exists['response']['code'] . ' Message: ' . $exists['response']['message'], _PLUGIN_NAME_).'");')
                    .'
                  
                    jQuery("#sq_blocklogin").hide();
                  </script>';
        }else {
            echo '<script type="text/javascript">
                    var sq_uri = "'.SQ_URI.'"; var sq_language = "'.get_bloginfo('language').'"; var sq_version = "'.SQ_VERSION_ID.'"; var sq_wpversion = "'.WP_VERSION_ID.'"; var sq_phpversion = "'.PHP_VERSION_ID.'"; var __postID = "'.$sq_postID.'"; var __token = "'.SQ_Tools::$options['sq_api'].'";
                    var sq_keyword_information = "'.SQ_Tools::$options['sq_keyword_information'].'"; var __noopt = "'.__('You haven`t used Squirrly SEO to optimize your article. Do you want to optimize for a keyword before publishing?',_PLUGIN_NAME_).'";
                        
                  </script>';

            SQ_ObjController::getController('SQ_DisplayController', false)
                  ->loadMedia(_SQ_STATIC_API_URL_.SQ_URI.'/js/squirrly.js?ver='.SQ_VERSION_ID);
        }
       
    }
    
    
}

?>