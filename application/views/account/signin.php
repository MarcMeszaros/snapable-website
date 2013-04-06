	<img id="signLogo" src="/assets/img/logo-indented.png" alt="Snapable" />

	<form id="signinWrap" name="signin" action="/account/validate" method="post" data-validate="parsley" novalidate>
	
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
		
		<label for="email">Email Address</label>
		<input class="text-center" type="email" data-type="email" name="email" required="required" data-required="true" />
		
		<label for="password">Password</label>
		<input class="text-center" type="password" name="password" required="required" data-required="true" data-minlength="6" data-minlength-message="Your password must be 6 or more characters." />
		
		<hr />
		
		
		<input type="submit" name="submit" value="Sign in" />
		<a id="forgotPassword" href="/account/reset">Forgot your password?</a>
		
	</form>