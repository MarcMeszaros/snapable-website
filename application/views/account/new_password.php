
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
	$(document).ready(function() 
	{
		$('form').submit(function(e) {
			var pass = $("input[name=password]").val();
			
			if ( pass == "")
			{
				$("label[for=password]").css({ "color": "#cc3300" });
				$("label[for=password] div.error2").fadeOut("fast");
				$("label[for=password] div.error1").fadeIn("fast");
				$("input[name=password]").addClass("inputError");
				e.preventDefault();
				return false;
			}
			else if ( pass.length < 6 )
			{
				$("label[for=password]").css({ "color": "#cc3300" });
				$("label[for=password] div.error1").fadeOut("fast");
				$("label[for=password] div.error2").fadeIn("fast");
				$("input[name=password]").addClass("inputError");
				e.preventDefault();
				return false;
			} else {
				$("label[for=password]").css({ "color": "#999" });
				$("input[name=password]").removeClass("inputError");
				$("label[for=password] div.error1, label[for=password] div.error2").fadeOut("fast");
				return true;
			}
			return false;
		});
	});     
    </script>
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

	<form id="signinWrap" name="signin" action="/account/password" method="post">
	
		<h1>Reset your password</h1>
		<h2>Enter a new password and click "Reset".</h2>
		
		<hr />
		
		<label for="password">
			Password
			<div class="error1">You need to provide a password to sign in</div>
			<div class="error2">Your password must be 6 or more characters</div>
		</label>
		<input type="password" name="password" />
		<input type="hidden" name="nonce" value="<?= $nonce ?>" />
		
		<hr />
		
		
		<input type="submit" name="submit" value="Reset" />
		
	</form>

</body>
</html>