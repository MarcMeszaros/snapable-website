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

	// get chosen photos
	$.Mustache.load('/assets/js/templates.html').done(function () 
	{
		var pPhotos = photos.split(",");
		$("#checkoutPhotos").fadeOut("fast", function()
		{
			$("#checkoutPhotos").html("");
			$.each(pPhotos, function(key, val) {
				var viewData = {
					id: val, 
					photo: '/p/get/' + val + '/200x200'
				};
				$('#checkoutPhotos').mustache('checkout-photo', viewData);
			});
			$("#checkoutPhotos").fadeIn("fast");
			
			// Trigger photo overlay code
			$(".photo").hover(
			  function () {
			    $(".photo-overlay", this).fadeIn("fast");
			  },
			  function () {
			    $(".photo-overlay", this).fadeOut("fast");
			  }
			); 
		})
	});
	
	
	$(document).on("click", ".removefrom-prints", function()
	{ 
		// remove from photos var
		var photoArr = photos.split(",");
		var photoID = $(this).attr("href").substring(1);
		var inPhotoArr = photoArr.indexOf(photoID);
		// reset cookie & remove from view
		if ( inPhotoArr >= 0 )
		{
			// remove from array
			photoArr.splice(inPhotoArr,1);
			var newIDstring = photoArr.toString();
			photos = newIDstring;
			createCookie('phCart', newIDstring,'90');
			$(this).parent().parent().parent().fadeOut("normal");
		}
	});
	
	$(document).on("click", ".continue", function()
	{ 
		var nextStep = $(this).attr("href").substr(1);
		if ( photos != "" )
		{
			window.location = "/checkout/" + nextStep;
		} else {
			alert("You haven't got any photos in your cart to be able to continue.");
		}
	});

});