<img id="signLogo" src="/assets/img/logo-indented.png" alt="Snapable" />

<div class="container">
	<div class="col-lg-4 col-lg-push-4 panel panel-default">
		<div class="panel-body">
		<form role="form" id="signinWrap" name="signin" action="/account/doreset" method="post" data-validate="parsley" novalidate>
		
			<h1>Reset your password</h1>
			<h2>Enter your email address, click the Reset button and we'll email you a link to reset your password.</h2>
			
			<?php
				if (!empty($error)) {
					echo '<div id="error">'.$error.'</div>';
				}
			?>

			<hr class="dotted" />

			<div class="form-group">
				<label for="email">Email Address</label>
				<input type="email" name="email" class="form-control" data-type="email" data-required="true" />
			</div>

			<hr class="dotted" />

			<div class="form-group">
				<button type="submit" name="submit" class="form-control btn btn-primary">Reset</button>
			</div>
		</form>
		</div>
	</div>
</div>
