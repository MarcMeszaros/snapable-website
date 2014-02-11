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
    <link rel="SHORTCUT ICON" href="/favicon.ico"/>
    
    <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/css/bootstrap-theme.min.css" />
    <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/2.0.1/css/bootstrap3/bootstrap-switch.min.css" />
    <link type="text/css" rel="stylesheet" href="/assets/libs/pnotify/jquery.pnotify.default.css" />
    <link type="text/css" rel="stylesheet" href="/assets/css/common/fonts.css" />
    <link type="text/css" rel="stylesheet" href="/assets/css/common/default.css?<?= md5_file('assets/css/common/default.css') ?>" />
    <link type="text/css" rel="stylesheet" href="/assets/css/common/snapable-theme.css?<?= md5_file('assets/css/common/snapable-theme.css') ?>" />
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
		foreach ($css as $asset) {
            echo '<link type="text/css" rel="stylesheet" href="/' . $asset . '?'. md5_file($asset) .'" media="screen" />'.PHP_EOL;
		}
    } 
    ?>
    
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.3.0/handlebars.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/2.0.1/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/parsley.js/1.2.2/parsley.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/spin.js/1.3.3/spin.min.js"></script>
    <script type="text/javascript" src="/assets/js/libs/jquery.spin.js"></script>
    <script type="text/javascript" src="/assets/libs/pnotify/jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="/assets/js/common/default.js?<?= md5_file('assets/js/common/default.js') ?>"></script>
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
			foreach ($js as $asset) {
                echo '<script type="text/javascript" src="/' . $asset . '?'. md5_file($asset) .'"></script>'.PHP_EOL;
			}
		}
    ?>
    <?php if(isset($stripe) && $stripe == true) { ?>
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <script type="text/javascript">
            Stripe.setPublishableKey('<?= STRIPE_KEY_PUBLIC ?>');
        </script>
    <?php } ?>

    <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "48c58afe-c312-46d6-aa45-ad95fc653c83", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>

    <!--[if lt IE 9]>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
    <![endif]-->

    <script type="text/javascript">
    <?php if ( $_SERVER['HTTP_HOST'] == "snapable.com" || $_SERVER['HTTP_HOST'] == "www.snapable.com" ) { ?> 
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-38299813-2', 'snapable.com');
        ga('require', 'linkid', 'linkid.js');
        ga('require', 'ecommerce', 'ecommerce.js');
        ga('send', 'pageview');

        // old analytics
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-295382-36']);
        _gaq.push(['_setDomainName', 'snapable.com']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    <?php } else { ?> 
        var _gaq = _gaq || [];
    <?php } ?>
	</script>

    <?php if (isset($facebook_pixel) && $facebook_pixel == true && ($_SERVER['HTTP_HOST'] == "snapable.com" || $_SERVER['HTTP_HOST'] == "www.snapable.com")) { ?>
    <script type="text/javascript">
    var fb_param = {};
    fb_param.pixel_id = '6008243038461';
    fb_param.value = '0.00';
    fb_param.currency = 'USD';

    (function(){
      var fpw = document.createElement('script');
      fpw.async = true;
      fpw.src = '//connect.facebook.net/en_US/fp.js';
      var ref = document.getElementsByTagName('script')[0];
      ref.parentNode.insertBefore(fpw, ref);
    })();
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6008243038461&amp;value=0&amp;currency=USD" /></noscript>
    <?php } ?>
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
