function checkUrl(url) {
	$("#event_url_status").removeClass("url_good").removeClass("url_bad").addClass("spinner-16px")
	$.getJSON("/signup/check", { "url": url }, function(data) {
		if ( data['status'] == 404 && url.length > 0) {
			$("#event_url_status").removeClass("url_bad").removeClass("spinner-16px").addClass("url_good");	
			$("#event_url").removeClass("input-error");
			$("#event_url_error").fadeOut();	
			return true;	
		} else {
			$("#event_url_status").removeClass("url_good").removeClass("spinner-16px").addClass("url_bad");	
			$("#event_url").addClass("input-error");
			$("#event_url_error").fadeIn();
			return false;
		}
	});
}

function geocoder(address) {
	// do geocode to get addresses lat/lng
	// set #lat and #lng
	$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address=" + encodeURIComponent(address) + "&sensor=false", function(data) {
		if ( data['results'][0]['geometry']['location'] ) {
			var lat = data['results'][0]['geometry']['location']['lat'];
			var lng = data['results'][0]['geometry']['location']['lng'];
			$("#lat").val(lat);
			$("#lng").val(lng);
		}
		
		var timestamp = Math.round((new Date()).getTime() / 1000);
		var tzRequest = '/ajax/timezone?lat='+lat+'&lng='+lng+'&timestamp='+timestamp;
		$.getJSON(tzRequest, function(data){
			$('#timezone').val((data.rawOffset/60));
		});
	});

	return true;
}

function userExists(email) {
	$("#email_status").removeClass("email_good").removeClass("email_bad").addClass("spinner-16px");	
	$.getJSON("/signup/check", { "email": email }, function(data) {
		if ( data['status'] == 404 ) {
			$("#email_status").removeClass("spinner-16px").addClass("email_good");
			return true;
		} else {
			$("#email_status").removeClass("spinner-16px").addClass("email_bad");
			return false;
		}
	});
}

function sanitizeUrl() {
	// replace spaces with dashes change uppercase to lowercase
	var new_title = $("#event_url").val().replace(/&/g,"and");
	new_title = new_title.replace(/ /g,"-");
	new_title = new_title.replace(/[^a-zA-Z0-9_-]/g,"");
	var title = new_title.toLowerCase();
	// check if already in the database
	$("#event_url").val(title);
	checkUrl($("#event_url").val());
}

$(document).ready(function() {
	// validate all fields on blur
	$('form input').blur(function() {
		$(this).parsley('validate');
	});

    $("#event-start-date,#event-end-date").datepicker({dateFormat: 'M d, yy'});//( "option", "dateFormat", "d M, y" );
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
	$("#user_email").keyup($.debounce(650, function() {
		return userExists($(this).val());
	}));
	
	// listener to check if url is available
	$('#event_url').on('keyup change blur', $.debounce(650, function() {
		sanitizeUrl();
	}));
	
	// listener to geocode location
	$('#event_location').on('blur keypress', function(e){
		if (e.type == 'blur' || (e.type == 'keypress' && e.which == 13)) {
			return geocoder($("#event_location").val());
		}
	});
	
	// listener to set url from title
	$("#event_title").blur( function() {
		$("#event_url").val($(this).val());
		sanitizeUrl();
	});

	$("#event-duration-type").change( function() {
		var option = $(this).val();
		var values = "";
		var selected = "";
		
		if ( option == "days" ) {
			for ( var i=1; i <= 7; i++ ) { 
				values += "<option value='" + i + "'" + selected + ">" + i + "</option>";
			}
		} else {
			for ( var i=1; i<= 23; i++ ) { 
				if ( i == 12 ) {
					selected = " SELECTED";
				}
				values += "<option value='" + i + "'" + selected + ">" + i + "</option>";
			}
		}
		$("#event-duration-num").html(values);
		if ( option == "days" ) {
			$("#event-duration-num").val(1);
		} else {
			$("#event-duration-num").val(12);
		}
	});
	
	
	$("#apply-promo-code").click( function() {
		if ($('#promo-code').val().length > 0) {
			$.getJSON("/signup/promo", { "code": $("#promo-code").val() }, function(json) {	
				var promoCode = $("#promo-code").val();
				
				if ( json.status == 200 ) {
					var amount = parseFloat($("#package-amount").data('amount'));
					var discount = parseFloat(json.value);
					$('#promo-code-applied').val(promoCode);
					$('#promo-code').data('amount', discount);
					if (discount > amount) {
						discount = amount;
					}
					$("#package-amount").html((amount - discount)/100);

					// if the amount is 0, disable credit card fields
					if ((amount - discount) == 0) {
						$('#billing input,#billing select').not('#completSignup').attr("disabled", "disabled");
					} else {
						$('#billing input,#billing select').not('#completSignup').removeAttr("disabled");
					}
				} else {
					$.pnotify({
						type: 'info',
						text: "Sorry, that's not a valid promo code."
					});
				}
			});
		}
		return false;
	});
	
	// form verification and submission 
	$(".button").click( function() {
		var id = $(this).attr("id");
		
		$(".field-error").css({ "display":"none" });
		$("input").removeClass("input-error");

		if ( id == "eventDeets" ) {
			_gaq.push(['_trackPageview', 'signup/event_details']);
			
			$("#event").fadeOut("fast", function() {
				$("#navEvent").removeClass("active");
				$("#navYour").addClass("active");
				$("#your").fadeIn("fast");
			})
		} 
		else if ( id == "yourDeets" ) {
			_gaq.push(['_trackPageview', 'signup/your_details']);
			
			$("#your").fadeOut("fast", function() {
				$("#navYour").removeClass("active");
				$("#navBilling").addClass("active");
				$("#billing").fadeIn("fast");
			})
		}
		return false;
	});

	// form step toggle
	$('#navEvent,#navYour,#navBilling').click(function(){
		var id = $(this).attr("id");
		if (id == 'navEvent') {
			$("#navYour,#navBilling").removeClass("active");
			$("#navEvent").addClass("active");
			$("#your,#billing").fadeOut(400, function(){
				$("#event").fadeIn("fast");
			});
		} else if (id == 'navYour') {
			$("#navEvent,#navBilling").removeClass("active");
			$("#navYour").addClass("active");
			$("#event,#billing").fadeOut(400, function(){
				$("#your").fadeIn("fast");
			});
		} else if (id == 'navBilling') {
			$("#navEvent,#navYour").removeClass("active");
			$("#navBilling").addClass("active");
			$("#event,#your").fadeOut(400, function(){
				$("#billing").fadeIn("fast");
			});
		}
		return false;
	});
	
	$("#btn-sign-up").click( function() {
		document.forms["signupForm"].submit();
	});
	
	$("#payment-form").submit(function(event) {
		$('#completSignup').hide();
		$('#signup-spinner').removeClass('hide');
		// disable the submit button to prevent repeated clicks
		$('input[name=submit-button]').attr("disabled", "disabled");

		// create the token
		Stripe.createToken($("#payment-form").get(0), stripeResponseHandler);

		// prevent the form from submitting with the default action
		return false;
	});
	
	function stripeResponseHandler(status, response) {
	    if (response.error) {
	    	_gaq.push(['_trackPageview', 'signup/error']);
	    	$.pnotify({
				type: 'error',
				text: response.error.message
			});
	        // show the errors on the form
	        $('#signup-spinner').addClass('hide');
	        $('#completSignup').removeAttr("disabled").show();
	    } else {
	        _gaq.push(['_trackPageview', 'signup/submit']);
	        
	        var form = $("#payment-form");
	        // token contains id, last4, and card type
	        var token = response['id'];
	        // insert the token into the form so it gets submitted to the server
	        form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
	        // and submit
	        form.get(0).submit();
	    }
	}

	$("#creditcard_number").blur( function() {
		if (!Stripe.validateCardNumber($(this).val())) {
			$("#creditcard_number").addClass("input-error");
			$("#creditcard_number_error").fadeIn();
		} else {
			$("#creditcard_number").removeClass("input-error");
			$("#creditcard_number_error").fadeOut();
		}
		
		return false;
	});
	$("#creditcard_month, #creditcard_year").change( function() {
		if ( !Stripe.validateExpiry($('#creditcard_month').val(), $('#creditcard_year').val()) ) {
			$("#creditcard_exp_error").fadeIn();
		} else {
			$("#creditcard_exp_error").fadeOut();
		}
		
		return false;
	}); 
	$("#creditcard_cvc").blur( function() {
		if ( !Stripe.validateCVC($(this).val()) ) {
			$("#creditcard_cvc").addClass("input-error");
			$("#creditcard_cvc_error").fadeIn();
		} else {
			$("#creditcard_cvc").removeClass("input-error");
			$("#creditcard_cvc_error").fadeOut();
		}
		
		return false;
	});

	$("#creditcard_number").keyup($.debounce(500, function() {
		//#card_type
		var type = Stripe.card.cardType($(this).val());
		var valid_types = ['Visa', 'MasterCard', 'American Express', 'Discover'];
		if ($.inArray(type, valid_types) >= 0) {
			$('#card_type img').addClass('disabled');
			$("#card_type img[alt='"+type+"']").removeClass('disabled');
		} else {
			$('#card_type img').removeClass('disabled');
		}
	}));
	
});