function checkEmail(email) 
{
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email)) {
		return false;
	} else {
		return true;
	}
}

function checkUrl(url)
{
	// ajax call to database to find out if url exists already or not
	var status = false;
		
	if ( status == false)
	{
		$("#event_url_status").removeClass("url_bad").addClass("url_good");	
		$("#event_url").removeClass("input-error");
		$("#event_url_error").fadeOut();		
	} else {
		$("#event_url_status").removeClass("url_good").addClass("url_bad");	
		$("#event_url").addClass("input-error");
		$("#event_url_error").fadeIn();
	}
	return false;
}

function geocoder(address)
{
	// do geocode to get addresses lat/lng
	// set #lat and #lng
	$("#event_location_status").addClass("spinner-16px")
	$.getJSON("http://where.yahooapis.com/geocode?location=1110+Halton+Terrace,+Kanata,+ON&flags=J&appid=qrVViDXV34GuS1yV7Mi2ya09wffvK6zlXaN1LFLQ3Q7fIXQI2MVhMtLMKQkDWMPP_g--", function(data)
	{
		if ( data['ResultSet']['Error'] == 0 )
		{
			var lat = data['ResultSet']['Results'][0]['latitude'];
			var lng = data['ResultSet']['Results'][0]['longitude'];
			$("#lat").val(lat);
			$("#lng").val(lng);
			// set spinner to checkmark
			$("#event_location_status").removeClass("spinner-16px").addClass("location_good");
		} else {
			alert("fail")
		}
	});
	
	return true;
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
	
	// listener to check if url is available
	$('#event_url').keyup(function() {
		checkUrl($("#event_url").val());
	});
	
	// listener to geocode location
	$("#event_location").blur( function()
	{
		var geocoded = geocoder($("#event_location").val());
		return geocoded;
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
			var title = $("#event_title").val().replace(/ /g,"-").toLowerCase();
			// check if already in the database
			var check = checkUrl(title);
			if ( check == false )
			{
				// write to url input
				$("#event_url").val(title);
			}
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
	})
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
	})
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
	})
	$("#creditcard_number").blur( function()
	{
		if ( $("#creditcard_number").val() == "" )
		{
			$("#creditcard_number").focus();
			$("#creditcard_number").addClass("input-error");
			$("#creditcard_number_error").fadeIn();
		} else {
			$("#creditcard_number").removeClass("input-error");
			$("#creditcard_number_error").fadeOut();
		}
		
		return false;
	})
	$("#creditcard_verification_value").blur( function()
	{
		if ( $("#creditcard_verification_value").val() == "" )
		{
			$("#creditcard_verification_value").focus();
			$("#creditcard_verification_value").addClass("input-error");
			$("#creditcard_verification_value_error").fadeIn();
		} else {
			$("#creditcard_verification_value").removeClass("input-error");
			$("#creditcard_verification_value_error").fadeOut();
		}
		
		return false;
	})
	$("#address_zip").blur( function()
	{
		if ( $("#address_zip").val() == "" )
		{
			$("#address_zip").focus();
			$("#address_zip").addClass("input-error");
			$("#address_zip_error").fadeIn();
		} else {
			$("#address_zip").removeClass("input-error");
			$("#address_zip_error").fadeOut();
		}
		
		return false;
	})
	
	// form verification and submission 
	$("#btn-sign-up").click( function()
	{
		// setup variables
		var package = $("#package").val();
		var fname = $("#user_first_name").val(); // cannot be blank
		var lname = $("#user_last_name").val(); // cannot be blank
		var email = $("#user_email").val(); // cannot be blank and must be valid
		var password1 = $("#user_password").val(); // cannot be blank or less than 6 characters
		var password2 = $("#user_password_confirmation").val(); // must match password1
		
		var title = $("#event_title").val(); // cannot be blank
		var location = $("#event_location").val(); // cannot be blank
		var lat = $("#lat").val(); // cannot be zero
		var lng = $("#lng").val(); // cannot be zero
		var url = $("#event_url").val();
		
		var ccname = $("#creditcard_name").val(); // cannot be blank
		var ccnumber = $("#creditcard_number").val(); // cannot be blank
		var ccmonth = $("#creditcard_month").val(); // cannot be blank
		var ccyear = $("#creditcard_year").val(); // cannot be blank
		var cvv = $("#creditcard_verification_value").val(); // cannot be blank
		var zip = $("#address_zip").val(); // cannot be blank
		var agree = 0; // must be checked
		
		if ($('#terms-service').is(':checked')) {
			agree = 1;
		}
		
		$(".field-error").css({ "display":"none" });
		$("input").removeClass("input-error");
		$("#terms-refund h3").css({"color":"#444"});
		
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
		else if ( checkEmail(email) == false )
		{
			$("#user_email").focus();
			$("#user_email").addClass("input-error");
			$("#user_email_error").fadeIn();
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
		}
		else if ( title == "" )
		{
			$("#event_title").focus();
			$("#event_title").addClass("input-error");
			$("#event_title_error").fadeIn();
			location.href="#event-details";
		}
		else if ( location == "" )
		{
			$("#event_location").focus();
			$("#event_location").addClass("input-error");
			$("#event_location_error").fadeIn();
			location.href="#event-details";
		}
		else if ( (lat == "" || lat == "0") && (lng == "" || lng == "0") )
		{
			$("#event_location").focus();
			$("#event_location").addClass("input-error");
			$("#event_location_error").html("We can't seem to locate this address, would you like to <a href='#'>find it on a map</a>?").fadeIn();
			location.href="#event-details";
		}
		else if ( ccname == "" )
		{
			$("#creditcard_name").focus();
			$("#creditcard_name").addClass("input-error");
			$("#creditcard_name_error").fadeIn();
			location.href="#billing-info";
		}
		else if ( ccnumber == "" )
		{
			$("#creditcard_number").focus();
			$("#creditcard_number").addClass("input-error");
			$("#creditcard_number_error").fadeIn();
			location.href="#billing-info";
		}
		else if ( cvv == "" )
		{
			$("#creditcard_verification_value").focus();
			$("#creditcard_verification_value").addClass("input-error");
			$("#creditcard_verification_value_error").fadeIn();
			location.href="#billing-info";
		}
		else if ( zip == "" )
		{
			$("#address_zip").focus();
			$("#address_zip").addClass("input-error");
			$("#address_zip_error").fadeIn();
			location.href="#billing-info";
		}
		else if ( agree == 0 )
		{
			$("#terms-error").fadeIn();
			$("#terms-refund h3").css({"color":"#cc3300"});
			location.href="#terms-refund";
		} else {
			document.forms["create_event"].submit();
		}
		
		return false;
	});

});