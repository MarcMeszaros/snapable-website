<div class="container">
	<img id="signLogo" src="/assets/img/logo-indented.png" alt="Snapable" />
		<form id="signinWrap" class="col-lg-4 col-push-4 form" name="signin" action="/account/validate" method="post" data-validate="parsley" novalidate>
			<h1>Sign in to your account</h1>
			<h2>Don't have an account? <a href="/signup">Sign-up here</a></h2>
			
			<hr />
			
			<?php 
			if ( $error == true ) {
				echo "<div id='error'>Incorrect password or email address.</div>";	
			} 
			if ( $reset == true ) {
				echo "<div id='reset'>Your password was successfully reset.</div>";	
			} 
			?>
			
			<div class="form-group">
				<label for="email">Email Address</label>
				<input class="form-control text-center" type="email" data-type="email" name="email" required="required" data-required="true" />
			</div>

			<div class="form-group">
				<label for="password">Password</label>
				<input class="form-control text-center" type="password" name="password" required="required" data-required="true" data-minlength="6" data-minlength-message="Your password must be 6 or more characters." />
			</div>

			<hr />

			<input type="submit" name="submit" value="Sign in" />
			<a id="forgotPassword" href="/account/reset">Forgot your password?</a>
			
		</form>
</div>