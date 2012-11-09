
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
