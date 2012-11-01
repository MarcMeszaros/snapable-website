$(document).ready(function() 
{ 
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
});