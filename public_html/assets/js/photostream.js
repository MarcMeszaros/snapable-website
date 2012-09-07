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
	var csvFilename = "";
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
		
		$.Mustache.load('/assets/js/templates.html').done(function () 
		{
			$('#guest').mustache('invite-guests',"",{method: "html"});
			$("#guest").slideToggle();
			// get notification template and drop in place
			$.get('/event/guests/notify', function(data) 
			{
				$("#notify-message").html(data);
				// check if event has guests, hide 'send email' button if it doesn't
				
				$.get('/event/guests/count', { resource_uri:eventID }, function(count)
				{
					if ( count == 0 )
					{
						$("#do-notify-wrap").html("No guests have been invited yet.");
					}
				});
			});
		});
		//$("#guest").slideToggle();
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
		$.Mustache.load('/assets/js/templates.html').done(function () 
		{
			$("#guest-choices").hide().mustache("guest-upload", "", {method: "html"}).addClass("guests-options-wrap").fadeIn("normal");
		});
		return false;
	});

	$(document).on("click", "#guest-link-manual", function(){ 
		$("#guest-choices").hide().mustache("guest-manual", "", {method: "html"}).addClass("guests-options-wrap").fadeIn("normal");
		return false;
	});
	
	
	$(document).on("click", "#guests-file-how-to-csv-link", function(){ 
		$("#guests-file-how-to-csv").fadeIn("normal");
		return false;
	});
	
	$(document).on("click", ".guests-back-to-choices", function(){ 
		//$("#guests-manual, #guests-upload").hide();
		//$("#guests-choices").fadeIn();
		$("#guest-choices").hide().mustache("guest-options", "", {method: "html"}).removeClass("guests-options-wrap").fadeIn("normal");
		return false;
	});
	
	$(document).on("click", "#guests-upload-csv", function(e){
		e.preventDefault();
		
		var ext = $('#guests-csv-input').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['csv']) == -1) {
		    alert("Sorry, this doesn't appear to be a CSV file");
		} else {
			var iframe = $('<iframe name="postCSViframe" id="postCSViframe" style="display: none" />');

            $("body").append(iframe);

            var form = $('#guests-file-uploader');
            form.attr("action", "/upload/csv");
            form.attr("method", "post");
            form.attr("enctype", "multipart/form-data");
            form.attr("encoding", "multipart/form-data");
            form.attr("target", "postCSViframe");
            form.attr("file", $('#guests-csv-input').val());
            form.submit();

            $("#postCSViframe").load(function () {
                iframeContents = $("#postCSViframe")[0].contentWindow.document.body.innerHTML;
                var guestJSON = jQuery.parseJSON(iframeContents);
                
                if ( guestJSON.status == 200 )
                {
                	csvFilename = guestJSON.filename;
                	
                	$.Mustache.load('/assets/js/templates.html').done(function () 
                	{
                		var viewData = { 
							var: 'data', 
						};
	                	$("#guest-choices").removeClass("choiceBox").addClass("csvParse").mustache('guest-csv-parse', viewData, {method: "html"});
	                	
	                	var emailHeader = guestJSON.header.email.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	                	var nameHeader = guestJSON.header.name.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	                	var guestHeader = guestJSON.header.type.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	                	
	                	$("#csvHeader" + emailHeader).val("email");
	                	$("#csvHeader" + nameHeader).val("name");
	                	$("#csvHeader" + guestHeader).val("type");
	                	
	                	// populated each row with the correct data (use template: guest-csv-row)
	                	
	                	$.each(guestJSON.rows, function(key, val) 
	                	{
	                		var emailRow = emailHeader.toLowerCase();
	                		var nameRow = nameHeader.toLowerCase();
	                		var guestRow = guestHeader.toLowerCase();
	                		
	                		$.each(val, function(k, v) 
	                		{
		                		if ( k == emailRow )
		                		{
			                		var emailData = { 
				                		text: v 
				                	};
				                	$("#row" + emailHeader + "Contents").mustache('guest-csv-row', emailData);	
		                		}
		                		else if ( k == nameRow )
		                		{
			                		var nameData = { 
				                		text: v 
				                	};
				                	$("#row" + nameHeader + "Contents").mustache('guest-csv-row', nameData);	
		                		}
		                		else if ( k == guestRow )
		                		{
			                		var guestData = { 
				                		text: v 
				                	};
				                	$("#row" + guestHeader + "Contents").mustache('guest-csv-row', guestData);	
		                		}	
	                		})
	                	});
	                	
	                });
                } else {
	                alert("Sad Trombone, it seems we couldn't read the file you uploaded.");
                }
                
            });
			
		}
		/*
		$("#guests-upload").html("<strong>Your guests have been uploaded.</strong><br />Would you like to compose an email to let them know to download the Snapable app? <a id='notify-guests-yes' href='#'>Yes</a> / <a id='notify-guests-no' href='#'>No</a>");
		*/
		return false;
	});
	
	$(document).on("click", "#csvAllDone", function(e)
	{
		e.preventDefault();
		
		if ( csvFilename != "" )
		{
			$("#allDoneWrap").html("<img src='/assets/img/spinner_32px.gif' />");
			$.post("/parse/csv", { event:eventID, file:csvFilename, col1:$("#csvHeaderOne").val(), col2:$("#csvHeaderTwo").val(), col3:$("#csvHeaderThree").val() }, function(data)
			{
				var json = jQuery.parseJSON(data);
				
				if ( json.status == 200 )
				{
					// switch tab to notify and show content
					$("#addTab").removeClass("active");
					$("#notifyTab").addClass("active");
					$("#addBox").fadeOut("fast", function()
					{
						$.get('/event/guests/count', { resource_uri:eventID }, function(count)
						{
							if ( count == 0 )
							{
								$("#do-notify-wrap").html("No guests have been invited yet.");
							} else {
								$("#do-notify-wrap").html('<a href="#" id="do-notify-guests">Send Email(s)</a>');
							}
						});
						$("#notifyBox").fadeIn("fast");
					});
				} else {
					alert("We weren't able to complete the upload of your guest list at this time.");
					$("#allDoneWrap").html("<a id='csvAllDone' href='#'>All Done </a>");
				}
			});
		} else {
			alert("We weren't able to complete the upload of your guest list at this time.");
		}
	});
	
	
	$(document).on("focus", "#notify-custom-message", function(e)
	{
		if ( $(this).val() == "Enter a message for your guests." )
		{
			$(this).val("").css({"color":"#333333"});
		} 
	});
	$(document).on("blur", "#notify-custom-message", function(e)
	{
		if ( $(this).val() == "" )
		{
			$(this).val("Enter a message for your guests.").css({"color":"#999"});
		} 
	});
	$(document).on("click", "#do-notify-guests", function(e)
	{
		// get checkboxes checked and message
		var sendTo = new Array();
        $("input:checkbox[name=notify-type]:checked").each(function(){
            var val = $(this).val();
            sendTo.push(val);
        });
		var message = $("#notify-custom-message").val();
		
		if ( message == "" )
		{
			alert("You haven't supplied a message for your guests.")
		}
		else if ( sendTo.length === 0 )
		{
			alert("You haven't selected any of the guests to invite to use Snapable!");
		} else {
			$.post("/event/send/invites", { resource_uri:eventID, message:message, sendto:sendTo }, function(data)
			{
				alert("replace notify area with notification that invites were sent")
			});
		}
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