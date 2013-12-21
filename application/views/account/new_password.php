<img id="signLogo" src="/assets/img/logo-indented.png" alt="Snapable" />

<div class="container">
	<div class="col-lg-4 col-lg-push-4 panel panel-default">
		<div class="panel-body">
		<form role="form" id="signinWrap" name="signin" action="/account/reset" method="post" data-validate="parsley" novalidate>
		
			<h1>Reset your password</h1>
			<h2>Enter a new password and click "Reset".</h2>

			<?php 
			if (isset($error)) {
				echo '<div id="error">'.$error.'</div>';	
			}
			?>

			<hr class="dotted" />

			<div class="form-group">
				<input type="hidden" name="nonce" value="<?= $nonce ?>" />

				<label for="password">Password</label>
				<input id="password" type="password" name="password" class="form-control" data-required="true" data-minlength="6" />
				<label for="password-confirm">Password Confirmation</label>
				<input id="password-confirm" type="password" name="password-confirm" class="form-control" data-required="true" data-equalto="#password" />
			</div>

			<hr class="dotted" />

			<div class="form-group">
				<button type="submit" name="submit" class="form-control btn btn-primary">Reset</button>
			</div>
		</form>
		</div>
	</div>
</div>