$(document).ready(function() 
{  
	$(".photo-comment").tipsy({fade: true, live: true});
	
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
	
	$(document).on("click", ".addto-album", function(){ 
		alert("Show album menu")
	});
	
	$(document).on("click", ".addto-prints", function(){ 
		var count = parseFloat($("#in-cart-number").html()) + 1;
		$("#in-cart-number").html(count);
		// store reference of photos id somewhere
	});
	
});