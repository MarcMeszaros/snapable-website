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
	
	var photoJSON = '{ "objects": [';
	
	if ( photos > 0 )
	{	
		// Display Loader
		$("#photoArea").css({"text-align":"center","font-weight":"bold"}).html("<div id='photoRetriever'>Retrieving Photos...<div class='bar'><span></span></div></div>");
		// Get photos for event
		var eid = eventID.split("/");
		$.getJSON('/event/get/photos/' + eid[3], function(json) {
			if ( json.status == 200 )
			{
				var count = 1;
				$("#photoRetriever").css({"display":"none"});
				$.Mustache.load('/assets/js/templates.html').done(function () 
				{
					$.each(json.response.objects, function(key, val) {
						
						if ( count <= 12 )
						{
							var resource_uri = val.resource_uri.split("/");
							var caption_icon = "comment.png";
							if ( !val.caption )
							{
								caption_icon = "blank.png";
							}
							
							var viewData = { 
								url: '/p/' + resource_uri[3],
								photo: '/p/get/' + resource_uri[3] + '/200x200',
								caption: val.caption,
								caption_icon: caption_icon,
								photographer: val.author_name 
							};
							$('#photoArea').mustache('event-list-photo', viewData);
						} else {	
							var caption = val.caption.replace(/"/g,"'");
							
							photoJSON += '{' +
				                '"author_name": "' + val.author_name + '",' +
				                '"caption": "' + caption + '",' +
				                '"event": "' + val.event + '",' +
				                '"guest": "' + val.guest + '",' +
				                '"metrics": "' + val.metrics + '",' +
				                '"resource_uri": "' + val.resource_uri + '",' +
				                '"streamable": ' + val.streamable + ',' +
				                '"timestamp": "' + val.timestamp + '",' +
				                '"type": "' + val.type + '"' +
				            '},';
						}
						count++;
					});

					if ( photoJSON.substr(-1, 1) == "," )
					{
						photoJSON = photoJSON.slice(0, -1);
					}
					photoJSON += '],' + '"count": ' + count + '}';
					
					if ( photos > 12 )
					{
						$("#photoArea").append("<div class='loadMoreWrap'><a class='loadMore' href='#" + count + "'>Load More</a></div>");
					}
					
					// Trigger photo overlay code
					$(".photo").hover(
					  function () {
					    $(".photo-overlay", this).fadeIn("fast");
					  },
					  function () {
					    $(".photo-overlay", this).fadeOut("fast");
					  }
					); 
					$('a.photo-enlarge').facebox();
				});
			} else {
				// hide loader and display error
				$("#photoArea").html("Something went wrong while fetching the photos for this event.");
			}
		});
	} else {
		$("#photoArea").addClass("noPhotos");
		
		$.Mustache.load('/assets/js/templates.html').done(function () 
		{
	        $('#photoArea').mustache('event-first-run');
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
			$("#uploadedArea").css({"display":"block"}).append("<div id='FILE"+result.filename+"' class='files'>Uploading Photo <div class='filesSmText'>This will just take a moment</div><div id='PRO"+result.filename+"' class='bar'><span></span></div></div>");	
		},

		loaded		: function(result){
			$("#PRO"+result.filename).remove();
			var resultText = "Upload complete.";
			
			if( result.status != 200 )
			{
				resulttext = "Your photo didn't completely upload.";
			} else {
				jQuery.facebox({ ajax: '/upload/crop/' + result.image + '/' + result.width + '/' +result.height });
			}			
		},

		progress	: function(result){
			$("#PRO"+result.filename).css("width", result.perc+"%");
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
	
	$(document).on("click", ".loadMore", function(e)
	{ 
		e.preventDefault();
		
		var photoObj = jQuery.parseJSON(photoJSON);
		photoJSON = '{ "objects": [';
		var count = 1;
		
		$(".loadMoreWrap").html("<span></span>").addClass("bar");
		
		$.Mustache.load('/assets/js/templates.html').done(function () 
		{
			$.each(photoObj.objects, function(key, val) {
				
				if ( count <= 12 )
				{
					var resource_uri = val.resource_uri.split("/");
					var caption_icon = "comment.png";
					if ( val.caption == "" )
					{
						caption_icon = "blank.png";
					}
					var viewData = { 
						url: '/p/' + resource_uri[3],
						photo: '/p/get/' + resource_uri[3] + '/200x200',
						caption: val.caption,
						caption_icon: caption_icon,
						photographer: val.author_name 
					};
					$('#photoArea').mustache('event-list-photo', viewData);
				} else {
					// LATHER, RINSE, REPEAT
					var caption = val.caption.replace(/"/g,"'");
					photoJSON += '{' +
		                '"author_name": "' + val.author_name + '",' +
		                '"caption": "' + caption + '",' +
		                '"event": "' + val.event + '",' +
		                '"guest": "' + val.guest + '",' +
		                '"metrics": "' + val.metrics + '",' +
		                '"resource_uri": "' + val.resource_uri + '",' +
		                '"streamable": ' + val.streamable + ',' +
		                '"timestamp": "' + val.timestamp + '",' +
		                '"type": "' + val.type + '"' +
		            '},';
				}
				count++;
			});
			$(".loadMoreWrap").remove();
			
			if ( photoJSON.substr(-1, 1) == "," )
			{
				photoJSON = photoJSON.slice(0, -1);
			}
			photoJSON += '],' + '"count": ' + count + '}';
			
			var countCheck = jQuery.parseJSON(photoJSON);
						
			if ( jQuery.isEmptyObject(countCheck.objects) == false )
			{
				$("#photoArea").append("<div class='loadMoreWrap'><a class='loadMore' href='#" + count + "'>Load More</a></div>");
			} else {
				
			}
			
			// Trigger photo overlay code
			$(".photo").hover(
			  function () {
			    $(".photo-overlay", this).fadeIn("fast");
			  },
			  function () {
			    $(".photo-overlay", this).fadeOut("fast");
			  }
			); 
			$('a.photo-enlarge').facebox();
		});
		return false;
	});
	
	$(".photo-comment").tipsy({fade: true, live: true});
	
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