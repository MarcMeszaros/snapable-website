<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title><?php if ( isset($title) ) { echo $title; } else { echo "Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment."; } ?></title>
    
    <meta name="Keywords" content="" /> 
	<meta name="Description" content="" />
    
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
	<link rel="SHORTCUT ICON" href="/favicon.ico"/> 
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>
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
    
    <?php 
	    if ( isset($js) ) {
			echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>';
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
    
</head>

<body id="top">
