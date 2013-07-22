<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo (isset($title)) ? 'Snapable - '.$title : "Snapable - The easiest way to instantly capture every photo at your event without missing a single moment."; ?></title>
    
    <meta name="Keywords" content="<?php echo (isset($keywords) && is_array($keywords)) ? implode(', ',$keywords): ''; ?>" /> 
	<meta name="Description" content="<?php echo (isset($description)) ? $description : ''; ?>" />
    
    <?php if(isset($meta) && is_array($meta)) {
        foreach ($meta as $key => $value) {
            echo '<meta property="'.$key.'" content="'.$value.'" />'.PHP_EOL;
        }
    } ?>

    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" />
    <link href="https://plus.google.com/117772662503157448966" rel="publisher" />
    <link rel="SHORTCUT ICON" href="/favicon.ico"/>
    
    <link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans+Caption:400,700" />
    <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="/assets/libs/pnotify/jquery.pnotify.default.css" />
    <link type="text/css" rel="stylesheet" href="/assets/css/common/fonts.css" />
    <link type="text/css" rel="stylesheet" href="/assets/css/common/default.css" />
    <?php 
    // external resources
    if ( isset($ext_css) ) {
        // add assets
        foreach ($ext_css as $ext_asset) {
            echo '<link type="text/css" rel="stylesheet" href="' . $ext_asset . '" media="screen" />'.PHP_EOL;
        }
    } 
    // internal resources
    if ( isset($css) ) {
    	// add assets
		if(defined('MINIFY') && MINIFY == false) {
			foreach ($css as $asset) {
                echo '<link type="text/css" rel="stylesheet" href="/' . $asset . '?'. md5_file($asset) .'" media="screen" />'.PHP_EOL;
			}
		} else {
			echo '<link type="text/css" rel="stylesheet" href="/min/c/' . base64_encode(implode(',', $css)) . '" media="screen" />';
		}
    } 
    ?>
    
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/parsley.js/1.1.10/parsley.min.js"></script>
    <script type="text/javascript" src="/assets/libs/pnotify/jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="/assets/js/common/default.js"></script>
    <?php
        // external resources
        if ( isset($ext_js) ) {
            // add assets
            foreach ($ext_js as $ext_asset) {
                echo '<script type="text/javascript" src="' . $ext_asset . '"></script>'.PHP_EOL;
            }
        }
        // internal resources
	    if ( isset($js) ) {
            // add assets
			if(defined('MINIFY') && MINIFY == false) {
				foreach ($js as $asset) {
                    echo '<script type="text/javascript" src="/' . $asset . '?'. md5_file($asset) .'"></script>'.PHP_EOL;
				}
			} else {
				echo '<script type="text/javascript" src="/min/j/' . base64_encode(implode(',', $js)) . '"></script>';
			}
	    }
    ?>
    <?php if( isset($stripe) ) { ?>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <script type="text/javascript">
            Stripe.setPublishableKey('<?= STRIPE_KEY_PUBLIC ?>');
        </script>
    <?php } ?>

    <script type="text/javascript" src="/assets/libs/sharethis/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "e0bd9da5-9c45-4ad2-b1a3-81ca7d809ede", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>

    <?php if (isset($js_vars)) { ?>
    <script type="text/javascript">
        <?php
            foreach ($js_vars as $key => $value) {
                echo 'var '.$key.'='.str_replace('\/', '/', json_encode($value)).';'.PHP_EOL;
            }
        ?>
    </script>
    <? } ?>

    <!--[if lt IE 9]>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
    <![endif]-->

    <script type="text/javascript">
<?php if ( $_SERVER['HTTP_HOST'] == "snapable.com" || $_SERVER['HTTP_HOST'] == "www.snapable.com" ) { ?>  
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-295382-36']);
	  _gaq.push(['_setDomainName', 'snapable.com']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
 <?php } else { ?> 
 	var _gaq = _gaq || [];
 <?php } ?>
	</script>

</head>

<body id="top">
    <?php
    // super tiny link to internal admin stuff
    $logged_in = SnapAuth::is_logged_in();
    $userParts = explode('/', $logged_in['resource_uri']);
    if ($logged_in && isset($userParts[3]) && $userParts[3] < 1000) { ?>
        <div style="position: fixed; background-color: rgba(0,0,0,0.5); color: #fff; padding: 5px; top: 0; left: 0; z-index: 9998; border-radius: 0 0 5px 0; font-size:0.75em;">
            <a style="color:#fff;" href="/">Home</a> | <a style="color:#fff;" href="/internal/dashboard">Dashboard</a>
        </div>
    <?php } ?>
