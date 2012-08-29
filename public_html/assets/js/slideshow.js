$(document).ready(function() 
{ 

	var bWidth = $(window).width();
	var bHeight = $(window).height();
	
	var spinLeft = (bWidth / 2 ) - 16;
	var spinTop = (bHeight / 2 ) - 16;
	$("#spinner").css({"top": spinTop + "px", "left": spinLeft + "px"});
	
	var photoWidth = bWidth;
	var photoHeight = bHeight;
	
	if ( bWidth > bHeight )
	{
		photoWidth = bHeight;
	} else {
		photoHeight = bWidth;
	}
	photoPos = (bWidth - photoWidth) / 2;
	
	// get photo list for event
	$.getJSON('/slideshow/photos', { url:url }, function(json) 
	{
		//$("#photos").html(json.id + ",<br />" + json.caption + ",<br />" + json.photographer + ",<br />" + json.timestamp);
		setInterval("showPhoto(" + json.id + "," + photoPos + "," + photoWidth + "," + photoHeight + ")", 5000);
	});
});

showPhoto(id, pos, width, height)
{
	$("#photos").css({ "left":pos + "px" }).append("<img src='/p/get/" + json.id + "/?size=" + width + "x" + height + "' width='" + width + "' height='" + width + "' />")
	$("#photos img").load(function() {
		$("#spinner").css({"display":"none"});
		$('#photos').fadeIn( "slow", function() {
			
		});
    });
}