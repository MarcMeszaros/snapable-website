$(document).ready(function() 
{ 
	$(document).on("click", ".continue", function()
	{ 
		// verify form if filled out
		var nextStep = $(this).attr("href").substr(1);
		window.location = "/checkout/" + nextStep;
	});
});