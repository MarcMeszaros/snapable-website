function sendNotification(type, message)
{
	$("#notification").addClass(type).html(message).slideDown().delay(1500).slideUp();
}

var slideCount = 1;
function firstRunSlideshow()
{
	$("#eventFirstRunText .displayMe").fadeOut("normal", function()
    {
    	if ( slideCount == 5 )
    	{
	    	$(this).removeClass("displayMe")
	    	$("#uploadText").fadeIn("normal").addClass("displayMe");
	    	$("a.blue").removeClass("blue");
	    	$("#uploadDot").addClass("blue"); 
	    	slideCount = 1;
    	} else {
	    	$(this).removeClass("displayMe").next("li").fadeIn("normal").addClass("displayMe");
	    	$("a.blue").removeClass("blue").next("a").addClass("blue"); 
	    	slideCount++;
	    } 
    })
}

$(document).ready(function() 
{  
	
	if ( photos > 0 )
	{
		alert("get photos for event (show loader while doing so)");
	} else {
		$("#photoArea").addClass("noPhotos");
		
		var viewData = { name: 'Andrew' };
		$.Mustache.load('/assets/js/templates.html').done(function () 
		{
	        $('#photoArea').mustache('event-first-run', viewData);
	        // start dot cycle
	        setInterval ( "firstRunSlideshow()", 5000 );
	        // set dot buttons
	        $(document).on("click", "#eventFirstRunDots a", function()
	        {  
	        	var clicked = $(this).attr("href").substring(1);
	        	$("#eventFirstRunDots a").removeClass("blue");
	        	$(this).addClass("blue");
	        	$("#eventFirstRunText .displayMe").fadeOut("normal", function()
	        	{
		        	$(this).removeClass("displayMe");
		        	$("#" + clicked + "Text").fadeIn("normal").addClass("displayMe");
	        	})
	        });
		});
	}
	
	/*** PHOTO UPLOADER ****/
	var errors="";
	
	$('#uploadArea').mfupload({
		
		type		: '',	//all types
		maxsize		: 2,
		post_upload	: "/upload",
		folder		: "./here",
		ini_text	: "<div class='uploadText'>Drag files (or click) into this area to upload</div>",
		over_text	: "<div class='uploadText'>Drop file here</div>",
		over_col	: 'white',
		over_bkcol	: '#006699',
        
		init		: function(){		
			$("#uploadedArea").empty();
		},
		
		start		: function(result){		
			$("#uploadedArea").append("<div id='FILE"+result.fileno+"' class='files'>Uploading Photo <div class='filesSmText'>This will just take a moment</div><div id='PRO"+result.fileno+"' class='bar'><span></span></div></div>");	
		},

		loaded		: function(result){
			$("#PRO"+result.fileno).remove();
			var resultText = "Upload complete.";
			if( result.status != 200 )
			{
				resulttext = "Your photo didn't completely upload.";
			}
			$("#FILE"+result.fileno).html(resultText).delay("1500").fadeOut("normal", function()
			{
				$("#photoArea").prepend("<div class='photo photoHidden'>" +
					"<div class='photo-overlay'>" +
						"<ul class='photo-share'>" +
						"<li><a class='photo-share-twitter' href='#'>Tweet</a></li>" +
						"<li><a class='photo-share-facebook' href='#'>Share</a></li>" +
						"<li><a class='photo-share-email' href='#'>Email</a></li>" +
					"</ul>" +
					"<div class='photo-buttons'>" +
						"<a class='button addto-prints' href='#'>Add to Prints</a>" +
					"</div>" +
					"<a class='photo-enlarge' href='/p/123' title='Enlarge'>Enlarge</a>" +
				"</div>" +
				"<img src='/assets/img/FPO/event-photo-1.jpg' />" +
				"<img class='photo-comment' title='Uncle Bob dancing up a storm on the dance floor.' src='/assets/img/icons/comment.png' /> Andrew D." +	
				"</div>");
				$("#photoArea .photo:first-child").fadeIn("fast");
			});			
		},

		progress	: function(result){
			$("#PRO"+result.fileno).css("width", result.perc+"%");
		},

		error		: function(error){
			errors += error.filename+": "+error.err_des+"\n";
		},

		completed	: function(){
			if (errors != "") {
				alert(errors);
				errors = "";
			}
		}
	});
	
	/**** OTHER ****/
	
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
	
	$(document).on("click", ".addto-album", function()
	{ 
		alert("Show album menu")
	});
	
	$(document).on("click", ".addto-prints", function()
	{ 
		var count = parseFloat($("#in-cart-number").html()) + 1;
		$("#in-cart-number").html(count);
		// store reference of photos id somewhere
		sendNotification("positive", "The photo was added to your cart.");
	});
	
	// SLIDESHOW MENU
	$("#slideshowBTN").click( function(e)
	{
		e.preventDefault();
		$("#slideshow").slideToggle();
	});
	
	// GUEST MENU
	$("#guestBTN").click( function(e)
	{
		e.preventDefault();
		$("#guest").slideToggle();
	});
	
	// PRIVACY MENU
	$("#event-nav-privacy").click(function(e) 
	{          
		e.preventDefault();
        $("#event-nav-menu-privacy").toggle();
		$("#event-nav-privacy").toggleClass("menu-open");
    });
	
	$("#event-nav-menu-privacy").mouseup(function() 
	{
		return false
	});
	$(document).mouseup(function(e) {
		if($(e.target).parent("a#event-nav-privacy").length==0) {
			$("#event-nav-privacy").removeClass("menu-open");
			$("#event-nav-menu-privacy").hide();
		}
	});
	
	// SHARE MENU
	$("#event-nav-share").click(function(e) 
	{          
		e.preventDefault();
        $("#event-nav-menu-share").toggle();
		$("#event-nav-share").toggleClass("menu-open");
    });
	
	$("#event-nav-menu-share").mouseup(function() 
	{
		return false
	});
	$(document).mouseup(function(e) 
	{
		if($(e.target).parent("a#event-nav-share").length==0) {
			$("#event-nav-share").removeClass("menu-open");
			$("#event-nav-menu-share").hide();
		}
	});
	
	// INVITE GUESTS
	
	$(document).on("click", ".tabs a", function(){
		var href = $(this).attr("href").substring(1);
		
		if ( $(this).parent().attr("class") != "active" )
		{
			$(".tabs li").removeClass("active");
			$(this).parent().addClass("active");
			$(".tab-content").hide();
			$("#" + href + "Box").show();
		}
	});
	
	//$("#guest-link-upload").on("click", function(event) {
	$(document).on("click", "#guest-link-upload", function(){ 
		$("#guests-choices").hide();
		$("#guests-upload").fadeIn("normal");
		return false;
	});

	$(document).on("click", "#guest-link-manual", function(){ 
		$("#guests-choices").hide();
		$("#guests-manual").fadeIn("normal");
		return false;
	});
	
	$(document).on("click", "#guests-file-how-to-csv-link", function(){ 
		$("#guests-file-how-to-csv").fadeIn("normal");
		return false;
	});
	
	$(document).on("click", ".guests-back-to-choices", function(){ 
		$("#guests-manual, #guests-upload").hide();
		$("#guests-choices").fadeIn();
		return false;
	});
	
	$(document).on("click", "#guests-upload-csv", function(){
		$("#guests-upload").html("<strong>Your guests have been uploaded.</strong><br />Would you like to compose an email to let them know to download the Snapable app? <a id='notify-guests-yes' href='#'>Yes</a> / <a id='notify-guests-no' href='#'>No</a>");
		return false;
	});
	
	$(document).on("click", "#notify-guests-yes", function(){
		$("#overlay-tabs-add").removeClass("active");
		$("#overlay-tabs-notify").addClass("active");
		$("#add-guests-wrap").fadeOut("fast", function()
		{
			$("#notify-guests").fadeIn("fast");
		});
		return false;
	});
	
	
	/// GUEST LOGIN
	
	
	$('form').submit(function(e) 
	{
		var email = $("input[name=email]").val();
		var pin = $("input[name=pin]").val();
		
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
		else if ( pin == "")
		{
			$("input[name=email]").removeClass("inputError");
			$("label[for=email]").css({ "color": "#999" });
			$("label[for=email] div").fadeOut("fast");
			
			$("label[for=pin]").css({ "color": "#cc3300" });
			$("label[for=pin] div").fadeOut("fast");
			$("input[name=pin]").addClass("inputError");
			e.preventDefault();
			return false;
		} else {
			$("label[for=email], label[for=pin]").css({ "color": "#999" });
			$("input[name=email], input[name=pin]").removeClass("inputError");
			$("label[for=email] div, label[for=pin] div").fadeOut("fast");
			return true;
		}
	});
	
});