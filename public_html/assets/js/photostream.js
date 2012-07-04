$(document).ready(function() 
{  
	
	$(".photo").hover(
	  function () {
	    $(".photo-overlay", this).fadeIn("fast");
	  },
	  function () {
	    $(".photo-overlay", this).fadeOut("fast");
	  }
	); 
	
	$("#uploadBTN").click( function()
	{
		$("#uploadArea").slideToggle("fast");
	});
	
});