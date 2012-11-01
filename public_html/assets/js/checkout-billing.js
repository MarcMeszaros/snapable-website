$(document).ready(function() 
{ 
	var d = new Date();
	var m = d.getMonth() + 1;
	var y = d.getFullYear();
	
	if ( m.toString().length == 1 )
	{
		m = "0" + m;
	}
	
	$("select[name=card-expiry-month]").val(m);
	$("select[name=card-expiry-year]").val(y);
	
	function addInputNames() {
        // Not ideal, but jQuery's validate plugin requires fields to have names
        // so we add them at the last possible minute, in case any javascript 
        // exceptions have caused other parts of the script to fail.
        $(".card-number").attr("name", "card-number")
        $(".card-cvc").attr("name", "card-cvc")
        $(".card-expiry-year").attr("name", "card-expiry-year")
    }

    function removeInputNames() {
        $(".card-number").removeAttr("name")
        $(".card-cvc").removeAttr("name")
        $(".card-expiry-year").removeAttr("name")
    }

    function submit(form) {
        // remove the input field names for security
        // we do this *before* anything else which might throw an exception
        removeInputNames(); // THIS IS IMPORTANT!

        // given a valid form, submit the payment details to stripe
        $(form['submit-button']).attr("disabled", "disabled")

        Stripe.createToken({
            name: $('.card-name').val(),
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(), 
            exp_year: $('.card-expiry-year').val()
        }, function(status, response) {
            if (response.error) {
                // re-enable the submit button
                $(form['submit-button']).removeAttr("disabled")

                // show the error
                $(".payment-errors").css({ "display":"block" }).html(response.error.message);

                // we add these names back in so we can revalidate properly
                addInputNames();
                return false;
            } else {
                // token contains id, last4, and card type
                var token = response['id'];

                // insert the stripe token
                var input = $("<input name='stripeToken' value='" + token + "' style='display:none;' />");
                form.appendChild(input[0])

                // and submit
                form.submit();
            }
        });
    }
    
    // add custom rules for credit card validating
    jQuery.validator.addMethod("cardNumber", Stripe.validateCardNumber, "Please enter a valid card number");
    jQuery.validator.addMethod("cardCVC", Stripe.validateCVC, "Please enter a valid security code");
    jQuery.validator.addMethod("cardExpiry", function() {
        return Stripe.validateExpiry($(".card-expiry-month").val(), 
                                     $(".card-expiry-year").val())
    }, "Please enter a valid expiration");

    // We use the jQuery validate plugin to validate required params on submit
    $("#billingForm").validate({
        submitHandler: submit,
        rules: {
            "card-cvc" : {
                cardCVC: true,
                required: true
            },
            "card-number" : {
                cardNumber: true,
                required: true
            },
            "card-expiry-year" : "cardExpiry" // we don't validate month separately
        }
    });

    // adding the input field names is the last step, in case an earlier step errors                
    addInputNames();
	/*
	// set expiry date month to this month
	var d = new Date();
	var month = d.getMonth();
	$("select[name=billing_expiry_month]").val(month)
	
	$("#card_type img").click( function()
	{
		$("#card_type img").removeClass("cardSelected");
		$(this).addClass("cardSelected");
	});
	
	$('form#billingForm').submit(function(e) 
	{
		e.preventDefault();
		
		var url = $(this).attr("action");

		var name = $("#billing_name").val();
		var number = $("#billing_num").val();
		var expiry_month = $("select[name=billing_expiry_month]").val();
		var expiry_year = $("select[name=billing_expiry_year]").val();
		var cvv = $("#billing_cvv").val();
		var zip = $("#billing_zip").val();
		
		alert("add stripe validation")
	});
	*/
});