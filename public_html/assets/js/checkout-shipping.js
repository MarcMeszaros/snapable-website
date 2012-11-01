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

$(document).ready(function() 
{ 
	$('form#shippingForm').submit(function(e) 
	{
		e.preventDefault();
		
		var url = $(this).attr("action");
		
		// verify form if filled out
		var name = $("#shipping_name").val();
		var address = $("#shipping_address").val();
		var city = $("#shipping_city").val();
		var state = $("select[name=shipping_state]").val();
		var country = $("select[name=shipping_country]").val();
		var zip = $("#shipping_zip").val();
		var email = $("#user_email").val();
		
		if ( name == "" )
		{
			alert("You'll need to a name for a person at the address you're shipping to.");
			return false;
		}
		else if ( address == "" )
		{
			alert("You'll need to let us know where to deliver your package.");
			return false;
		}
		else if ( city == "" )
		{
			alert("Unfortunately we can't ship your package to just a street address, we'll need the city/town as well.");
			return false;
		}
		else if ( state == 0 )
		{
			alert("You'll need to let us know the state or province to deliver to, if it's not listed please pick 'Not Available'.");
			return false;
		}
		else if ( zip == "" )
		{
			alert("You'll need to give us a postal or zip code for the address as well.");
			return false;
		}
		else if ( checkEmail(email) == false )
		{
			alert("We need a valid email address to send your receipt and shipping notifications.");
			return false;
		} else {
			$.post("/checkout/address", { type:"shipping", name:name, address:address, city:city, state:state, country:country, zip:zip, email:email }, function(data)
			{
				var json = jQuery.parseJSON(data);
				if ( json.status == 200 )
				{
					window.location = url;
				}
			});
			return false;
		}
		return false;
	});
});