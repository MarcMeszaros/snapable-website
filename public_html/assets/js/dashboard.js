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
			$.post("/account/email", {message:message,email:$("input[name=email]").val()}, function(data){
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