	<img id="signLogo" src="/assets/img/logo-indented.png" alt="Snapable" />

	<form id="signinWrap" name="signin" action="/account/validate" method="post">
	
		<h1>Sign in to your account</h1>
		<h2>Don't have an account? <a href="/#packages">Sign-up here</a></h2>
		
		<hr />
		
		<?php 
		if ( $error == true ) {
			echo "<div id='error'>Incorrect password or email address.</div>";	
		} 
		if ( $reset == true ) {
			echo "<div id='reset'>Your password was successfully reset.</div>";	
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
		
		
		<input type="submit" name="submit" value="Sign in" />
		<a id="forgotPassword" href="/account/reset">Forgot your password?</a>
		
	</form>