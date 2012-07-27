
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title>Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment.</title>
    
    <meta name="Keywords" content="" /> 
	<meta name="Description" content="" />
    
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
	<link rel="SHORTCUT ICON" href="/favicon.ico"/> 
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>
    <?php if ( isset($css) ) { ?>
    <link rel="stylesheet" href="/min/c/<?= $css ?>" type="text/css" media="screen" />
    <?php } ?>
    <?php if ( isset($js) ) { ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/min/j/<?= $js ?>"></script>
    <?php } ?>
    
</head>

<body id="top">

	<div id="homeHeadWrap">
		<div id="homeHead">
			
			<a id="headLogo" href="/">Snapable</a>
			
			<?php if ( !isset($noTagline) ) { ?>
			<div id="headTagline">Every moment. Captured.</div>
			<?php 
			}
			if ( isset($linkHome) ) { ?>
			<div id="headNav"><a href="/">‹ Back to Home</a></div>
			<?php } ?>
			
		</div>
	</div>
	
	<div id="restOfPageWrap">