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
	
	// OPEN/CLOSE SECTION CONTENT
	$(document).on("click", ".left ul a", function() 
	{
		var clicked = $(this).attr("href").substr(1);
		var activeURL = $(".left .active").attr("href").substr(1);
		
		if ( !$(this).hasClass("active") )
		{
			$(".active").removeClass("active");
			$(this).addClass("active");
			$("#" + activeURL).fadeOut( "fast", function()
			{
				$("#" + clicked).fadeIn( "fast");
			})
		}
	});
	
	// OPEN/CLOSE SECTION STEPS CONTENT
	$(document).on("click", ".right a", function() 
	{
		var url = $(this).attr("href").substr(1);
		
		if ( $(this).hasClass("opened") )
		{
			$(this).removeClass("opened").addClass("closed");
			$("#" + url + "-content").slideUp("fast");
		} else {
			$(this).removeClass("closed").addClass("opened");
			$("#" + url + "-content").slideDown("fast");
		}
	});
	
	// HIGHLIGHT URL ON FOCUS
	$(document).on("click", "#event-url", function() 
	{
		$(this).select();
	});
	
	// SHARE VIA EMAIL
	
	$(document).on("click", "#shareLinks a.email", function() 
	{
		$("#shareViaEmail").slideToggle();
	});
	
	$(document).on("submit", "form#shareViaEmail", function(e) 
	{
		var message = $("textarea[name=messageBody]").val();
		if ( $("input[name=to]") == "")
		{
			alert("Looks like you forgot who you're sending this to.");
			$("input[name=to]").focus();
			e.preventDefault();
			return false;
		} 
		else if ( checkEmail($("input[name=to]").val()) == false )
		{
			alert("Hmm. This doesn't look like an email address to us.");
			$("input[name=to]").focus();
			e.preventDefault();
			return false;
		} else if ( message == "")
		{
			alert("Forget to include your message?");
			e.preventDefault();
			return false;
		} else {
			$.post("/account/email", {type:"share",message:message,to:$("input[name=to]").val(),from:$("input[name=from]").val()}, function(data){
				if ( data == "sent" )
				{
					$("form#shareViaEmail").prepend("<h3 id='shareMessageSent'>Your message to " + $("input[name=to]").val() + " has been sent</h3>");
					$("input[name=to]").val("");
					$("#shareMessageSent").delay(5000).fadeOut("fast");
				} else {
					alert("An error occurred while trying to send your message. Please email us direct at team@snapable.com");
				}
			})	
			e.preventDefault();
			return false;
		}
	});
	
	
	// EMAIL LINK
	$(document).on("click", "#email", function() 
	{
		var activeURL = $(".left .active").attr("href").substr(1);
		
		if ( $("#questions").hasClass("hiding") )
		{
			
			$(".active").removeClass("active");
			$("#questions-link").addClass("active");
			$("#" + activeURL).fadeOut( "fast", function()
			{
				$("#questions").fadeIn( "fast");
			});
		}
	});
	
	// QUESTIONS FORM
	
	$(document).on("focus", "textarea[name=message]", function(e) 
	{
		if ( $(this).val() == "Enter a question, comment or message..." )
		{
			$(this).val("").css({"color":"#333333"});
		}
	});
	$(document).on("blur", "textarea[name=message]", function(e) 
	{
		if ( $(this).val() == "" )
		{
			$(this).val("Enter a question, comment or message...").css({"color":"#999"});
		}
	});
	
	$(document).on("submit", "form#questionForm", function(e) 
	{
		var message = $("textarea[name=message]").val();
		if ( message == "" || message == "Enter a question, comment or message...")
		{
			alert("Forget to include your message?");
			e.preventDefault();
			return false;
		} else {
			$.post("/account/email", {type:"question",message:message,email:$("input[name=email]").val()}, function(data){
				if ( data == "sent" )
				{
					$("form#questionForm").html("<h3>Thanks! Your message has been sent</h3><p>We'll be in touch shortly.</p>");
				} else {
					alert("An error occurred while trying to send your message. Please email us direct at team@snapable.com");
				}
			})	
			e.preventDefault();
			return false;
		}
	});
	
});