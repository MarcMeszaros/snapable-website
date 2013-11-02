function checkEmail(email) 
{
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email)) {
		return false;
	} else {
		return true;
	}
}

$(document).ready(function() 
{  
	
	$(".ajax").colorbox();
	
	$(document).on("submit", "form#signupWrap", function(e) 
	{
		e.preventDefault();
		
		ga('send', 'event', 'Signups', 'Clicked', 'Email Captured');
		_gaq.push(['_trackEvent', 'Signups', 'Clicked', 'Email Captured']);
		
		var package = $("input[name=package]").val();
		var email = $("input[name=email]").val();
		
		if ( checkEmail(email) == false )
		{
			alert("Sorry, that doesn't appear to be a proper email address.");
			$("input[name=email]").focus();
		} else {
			$.post("/assets/signup", {package:package,email:email}, function(data){
				if ( data == "sent" )
				{
					e.preventDefault();
					$("#theForm").fadeOut("fast", function()
					{
						$("#share").fadeIn("fast", function()
						{
							stButtons.locateElements();
						});
					});
				} else {
					alert("Darn, an error occurred while trying to sign you up. Please email us at team@snapable.com & we'll get it all straightened up. Thanks!");
				}
				return false;
			});
		}
		return false;
	});

});