
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title>Sign in to Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment.</title>
    
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
    <script type="text/javascript">
	$(document).ready(function() 
	{
		$('form').submit(function(e) 
		{
			var email = $("input[name=email]").val();
			var emailReg = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
		
			if ( emailReg.test(email) == false )
			{
				//$("#email-error").fadeIn("fast");
				$("label[for=email]").css({ "color": "#cc3300" });
				$("label[for=email] div").fadeIn("fast");
				$("input[name=email]").addClass("inputError");
				e.preventDefault();
				return false;
			} else {
				$("label[for=email]").css({ "color": "#999" });
				$("input[name=email]").removeClass("inputError");
				$("label[for=email] div").fadeOut("fast");
				return true;
			}
		});
	});     
    </script>
    <?php } ?>
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

	<img id="signLogo" src="/assets/img/logo-indented.png" alt="Snapable" />

	<form id="signinWrap" name="signin" action="/account/doreset" method="post">
	
		<h1>Reset your password</h1>
		<h2>Enter your email address, click the Reset button and we'll email you a link to reset your password.</h2>
		
		<?= $error ?>
		
		<hr />
		
		<label for="email">
			Email Address
			<div>This doesn't look like a proper email address</div>
		</label>
		<input type="text" name="email" />
		
		<hr />
		
		
		<input type="submit" name="submit" value="Reset" />
		
	</form>

</body>
</html>