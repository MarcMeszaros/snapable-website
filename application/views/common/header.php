
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
    <link rel="stylesheet" href="/min/<?= $css ?>" type="text/css" media="screen" />
    <!--
    <link rel="stylesheet" href="/assets/css/timePicker.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/assets/css/timePicker.css" type="text/css" media="screen" />
		<link type="text/css" href="/assets/css/cupertino/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
    -->
    <script type="text/javascript" src="/assets/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.timePicker.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery-ui-1.8.21.custom.min.js"></script>
    <script type="text/javascript" src="/assets/js/buy.js"></script>
    
    <script type="text/javascript" src="/assets/js/photostream.js"></script>
    
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