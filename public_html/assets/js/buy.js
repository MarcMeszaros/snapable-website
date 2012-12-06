
$(document).ready(function() 
{

	  $("#payment-form").submit(function(event) {
		// disable the submit button to prevent repeated clicks
	    $('.submit-button').attr("disabled", "disabled");

	    Stripe.createToken({
	        number: $('#creditcard_number').val(),
	        cvc: $('#creditcard_cvc').val(),
	        exp_month: $('#creditcard_month').val(),
	        exp_year: $('#creditcard_year').val()
	    }, stripeResponseHandler);

	    // prevent the form from submitting with the default action
	    return false;
	  });

	function stripeResponseHandler(status, response) {
	    if (response.error) {
	    	console.log(response);
	        // show the errors on the form
	        $(".payment-errors").text(response.error.message);
	        $(".submit-button").removeAttr("disabled");
	    } else {
	        var form$ = $("#payment-form");
	        // token contains id, last4, and card type
	        var token = response['id'];
	        // insert the token into the form so it gets submitted to the server
	        form$.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
	        // and submit
	        form$.get(0).submit();
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
	})
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
	})
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
});