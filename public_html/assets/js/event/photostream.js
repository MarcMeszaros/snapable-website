// some global variables (required to make the on DOM load stuff work)
var photoArr = new Array();
var photoAPI;
var photoAPIOffset = 0;

function sendNotification(type, message, duration)
{
	if (duration === undefined) {
		duration = 1500;
	}
	$("#notification").addClass(type).html(message).slideDown().delay(duration).slideUp();
}

var slideCount = 1;
function firstRunSlideshow()
{
	$("#eventFirstRunText .displayMe").fadeOut("normal", function()
    {
    	if ( slideCount >= 2 )
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


function bringBackAddonButton(id)
{
	$("#upgrade-" + id).html("<a class='addUpgrade' href='#' rel='" + id + "'>Add</a>");
}


function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function sanitizeUrl(url)
{
	// replace spaces with dashes change uppercase to lowercase
	var new_url = url.replace(/&/g,"and");
	new_url = new_url.replace(/ /g,"-");
	new_url = new_url.replace(/[^a-zA-Z0-9_-]/g,"");
	new_url = new_url.toLowerCase();
	return new_url;
}

function checkUrl(url)
{
	$("#event-settings-url-status").removeClass("good").removeClass("bad").addClass("spinner-16px");
	$.getJSON("/signup/check", { "url": url }, function(data) {		
		if ( data['status'] == 404 && url.length > 0) {
			$("#event-settings-url-status").removeClass("bad").removeClass("spinner-16px").addClass("good");	
		} else {
			$("#event-settings-url-status").removeClass("good").removeClass("spinner-16px").addClass("bad");	
		}
	});
}

// when the DOM is ready
$(document).ready(function() 
{  
	var eid = eventID.split("/");
	var csvFilename = "";
	//createCookie('phCart', '','90');

	if ( photo_count > 0 )
	{
		$('#event-cover-image').attr('src', '/p/get_event/'+eid[3]+'/60x60'); // load the cover image

		// Display Loader
		$("#photoArea").css({"text-align":"center","font-weight":"bold"}).html("<div id='photoRetriever'>Retrieving Photos...<div class='bar'><span></span></div></div>");
		// Get photos for event
		$.getJSON('/event/get/photos/' + eid[3], function(json) {
			if ( json.status == 200 )
			{
				$("#photoRetriever").css({"display":"none"});
				$.Mustache.load('/assets/js/templates.html').done(function () 
				{
					// check if any photos are in the cart
					var photoCart = readCookie('phCart');
					var photoArr = new Array();
					if ( photoCart != null )
					{
						photoArr = photoCart.split(",");
						$("#in-cart-number").html(photoArr.length);
					}
					
					// add initial photos
					photoAPI = json;
					loadPhotos(photoAPI);

					// hook up the 'Load More' button
					$(document).on("click", ".loadMore", function(e)
					{ 
						e.preventDefault();
						$.Mustache.load('/assets/js/templates.html').done(function () 
						{
							loadPhotos(photoAPI);
						});
						return false;
					});
				
					// LOAD UPGRADE MENU
					var upgradesJSON = '{"upgrades": [{ "id": 1, "titleDrk": "Single", "titleLgt": "Prints", "desc": "Pay-as-you-go", "type": "Prints", "qty": 1, "addBTN": 0, "price": 1, "shipping": 3},{ "id": 2, "titleDrk": "12", "titleLgt": "Prints", "desc": "", "type": "Prints", "qty": 12, "addBTN": 1, "price": 11, "shipping": 0},{ "id": 3, "titleDrk": "24", "titleLgt": "Prints", "desc": "", "type": "Prints", "qty": 24, "addBTN": 1, "price": 19, "shipping": 0},{ "id": 4, "titleDrk": "36", "titleLgt": "Prints", "desc": "", "type": "Prints", "qty": 36, "addBTN": 1, "price": 27, "shipping": 0}]}';
					var upgradeObj = jQuery.parseJSON(upgradesJSON);
					
					$.each(upgradeObj.upgrades, function(key, val) {
						
						if ( val.shipping == 0 )
						{
							uShipping = '<span>Free Shipping</span>';
						} else {
							uShipping = "$" + val.shipping + " Shipping";
						}
						
						var addBTNhtml = val.desc;
						if ( val.addBTN > 0 )
						{
							addBTNhtml = "<div class='addUpgradeWrap' id='upgrade-" + val.id + "'><a class='addUpgrade' href='#' rel='" + val.id + "'>Add</a></div>";
						}
					
						var viewData = {
							id: val.id, 
							titleDrk: val.titleDrk,
							titleLgt: val.titleLgt,
							desc: addBTNhtml,
							price: val.price,
							shipping: uShipping
						};
						$('#upgradeChoicesMenu .menuContents').mustache('upgrade-list', viewData);
					});
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
	        	if ( $(this).attr("id") == "uploadDot" )
	        	{
		        	slideCount = 1;
	        	} else {
		        	slideCount = 2;
	        	}
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
	$(".photo-comment").tipsy({fade: true, live: true, offset: -80});
	
	$(document).on("click", ".addto-album", function()
	{ 
		alert("Show album menu")
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

/*
Function used to load photos into the photo stream and hook up all the
buttons and events to each photo.
*/
function loadPhotos(photos) {
	// setup some variables
	var count = 0;

	//$.each(photos.response.objects, function(key, val) {
	offset = 0;
	if (photos.response.objects.hasOwnProperty('offset')) {
		offset = photos.response.objects.offset;
		$(".loadMoreWrap").html("<span></span>").addClass("bar");
	} else {
		photos.response.objects.offset = offset;
	}
	for (var key = offset; key < photos.response.objects.length ; key++) {
		var val = photos.response.objects[key];

		if ( count < 12 )
		{
			var resource_uri = val.resource_uri.split("/");
			var caption_icon = "comment.png";
			if ( !val.caption )
			{
				caption_icon = "blank.png";
			}
			
			var inPhotoArr = photoArr.indexOf(resource_uri[3]);
			var photoClass = "";
			var buttonClass = "addto-prints";
			var buttonText = "Add to Prints";
			if ( inPhotoArr >= 0 )
			{
				photoClass = " photoInCart";
				buttonClass = "removefrom-prints";
				buttonText = "Remove from Prints";
			}
			
			var viewData = {
				id: resource_uri[3], 
				url: '/p/' + resource_uri[3],
				photo: '/p/get/' + resource_uri[3] + '/200x200',
				caption: val.caption,
				caption_icon: caption_icon,
				photographer: val.author_name,
				photoClass: photoClass,
				buttonClass: buttonClass,
				buttonText: buttonText ,
				owner: owner
			};
			$('#photoArea').mustache('event-list-photo', viewData);
			delete photos.response.objects[key];
			count++;
		}
	}
	photos.response.objects.offset += count; // used to know where to resume looping
	$(".loadMoreWrap").remove();
	
	if ( photos.response.objects.offset < photos.response.objects.length-1 )
	{
		$("#photoArea").append("<div class='loadMoreWrap'><a class='loadMore' href='#'>Load More</a></div>");
	} else if (photo_count > 50 && (photoAPIOffset + 1) < photo_count) {
		console.log('we should load more from the api');
		photoAPIOffset += 50;

		var eid = eventID.split("/");
		$.getJSON('/event/get/photos/' + eid[3] + '/' + photoAPIOffset, function(json) {
			if ( json.status == 200 ) {
				photoAPI = json;
				$("#photoArea").append("<div class='loadMoreWrap'><a class='loadMore' href='#'>Load More</a></div>");
			}
		});
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

	// setup the delete
	$('#photo-action a.photo-delete').click(function(){
		var deleteButton = $(this); // save a reference to that button
		var photoDeleting = setTimeout(function(){
			$.getJSON('/p/delete_photo/'+$(deleteButton).attr('data-photo_id'), function(json) {
				console.log('photo deletion response code: '+json.status);
				if (json.status == 200 || json.status == 204) {
					// remove it from the ui
					//$(this).parents('div.photo').remove();
				}
			});
			$(deleteButton).closest('div.photo').remove();
		}, 4000);
		sendNotification('caution', 'Photo will be deleted. <a class="undo" href="#">Undo</a>', 3000);
		$('#notification a.undo').click(function(){
			clearTimeout(photoDeleting);
			$('#notification').html('Photo deletion cancelled.').stop(true, true).slideDown();
			setTimeout(function(){
				$('#notification').slideUp();
			},3000);
			return false;
		});

		return false;
	});

	// setup the download
	$('#photo-action a.photo-download').click(function(){
		document.location = '/download/photo/'+$(this).attr('data-photo_id');
		return false; // end execution of the javascript
	});
}