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
	
	$(document).on("submit", "form#questionForm", function(e) 
	{
		var message = $("textarea[name=message]").val();
		if ( message == "" || message == "Enter a question, comment or message...")
		{
			alert("Forget to include your message?");
			e.preventDefault();
			return false;
		} else {
			alert(message);
			e.preventDefault();
			return false;
		}
	});
	
});