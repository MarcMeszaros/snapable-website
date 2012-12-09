<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title><?php echo (isset($title)) ? 'Snapable - '.$title : "Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment."; ?></title>
    
    <meta name="Keywords" content="<?php echo (isset($keywords) && is_array($keywords)) ? implode(', ',$keywords): ''; ?>" /> 
	<meta name="Description" content="<?php echo (isset($description)) ? $description : ''; ?>" />
    
    <?php if(isset($meta) && is_array($meta)) {
        foreach ($meta as $key => $value) {
            echo '<meta property="'.$key.'" content="'.$value.'" />'.PHP_EOL;
        }
    } ?>

    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
	<link rel="SHORTCUT ICON" href="/favicon.ico"/> 
    
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans+Caption:400,700" type="text/css">
    <?php 
    if ( isset($css) ) {
    	// add assets
		if(defined('DEBUG') && DEBUG == true) {
			$decoded_assets = explode(',', base64_decode($css));
			foreach ($decoded_assets as $asset) {
	    		echo '<link rel="stylesheet" href="/' . $asset . '" type="text/css" media="screen" />'.PHP_EOL;
			}
		} else {
			echo '<link rel="stylesheet" href="/min/c/' . $css . '" type="text/css" media="screen" />';
		}
    } 
    ?>
    
    <?php if (isset($stripe) && $stripe == true) { ?>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <script type="text/javascript">
            Stripe.setPublishableKey('<?= STRIPE_KEY_PUBLIC ?>');
        </script>
    <?php } ?>
    <?php 
	    if ( isset($js) ) {
			echo '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>'.PHP_EOL;
            //echo '<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>'.PHP_EOL;
			
            // add assets
			if(defined('DEBUG') && DEBUG == true) {
				$decoded_assets = explode(',', base64_decode($js));
				foreach ($decoded_assets as $asset) {
		    		echo '<script type="text/javascript" src="/' . $asset . '"></script>'.PHP_EOL;
				}
			} else {
				echo '<script type="text/javascript" src="/min/j/' . $js . '"></script>';
			}
	    } 
    ?>
    
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

    <!--[if lt IE 9]>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
    <![endif]--> 
</head>

<body id="top">
