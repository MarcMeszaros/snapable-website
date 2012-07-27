$(document).ready(function() 
{  

	var uploader = new qq.FileUploader({
        element: document.getElementById('uploadArea'),
        action: '/upload',
        debug: true
    }); 
	
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
	
	// PRIVACY MENU
	$("#event-nav-privacy").click(function(e) {          
		e.preventDefault();
        $("#event-nav-menu-privacy").toggle();
		$("#event-nav-privacy").toggleClass("menu-open");
    });
	
	$("#event-nav-menu-privacy").mouseup(function() {
		return false
	});
	$(document).mouseup(function(e) {
		if($(e.target).parent("a#event-nav-privacy").length==0) {
			$("#event-nav-privacy").removeClass("menu-open");
			$("#event-nav-menu-privacy").hide();
		}
	});
	
	// SHARE MENU
	$("#event-nav-share").click(function(e) {          
		e.preventDefault();
        $("#event-nav-menu-share").toggle();
		$("#event-nav-share").toggleClass("menu-open");
    });
	
	$("#event-nav-menu-share").mouseup(function() {
		return false
	});
	$(document).mouseup(function(e) {
		if($(e.target).parent("a#event-nav-share").length==0) {
			$("#event-nav-share").removeClass("menu-open");
			$("#event-nav-menu-share").hide();
		}
	});
	
});