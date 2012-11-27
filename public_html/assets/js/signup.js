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
		if ( data['status'] == 404 )
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
	$("#event_location_status").removeClass("location_good").removeClass("location_bad").addClass("spinner-16px")
	$.getJSON("http://where.yahooapis.com/geocode?location=" + encodeURIComponent(address) + "&flags=J&appid=qrVViDXV34GuS1yV7Mi2ya09wffvK6zlXaN1LFLQ3Q7fIXQI2MVhMtLMKQkDWMPP_g--", function(data)
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
			$("#event_location_status").removeClass("spinner-16px").addClass("location_bad");
		}

		var latLng = new google.maps.LatLng(lat, lng);
		var mapOptions = {
			center: latLng,
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.ROADMAP
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
        });
        $('<div class="form-field_hint" style="display: inline-block;">Tip: Drag the location marker to modify the event location.</div>').insertBefore('#map_canvas');
		
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
	
	// form verification and submission 
	$("#btn-sign-up").click( function()
	{
		// setup variables
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
		}/*
		else if ( checkEmail(email) == true )
		{
			$("#user_email").focus();
			$("#user_email").addClass("input-error");
			$("#user_email_error").fadeIn();
			$("#email_status").removeClass("spinner-16px").removeClass("email_good").addClass("email_bad");
			location.href="#your-details";
		}*/
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
		else if ( validUrl == 0 )
		{
			$("#event_url_status").removeClass("url_good").removeClass("spinner-16px").addClass("url_bad");	
			$("#event_url").addClass("input-error");
			$("#event_url_error").fadeIn();	
		} else {
			document.forms["create_event"].submit();
		}
		
		return false;
	});
});