
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
    <?php if ( isset($css) ) { ?>
    <link rel="stylesheet" href="/min/c/<?= $css ?>" type="text/css" media="screen" />
    <?php } ?>
    <?php if ( isset($js) ) { ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/min/j/<?= $js ?>"></script>
    <script type="text/javascript">
    var url = "<?= $url ?>"
    </script>
    <?php } ?>
    
</head>

<body id="top">

	<div id="toolbar">
		<a href="#" id="hide" class="arrowRight">Hide</a><a href="#" id="prev">prev</a><a href="#" id="pause" rel="pause" class="btnPause">pause</a><a href="#" id="next">next</a><a href="/event/<?= $url ?>" id="back">back to event</a>
	</div>
<!--
Slideshow baby, black background, get photo ids for event (only load 1 image at a time, once loaded display, also check for new photos before displaying/loading next image (if there's a new one start over and display that image first), toolbar with: hold time, hide tool bar, next/prev
-->

	<div id="spinner"><img src="/assets/img/spinner-grey_on_black.gif" alt="Loading..." /></div>
	
	<ul id="photos"></ul>
	
</body>
</html>