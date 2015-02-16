$(document).ready(function() 
{ 
	$('form').submit(function(e) {
		var email = $("input[name=email]").val();
		var pass = $("input[name=password]").val();
		
		var emailReg = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
		
		if ( emailReg.test(email) == false )
		{
			//$("#email-error").fadeIn("fast");
			$("label[for=email]").css({ "color": "#cc3300" });
			$("label[for=email] div").fadeIn("fast");
			$("input[name=email]").addClass("inputError");
			e.preventDefault();
			return false;
		}
		else if ( pass == "")
		{
			$("input[name=email]").removeClass("inputError");
			$("label[for=email]").css({ "color": "#999" });
			$("label[for=email] div").fadeOut("fast");
			
			$("label[for=password]").css({ "color": "#cc3300" });
			$("label[for=password] div.error2").fadeOut("fast");
			$("label[for=password] div.error1").fadeIn("fast");
			$("input[name=password]").addClass("inputError");
			e.preventDefault();
			return false;
		}
		else if ( pass.length < 6 )
		{
			$("input[name=email]").removeClass("inputError");
			$("label[for=email]").css({ "color": "#999" });
			$("label[for=email] div").fadeOut("fast");
			
			$("label[for=password]").css({ "color": "#cc3300" });
			$("label[for=password] div.error1").fadeOut("fast");
			$("label[for=password] div.error2").fadeIn("fast");
			$("input[name=password]").addClass("inputError");
			e.preventDefault();
			return false;
		} else {
			$("label[for=email], label[for=password]").css({ "color": "#999" });
			$("input[name=email], input[name=password]").removeClass("inputError");
			$("label[for=email] div, label[for=password] div.error1, label[for=password] div.error2").fadeOut("fast");
			return true;
		}
	});
	
});