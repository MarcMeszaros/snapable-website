function sendNotification(type, message)
{
	$("#notification").addClass(type).html(message).slideDown().delay(1500).slideUp();
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

$(document).ready(function() 
{  
	var csvFilename = "";
	var photoJSON = '{ "objects": [';
	var photoArr = new Array();
	//createCookie('phCart', '','90');
	
	
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
					// check if any photos are in the cart
					var photoCart = readCookie('phCart');
					var photoArr = new Array();
					if ( photoCart != null )
					{
						photoArr = photoCart.split(",");
						$("#in-cart-number").html(photoArr.length);
					}
					$.each(json.response.objects, function(key, val) {
						
						if ( count <= 12 )
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
								buttonText: buttonText 
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
						id: resource_uri[3], 
						url: '/p/' + resource_uri[3],
						photo: '/p/get/' + resource_uri[3] + '/200x200',
						caption: val.caption,
						caption_icon: caption_icon,
						photographer: val.author_name 
					};
					$('#photoArea').mustache('event-list-photo', viewData);
				} else {
					// LATHER, RINSE, REPEAT
					var resource_uri = val.resource_uri.split("/");
					var caption = val.caption.replace(/"/g,"'");
					photoJSON += '{' +
		                '"id": "' + resource_uri[3] + '",' +
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
		$(this).parent().parent().parent().addClass("photoInCart");
		$(this).removeClass("addto-prints").addClass("removefrom-prints").html("Remove from Prints");
		// store reference of photos id somewhere
		var photoCart = readCookie('phCart');
		var photoID = $(this).attr("href").substring(1);
		
		if ( photoCart != null )
		{
			var addID = photoCart + "," + photoID;
			createCookie('phCart', addID,'90');
		} else {
			createCookie('phCart', photoID,'90');	
		}
		//
		sendNotification("positive", "The photo was added to your cart.");
	});
	$(document).on("click", ".removefrom-prints", function()
	{ 
		var count = parseFloat($("#in-cart-number").html()) - 1;
		if ( count < 0 )
		{
			count = 0;
		}
		$("#in-cart-number").html(count);
		$(this).parent().parent().parent().removeClass("photoInCart");
		$(this).removeClass("removefrom-prints").addClass("addto-prints").html("Add to Prints");
		// remove reference from cart
		var photoCart = readCookie('phCart');
		var photoArr = photoCart.split(",");
		var photoID = $(this).attr("href").substring(1);
		var inPhotoArr = photoArr.indexOf(photoID);
		if ( inPhotoArr >= 0 )
		{
			// remove from array
			photoArr.splice(inPhotoArr,1);
			var newIDstring = photoArr.toString();
			createCookie('phCart', newIDstring,'90');
		}
		//
		sendNotification("caution", "The photo was removed from your cart.");
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
	
	// UPGRADES MENU
	$("#upgradeChoices").click(function(e) 
	{          
		e.preventDefault();
		$("#checkoutMenu").hide();
		
        $("#upgradeChoicesMenu").toggle();
		//$("#event-nav-privacy").toggleClass("menu-open");
    });
    
    $(document).on("click", ".addUpgrade", function()
	{
		var upgrade_id = $(this).attr("rel");
		
		var upgradeCookie = readCookie('upgrades');
		
		if ( upgradeCookie != null )
		{
			newString = upgradeCookie + "," + upgrade_id;
			createCookie('upgrades', newString,'90');
		} else {
			createCookie('upgrades', upgrade_id,'90');
		}
		$(this).closest(".addUpgradeWrap").html("Added.");
		
		setTimeout( function() { bringBackAddonButton(upgrade_id) }, 1500)
	});
	
	/**** SHOW CHECKOUT BUTTON ****/
	
	
	
	$('#checkout').click( function(e)
	{
		e.preventDefault();
		$("#upgradeChoicesMenu").hide();
		
		var photos_in_cart = parseFloat($("#in-cart-number").html());
		if ( photos_in_cart == 0 )
		{
			alert("You haven't added any photos yet.");
		} else {
			$("#checkoutMenu .menuContents ul").html(" ");
			
			var upgradeCookie = readCookie('upgrades');
			
			var subtotal = 0;
			var shipping = 3;
			var total = 3;
			
			var instructions = "";
			
			// if there's upgrades add them
			if ( upgradeCookie != null )
			{
				// check if there's more than one upgrade in teh cookie
				if (upgradeCookie.indexOf(",") >= 0)
				{
					// more than one upgrade exists
					var upgrades = upgradeCookie.split(",");
					
					var remains = 0;
					var extras = 0;
					var print_count = 0;
					var total_prints = 0;
					var shipping = "FREE";
					var instructions = "";
					var price = 0;
					var thisID = 0;
					
					// add upgrades
					$.Mustache.load('/assets/js/templates.html').done(function () 
					{
						$.each(upgrades, function(key, value) 
						{
							if ( value == 2 )
							{
								price = 11;
								print_count = 12;
							}
							else if ( value == 3 )
							{
								price = 19;
								print_count = 24;
							}
							else if ( value == 4 )
							{
								price = 27;
								print_count = 36;
							}
						
							var viewData = {
								id: thisID,
								num: 1, 
								print_count: print_count,
								instructions: "",
								price: price,
								type: 'upgrade'
							};
							$("#checkoutMenu .menuContents ul").mustache('checkout-review-upgrade', viewData);
							
							total_prints = total_prints + print_count;
							remains = total_prints - photos_in_cart;
							subtotal = subtotal + price;
							thisID++;
							
							$("#checkoutReviewSubTotalNum").html("$" + subtotal);
							// add shipping
							if ( shipping != "FREE" )
							{
								shipping = "$" + shipping;
							}
							$("#checkoutReviewShippingNum").html(shipping);
							// add total
							$("#checkoutReviewTotalNum").html("$" + subtotal);
						});
					// if there's less prints than the upgrades allow display message with # of prints left
					if  (remains > 0)
					{
						$("#checkoutReviewInstructions").html("You're selected upgrades allow for " + total_prints + " photos, you've  only chosen " + photos_in_cart + ".").show();
					} else {
						$("#checkoutReviewInstructions").hide();
					}
						
					});
					// if there's more prints than the upgrades add them as another line item
				} else {
				
					// just one upgrade here
					
					var remains = 0;
					var extras = 0;
					var print_count = 0;
					
					// get subtotal
					if ( upgradeCookie == 2 )
					{
						subtotal = 11;
						print_count = 12;
					}
					else if ( upgradeCookie == 3 )
					{
						subtotal = 19;
						print_count = 24;
					}
					else if ( upgradeCookie == 4 )
					{
						subtotal = 27;
						print_count = 36;
					}
					
					// if there's less prints than the upgrades allow display message with # of prints left
					if ( upgradeCookie == 2 && photos_in_cart < 12 )
					{
						remains = 12 - photos_in_cart;
						print_count = 12;
					}
					else if ( upgradeCookie == 3 && photos_in_cart < 24 )
					{
						remains = 24 - photos_in_cart;
						print_count = 24;
					}
					else if ( upgradeCookie == 4 && photos_in_cart < 36 )
					{
						remains = 36 - photos_in_cart;
						print_count = 36;
					}
					else if ( upgradeCookie == 2 && photos_in_cart > 12 )
					{
						extras = photos_in_cart - 12;
					}
					else if ( upgradeCookie == 3 && photos_in_cart > 24 )
					{
						extras = photos_in_cart - 24;
					}
					else if ( upgradeCookie == 4 && photos_in_cart > 36 )
					{
						extras = photos_in_cart - 36;
					}
					
					if  (remains > 0)
					{
						$("#checkoutReviewInstructions").html("You're selected upgrade allow for " + print_count + " photos, you've  only chosen " + photos_in_cart + ".");
					}
					
					// add upgrade
					$.Mustache.load('/assets/js/templates.html').done(function () 
					{
						var viewData = {
							id: 0,
							num: 1, 
							print_count: print_count,
							instructions: "",
							price: subtotal,
							type: 'upgrade'
						};
						$("#checkoutMenu .menuContents ul").mustache('checkout-review-upgrade', viewData);
						
						if (extras > 0)
						{
							var viewData = {
								num: extras, 
								type: 'singles'
							};
							$("#checkoutMenu .menuContents ul").mustache('checkout-review-singles', viewData);
						}
					});
					
					// add subtotal
					$("#checkoutReviewSubTotalNum").html("$" + subtotal);
					// add shipping
					if ( shipping != "FREE" )
					{
						shipping = "$" + shipping;
					}
					$("#checkoutReviewShippingNum").html(shipping);
					// add total
					$("#checkoutReviewTotalNum").html("$" + total);
				}
				
				shipping = "FREE";
				total = subtotal;
				$("#checkoutReviewShippingNum").addClass("freeShipping");
				
			} else { // if there's no upgrades display cost for individual prints
				//
				$.Mustache.load('/assets/js/templates.html').done(function () 
				{
					var viewData = {
						num: photos_in_cart, 
						type: 'singles'
					};
					$("#checkoutMenu .menuContents ul").mustache('checkout-review-singles', viewData);
				});
				subtotal = photos_in_cart;
				total = photos_in_cart + shipping;
				
				// add subtotal
				$("#checkoutReviewSubTotalNum").html("$" + subtotal);
				// add shipping
				if ( shipping != "FREE" )
				{
					shipping = "$" + shipping;
				}
				$("#checkoutReviewShippingNum").html(shipping);
				// add total
				$("#checkoutReviewTotalNum").html("$" + total);
			}
			$("#checkoutMenu").toggle();
		}
	});
	
	$("#checkoutMenu").on("click", ".checkoutRemove", function(e) 
	{
		var deets = $(this).attr("rel").split("|");
		var thisID = deets[0];
		var thisPrice = deets[1];
		
		// HIDE & REMOVE FROM CHECKOUT MENU
		
		$("#addon-" + thisID).fadeOut("fast");
		
		// update subtotal and total
		
		var subtotal = parseFloat($("#checkoutReviewSubTotalNum").html().replace("$", ""));
		var new_subtotal = subtotal - thisPrice;
		
		$("#checkoutReviewSubTotalNum").html("$" + new_subtotal);
		$("#checkoutReviewTotalNum").html("$" + new_subtotal);
		
		// REMOVE FROM UPGRADES COOKIE
		
		var upgradeCookie = readCookie('upgrades');
		var upgradeArr = upgradeCookie.split(",");
		upgradeArr.splice(thisID,1);
		createCookie('upgrades', upgradeArr,'90');
		
		if ( readCookie('upgrades') == "" )
		{
			createCookie('upgrades', "",'-90');
			$("#checkoutReviewSubTotalNum").html("$0");
			$("#checkoutReviewTotalNum").html("$0");
		}
	});
	
	$("#checkoutMenu").on("click", "#checkoutReviewContinue", function(e) 
	{
		var photosInCart = readCookie('phCart');
		var upgrades = readCookie('upgrades');
		
		if ( photosInCart.length > 0 && upgrades.length > 0 )
		{
			window.location = "/checkout/shipping";
		} else {
			alert("You don't seem to have added anything to your order.");
		}
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

	$(document).on("click", "#event-nav-menu-privacy input[type='button']", function(e)
	{
		var privacy_selected = $("input[name=privacy-setting]:checked").val();
		$("#privacySaveWrap").html("<img src='/assets/img/spinner_blue_sm.gif' />");
		$.post("/event/privacy", { event:eventID, selected:privacy_selected }, function(data)
		{
			var json = jQuery.parseJSON(data);
			if ( json.status = 202 )
			{
				sendNotification("positive", "Your privacy settings have been updated.");
				$("#privacySaveWrap").html("<input type='button' value='Save' />");
			} else {
				alert("This is embarassing, something went wrong on our end and we weren't able to change your privacy setting—never fear, we're on it!");
			}
		});
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
	
	$(document).on("click", ".tabs a", function()
	{
		var href = $(this).attr("href").substring(1);
		
		if ( $(this).parent().attr("class") != "active" )
		{
			$(".tabs li").removeClass("active");
			$(this).parent().addClass("active");
			
			if ( href == "guestlist" )
			{
				$(".tab-content").hide();
				$("#" + href + "Box").html("<div class='bar'><span></span></div>").show();
				
				// get guest list
				var eid = eventID.split("/");
				$.getJSON('/event/get/guests/' + eid[3], function(json) {
					if ( json.status == 200 )
					{
						$.Mustache.load('/assets/js/templates.html').done(function () 
						{
							$("#" + href + "Box").fadeOut("fast", function()
							{
								// format results and then fadeIn
								$("#" + href + "Box").html("<ul></ul>");
								$.each(json.guests, function(key, val) 
								{
									var viewData = { 
										name: val.name,
										email: val.email,
										type: val.type 
									};
									$("#" + href + "Box ul").mustache("guest-list", viewData);
								});
								$("#" + href + "Box").fadeIn("normal");
							});
						});
					} else {
						$("#" + href + "Box").html("You haven't added any guests to your event.");
					}
				});
			} else {
				$(".tab-content").hide();
				$("#" + href + "Box").show();
			}
		}
		return false;
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
		
		if ( message == "" || message == "Enter a message for your guests." )
		{
			alert("You haven't supplied a message for your guests.")
		}
		else if ( sendTo.length === 0 )
		{
			alert("You haven't selected any of the guests to invite to use Snapable!");
		} else {
			$.post("/event/send/invites", { resource_uri:eventID, message:message, sendto:sendTo }, function(data)
			{
				if ( data == "sent" )
				{
					sendNotification("positive","Your invitations were successfully sent.");
				} else {
					alert("Sad trombone. We weren't able to email your guests the invitations, contact us and we'll be happy to help.");
				}
			});
		}
	});
	
	
	$(document).on("click", "#notify-guests-yes", function()
	{
		$("#overlay-tabs-add").removeClass("active");
		$("#overlay-tabs-notify").addClass("active");
		$("#add-guests-wrap").fadeOut("fast", function()
		{
			$("#notify-guests").fadeIn("fast");
		});
		return false;
	});
	
	$(document).on("click", "#guests-manual-done", function()
	{
		// check if there's anything in the textbox
		if ( $("#guests-manual-textarea").val() == "" )
		{
			alert("It doesn't look like you've invited anyone to your event.");
			$("#guests-manual-textarea").focus();
		} else {
			$.post("/parse/text", { eventURI:eventID, message:$("#guests-manual-textarea").val() }, function(data)
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
				}
			});
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