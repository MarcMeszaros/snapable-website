
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

	<img id="logo" src="/assets/img/logo-indented.png" alt="Snapable" />

	<form id="signinWrap" name="signin" action="/account/validate" method="post">
	
		<h1>Sign in to your account</h1>
		<h2>Don't have an account? <a href="/#packages">Sign-up here</a></h2>
		
		<hr />
		
		<?php 
		if ( $error == true ) {
			echo "<div id='error'>Incorrect password or email address.</div>";	
		} 
		?>
		
		<label for="email">
			Email Address
			<div>This doesn't look like a proper email address</div>
		</label>
		<input type="text" name="email" />
		
		<label for="password">
			Password
			<div class="error1">You need to provide a password to sign in</div>
			<div class="error2">Your password must be 6 or more characters</div>
		</label>
		<input type="password" name="password" />
		
		<hr />
		
		
		<a id="forgotPassword" href="/account/forgot">Forgot your password?</a>
		<input type="submit" name="submit" value="Sign in" />
		
	</form>

</body>
</html>