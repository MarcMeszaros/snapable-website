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