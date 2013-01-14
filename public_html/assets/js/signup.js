var validEmail = 0;
var validUrl = 0;

function checkEmail(email) 
{	
	var filter  = /^([a-zA-Z0-9_\.\_%+-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
	//var filter = /^([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6})+$/;
	if (!filter.test(email)) {
		return false;
	} else {
		return true;
	}
}

function checkUrl(url)
{
	$("#event_url_status").removeClass("url_good").removeClass("url_bad").addClass("spinner-16px")
	$.getJSON("/signup/check", { "url": url }, function(data)
	{		
		if ( data['status'] == 404 && url.length > 0)
		{
			$("#event_url_status").removeClass("url_bad").removeClass("spinner-16px").addClass("url_good");	
			$("#event_url").removeClass("input-error");
			$("#event_url_error").fadeOut();	
			validUrl = 1;
			return 1;	
		} else {
			$("#event_url_status").removeClass("url_good").removeClass("spinner-16px").addClass("url_bad");	
			$("#event_url").addClass("input-error");
			$("#event_url_error").fadeIn();
			validUrl = 0;
			return 0;
		}
	});
}

function geocoder(address)
{
	// do geocode to get addresses lat/lng
	// set #lat and #lng
	//$("#event_location_status").removeClass("location_good").removeClass("location_bad").addClass("spinner-16px")
	$.getJSON("http://where.yahooapis.com/geocode?location=" + encodeURIComponent(address) + "&flags=J&appid=qrVViDXV34GuS1yV7Mi2ya09wffvK6zlXaN1LFLQ3Q7fIXQI2MVhMtLMKQkDWMPP_g--", function(data)
	{
		if ( data['ResultSet']['Error'] == 0 )
		{
			var lat = data['ResultSet']['Results'][0]['latitude'];
			var lng = data['ResultSet']['Results'][0]['longitude'];
			$("#lat").val(lat);
			$("#lng").val(lng);
			// set spinner to checkmark
			//$("#event_location_status").removeClass("spinner-16px").addClass("location_good");
		} else {
			//$("#event_location_status").removeClass("spinner-16px").addClass("location_bad");
		}
		/*
		var mapOptions = {
			center: new google.maps.LatLng(lat, lng),
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			streetViewControl: false
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		var marker = new google.maps.Marker({
		    position: new google.maps.LatLng(lat, lng),
		    map: map,
		    draggable: true,
		    animation: google.maps.Animation.DROP
		});
		google.maps.event.addListener(marker, "dragend", function(event) {
       		var point = marker.getPosition();
       		$("#lat").val(point.lat());
			$("#lng").val(point.lng());
       		map.panTo(point);

			var timestamp = Math.round((new Date()).getTime() / 1000);
			var tzRequest = '/ajax/timezone?lat='+point.lat()+'&lng='+point.lng()+'&timestamp='+timestamp;
			$.getJSON(tzRequest, function(data){
				$('#timezone').val((data.rawOffset/60));
			});
       	});
        $('#map_canvas_container').slideDown();
        */
		var timestamp = Math.round((new Date()).getTime() / 1000);
		var tzRequest = '/ajax/timezone?lat='+lat+'&lng='+lng+'&timestamp='+timestamp;
		$.getJSON(tzRequest, function(data){
			$('#timezone').val((data.rawOffset/60));
		});
	});

	return true;
}

function userExists(email)
{
	$("#email_status").removeClass("email_good").removeClass("email_bad").addClass("spinner-16px");
	
	if ( checkEmail($("#user_email").val()) == false )
	{
		$("#user_email").focus();
		$("#user_email").addClass("input-error");
		$("#user_email_error").fadeIn();
	} else {
		$("#user_email").removeClass("input-error");
		$("#user_email_error").fadeOut();
		
		$.getJSON("/signup/check", { "email": email }, function(data)
		{
			if ( data['status'] == 404 )
			{
				$("#email_status").removeClass("spinner-16px").addClass("email_good");
				validEmail = 1;
				return 1;
			} else {
				$("#email_status").removeClass("spinner-16px").addClass("email_bad");
				validEmail = 0;
				return 0;
			}
		});
	}
}

$(document).ready(function() 
{  

    $( "#event-start-date, #event-end-date" ).datepicker({dateFormat: 'M d, yy'});//( "option", "dateFormat", "d M, y" );

	$("#event-start-time").timePicker({
		startTime: "06.00", // Using string. Can take string or Date object.
		show24Hours: false,
		separator: ':',
		step: 30
	});
	$("#event-end-time").timePicker({
		startTime: "07.00", // Using string. Can take string or Date object.
		show24Hours: false,
		separator: ':',
		step: 30
	});
	
	// check if user has already registered
	$("#user_email").blur( function()
	{
		var is_registered = userExists($(this).val());
		return is_registered;
	});
	
	// listener to check if url is available
	$('#event_url').keyup(function() {
		// replace spaces with dashes change uppercase to lowercase
		var new_title = $("#event_url").val().replace(/&/g,"and");
		new_title = new_title.replace(/ /g,"-");
		new_title = new_title.replace(/[^a-zA-Z0-9_-]/g,"");
		var title = new_title.toLowerCase();
		// check if already in the database
		$("#event_url").val(title);
		checkUrl($("#event_url").val());
	});
	
	// listener to geocode location
	$("#event_location").blur( function()
	{
		var geocoded = geocoder($("#event_location").val());
		return geocoded;
	});
	$('#event_location').keypress(function(e){
    	if(e.which == 13) {
    		var geocoded = geocoder($("#event_location").val());
			return geocoded;
    	}
	});
	
	// listener to set url from title
	$("#event_title").blur( function()
	{
		if ( $("#event_title").val() == "" )
		{
			$("#event_title").focus();
			$("#event_title").addClass("input-error");
			$("#event_title_error").fadeIn();
		}
		else if ( $("#event_url").val() == "" )
		{
			// replace spaces with dashes change uppercase to lowercase
			var new_title = $("#event_title").val().replace(/&/g,"and");
			new_title = new_title.replace(/ /g,"-");
			new_title = new_title.replace(/[^a-zA-Z0-9_-]/g,"");
			var title = new_title.toLowerCase();
			// check if already in the database
			var check = checkUrl(title);
			//if ( check == false )
			//{
				// write to url input
				$("#event_url").val(title);
			//}
		} else {
			$("#event_title").removeClass("input-error");
			$("#event_title_error").fadeOut();
		}
		
		return false;
	});
	
	// check fields on blur
	$("#user_first_name").blur( function()
	{
		if ( $("#user_first_name").val() == "" )
		{
			$("#user_first_name").focus();
			$("#user_first_name").addClass("input-error");
			$("#user_first_name_error").fadeIn();
		} else {
			$("#user_first_name").removeClass("input-error");
			$("#user_first_name_error").fadeOut();
		}
		
		return false;
	})
	$("#user_last_name").blur( function()
	{
		if ( $("#user_last_name").val() == "" )
		{
			$("#user_last_name").focus();
			$("#user_last_name").addClass("input-error");
			$("#user_last_name_error").fadeIn();
		} else {
			$("#user_last_name").removeClass("input-error");
			$("#user_last_name_error").fadeOut();
		}
		
		return false;
	})/*
	$("#user_email").blur( function()
	{
		if ( checkEmail($("#user_email").val()) == false )
		{
			$("#user_email").focus();
			$("#user_email").addClass("input-error");
			$("#user_email_error").fadeIn();
		} else {
			$("#user_email").removeClass("input-error");
			$("#user_email_error").fadeOut();
		}
		
		return false;
	})*/
	$("#user_password, #user_password_confirmation").blur( function()
	{
		if ( $("#user_password").val().length < 6 )
		{
			$("#user_password").focus();
			$("#user_password").addClass("input-error");
			$("#user_password_error").html("Your password is not long enough.").fadeIn();
		}
		else if ( $("#user_password").val() != $("#user_password_confirmation").val() )
		{
			$("#user_password").addClass("input-error");
			$("#user_password_error").html("Your passwords do not match.").fadeIn();
		} else {
			$("#user_password, #user_password_confirmation").removeClass("input-error");
			$(".field-error").css({ "display":"none" });
		}
		
		return false;
	})
	$("#event-title").blur( function()
	{
		if ( $("#event-title").val() == "" )
		{
			$("#event-title").focus().addClass("input-error");
			$("#event-title-error").fadeIn();
		} else {
			$("#event-title").removeClass("input-error");
			$("#event-title-error").fadeOut();
		}
		
		return false;
	});
	
	$("#event-duration-type").change( function()
	{
		var option = $(this).val();
		var values = "";
		var selected = "";
		
		if ( option == "days" )
		{
			for ( var i=1; i <= 7; i++ )
			{ 
				values += "<option value='" + i + "'" + selected + ">" + i + "</option>";
			}
		} else {
			for ( var i=1; i<= 23; i++ )
			{ 
				if ( i == 12 )
				{
					selected = " SELECTED";
				}
				values += "<option value='" + i + "'" + selected + ">" + i + "</option>";
			}
		}
		$("#event-duration-num").html(values);
		if ( option == "days" )
		{
			$("#event-duration-num").val(1);
		} else {
			$("#event-duration-num").val(12);
		}
	});
	
	
	$("#apply-promo-code").click( function()
	{
		if ( $("input[name=promo-code]").val() == "" )
		{
			alert("You haven't provided a promo code.");
		} else {
			$.getJSON("/signup/promo", { "code": $("input[name=promo-code]").val() }, function(json)
			{	
				var promoApplied = $("input[name=promo-code-applied]").val();
				
				if ( json.status == 200 && promoApplied == 0 )
				{
					var amount = parseFloat($("#package-amount").html());
					var discount = parseFloat(json.value);
					$("#package-amount").html(amount - discount);
					$("input[name=promo-code-applied]").val(1);
					$("input[name=promo-code-amount]").val(amount - discount);
				} 
				else if ( promoApplied == 1 )
				{
					alert("Sorry, you've already applied a promo code.");
				} else {
					alert("Sorry, that's not a valid promo code.");
				}
			});
		}
		return false;
	});
	
	// form verification and submission 
	$(".button").click( function()
	{
		var id = $(this).attr("id");
		
		$(".field-error").css({ "display":"none" });
		$("input").removeClass("input-error");
		$("#terms-refund h3").css({"color":"#444"});
		
		if ( id == "eventDeets" )
		{
			var title = $("#event_title").val(); // cannot be blank
			var location = $("#event_location").val(); // cannot be blank
			var lat = $("#lat").val(); // cannot be zero
			var lng = $("#lng").val(); // cannot be zero
			var url = $("#event_url").val();
			
			if ( title == "" )
			{
				$("#event_title").focus();
				$("#event_title").addClass("input-error");
				$("#event_title_error").fadeIn();
				location.href="#event-details";
			}
			else if ( validUrl == 0 )
			{
				$("#event_url_status").removeClass("url_good").removeClass("spinner-16px").addClass("url_bad");	
				$("#event_url").addClass("input-error");
				$("#event_url_error").fadeIn();	
			} else {
				$("#event").fadeOut("fast", function()
				{
					$("#navEvent").removeClass("active");
					$("#navYour").addClass("active");
					$("#your").fadeIn("fast");
				})
			}
		} 
		else if ( id == "yourDeets" )
		{
			var fname = $("#user_first_name").val(); // cannot be blank
			var lname = $("#user_last_name").val(); // cannot be blank
			var email = $("#user_email").val(); // cannot be blank and must be valid
			var password1 = $("#user_password").val(); // cannot be blank or less than 6 characters
			var password2 = $("#user_password_confirmation").val(); // must match password1
			
			if ( fname == "" )
			{
				$("#user_first_name").focus();
				$("#user_first_name").addClass("input-error");
				$("#user_first_name_error").fadeIn();
				location.href="#your-details";
			}
			else if ( lname == "" )
			{
				$("#user_last_name").focus();
				$("#user_last_name").addClass("input-error");
				$("#user_last_name_error").fadeIn();
				location.href="#your-details";
			}
			else if ( validEmail == 0 )
			{
				$("#user_email").focus();
				$("#user_email").addClass("input-error");
				$("#user_email_error").fadeIn();
				$("#email_status").removeClass("spinner-16px").removeClass("email_good").addClass("email_bad");
				location.href="#your-details";
			}
			else if ( password1 == "" )
			{
				$("#user_password").focus();
				$("#user_password").addClass("input-error");
				$("#user_password_error").html("You must provide a password.").fadeIn();
				location.href="#your-details";
			}
			else if ( password1 != password2 )
			{
				$("#user_password").focus();
				$("#user_password").addClass("input-error");
				$("#user_password_error").html("Your passwords do not match.").fadeIn();
				location.href="#your-details";
			}
			else if ( password1.length < 6 )
			{
				$("#user_password").focus();
				$("#user_password").addClass("input-error");
				$("#user_password_error").html("Your password is not long enough.").fadeIn();
				location.href="#your-details";
			} else {
				$("#your").fadeOut("fast", function()
				{
					$("#navYour").removeClass("active");
					$("#navBilling").addClass("active");
					$("#billing").fadeIn("fast");
				})
			}
		}
		return false;
	});
	
	$("#btn-sign-up").click( function()
	{
		document.forms["signupForm"].submit();
	});
	
	$("#payment-form").submit(function(event) {
		// disable the submit button to prevent repeated clicks
		$('#completSignup').attr("disabled", "disabled");
		
		// check form fields
		$("#creditcard_name").blur();
		$("#creditcard_number").blur();
		$("#creditcard_cvc").blur();
		//$("#creditcard_year").change(); // checking one of the two exp date fields checks both
		
		Stripe.createToken({
			name: $('#creditcard_name').val(),
		    number: $('#creditcard_number').val(),
		    cvc: $('#creditcard_cvc').val(),
		    exp_month: $('#creditcard_month').val(),
		    exp_year: $('#creditcard_year').val(),
		    address_zip: $('#address_zip').val()
		}, stripeResponseHandler);
		
		// prevent the form from submitting with the default action
		return false;
	});
	
	function stripeResponseHandler(status, response) {
	    if (response.error) {
	    	console.log(response);
	        // show the errors on the form
	        //$(".payment-errors").text(response.error.message);
	        $(".submit-button").removeAttr("disabled");
	    } else {
	        var form = $("#payment-form");
	        // token contains id, last4, and card type
	        var token = response['id'];
	        // insert the token into the form so it gets submitted to the server
	        form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
	        // and submit
	        form.get(0).submit();
	        $('#processing-order-msg').fadeIn();
	    }
	}

	$("#creditcard_name").blur( function()
	{
		if ( $("#creditcard_name").val() == "" )
		{
			$("#creditcard_name").focus();
			$("#creditcard_name").addClass("input-error");
			$("#creditcard_name_error").fadeIn();
		} else {
			$("#creditcard_name").removeClass("input-error");
			$("#creditcard_name_error").fadeOut();
		}
		
		return false;
	});
	$("#creditcard_number").blur( function()
	{
		if ( $("#creditcard_number").val() == "" || !Stripe.validateCardNumber($(this).val()) )
		{
			$("#creditcard_number").focus();
			$("#creditcard_number").addClass("input-error");
			$("#creditcard_number_error").fadeIn();
		} else {
			$("#creditcard_number").removeClass("input-error");
			$("#creditcard_number_error").fadeOut();
		}
		
		return false;
	});
	$("#creditcard_month, #creditcard_year").change( function()
	{
		if ( $(this).val() == "" || !Stripe.validateExpiry($('#creditcard_month').val(), $('#creditcard_year').val()) )
		{
			$("#creditcard_exp_error").fadeIn();
		} else {
			$("#creditcard_exp_error").fadeOut();
		}
		
		return false;
	}); 
	$("#creditcard_cvc").blur( function()
	{
		if ( !Stripe.validateCVC($(this).val()) )
		{
			$("#creditcard_cvc").focus();
			$("#creditcard_cvc").addClass("input-error");
			$("#creditcard_cvc_error").fadeIn();
		} else {
			$("#creditcard_cvc").removeClass("input-error");
			$("#creditcard_cvc_error").fadeOut();
		}
		
		return false;
	});
	
});