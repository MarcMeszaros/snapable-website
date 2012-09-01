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
	
	var photoJSON = '{ "objects": [';
	
	// get photo list for event
	$.getJSON('/slideshow/photos', { url:url }, function(json) 
	{
		if ( json.status == 200 && json.response.meta.total_count > 0 )
		{
			var count = 1;
			$.each(json.response.objects, function(key, val) 
			{
				var used = 0;
				var resource_uri = val.resource_uri.split("/");
				/*
				if ( count <= 12 )
				{
					$("#photos").append('<img src="/p/get/' + resource_uri[3] + '/?size=' + photoWidth + 'x' + photoHeight + '" width="' + photoWidth + '" height="' + photoHeight + '" />');
					used = 1;
				}
				*/
				
				$("#photos").append('<li data-cycle-image="/p/get/' + resource_uri[3] + '/?size=' + photoWidth + 'x' + photoHeight + '">Slide 1</li>');
				
				photoJSON += '{' +
					'"used": ' + used + ',' +
	                '"resource_uri": "' + val.resource_uri + '"' +
	            '},';
				count++;
			});
			
			if ( photoJSON.substr(-1, 1) == "," )
			{
				photoJSON = photoJSON.slice(0, -1);
			}
			photoJSON += '],' + '"count": ' + count + '}';
			
			
			// POSITION AND SIZE PHOTOS
			
			$("#photos, #photos li").css({ "width":photoWidth + "px", "height":photoHeight + "px" });
			$("#photos").css({ "left": photoPos + "px" });
			/*
			$('#photos').cycle({ 
			    fx:    'fade', 
			    speed:  2500,
			    timeout: 2000,
			    nowrap: true,
			    before: function()
			    {
				    $("#spinner").fadeOut("fast");
			    },
			    end:  function(currSlideElement, nextSlideElement) 
				{
					$("#photos").cycle('destroy');
					updateSlideshow(json.response.meta.total_count, photoJSON, photoWidth, photoHeight);
				}
			 });
			*/
			
			// SET CYCLE
			
			$('#photos').cycle({
				fx:    'fade', 
			    speed:  4000,
			    timeout: 10000,
				after: function(currSlideElement, nextSlideElement) 
				{ 
					$("#spinner").fadeOut("fast");
					
					// Lazy loading of images
					var data_cycle_image = $(nextSlideElement).attr('data-cycle-image');
					
					if (typeof data_cycle_image !== 'undefined' && data_cycle_image !== false) {
						$('#photos').cycle('pause');
						var enlarge_preload = new Image();
						enlarge_preload.src = data_cycle_image;
						enlarge_preload.onload = function() {
							$(nextSlideElement).css({"background-image":"url(" + enlarge_preload.src + ")"}).removeAttr('data-cycle-image')
							$('#photos').cycle('resume');
						}
					}
				}
			});
			
		} else {
			alert("There are either no photos for this event or something has gone awry");
		}
	});
	
	$("#pause").click( function()
	{
		if ( $(this).attr("rel") == "pause" )
		{
			$(this).attr("rel", "resume").removeClass("btnPause").addClass("btnPlay");
			$("#photos").cycle('pause');
		} else {
			$(this).attr("rel", "pause").removeClass("btnPlay").addClass("btnPause");
			$("#photos").cycle('resume');
		}
	});
	$("#play").click( function()
	{
		$("#photos").cycle('resume');
	});
	$("#next").click( function()
	{
		$("#photos").cycle('next');
	});
	$("#prev").click( function()
	{
		$("#photos").cycle('prev');
	});
	$("#hide").click( function()
	{
		if ( $("#toolbar").css("right") == "-284px" )
		{
			$("#toolbar").animate({
		    	right: '+=284'
		    }, function()
		    {
		    	$("#hide").removeClass("arrowLeft").addClass("arrowRight");
		    });
		} else {
			$("#toolbar").animate({
		    	right: '-=284'
		    }, function()
		    {
		    	$("#hide").removeClass("arrowRight").addClass("arrowLeft");
		    });
		}
	})
});

function updateSlideshow(photoCount, photoJSON, photoWidth, photoHeight)
{
	//alert(photoJSON)
	var photoObj = jQuery.parseJSON(photoJSON);
	photoJSON = '{ "objects": [';
	var count = 1;
	var usedCount = 0;
	
	// check if there's any new photos
	
	// check if we're at the end and should start over
	$.each(photoObj.objects, function(key, val) {
		if ( val.used == 1 )
		{
			usedCount++;
		}
	});
	
	if ( usedCount == photoCount )
	{	
		photoJSON2 = '{ "objects": [';
		$.each(photoObj.objects, function(key, val) 
		{
			// reset all used
			photoJSON2 += '{' +
				'"used": 0,' +
                '"resource_uri": "' + val.resource_uri + '"' +
            '},';
		});
		if ( photoJSON2.substr(-1, 1) == "," )
		{
			photoJSON2 = photoJSON2.slice(0, -1);
		}
		photoJSON2 += '],' + '"count": ' + count + '}';
		
		var photoObj = jQuery.parseJSON(photoJSON2);
	}
		
	$('#photos').html("");
		
	$.each(photoObj.objects, function(key, val) {
		
		var used = 0;
		var resource_uri = val.resource_uri.split("/");
				
		if ( count <= 12 && val.used == 0 )
		{
			$("#photos").append('<img src="/p/get/' + resource_uri[3] + '/?size=' + photoWidth + 'x' + photoHeight + '" width="' + photoWidth + '" height="' + photoHeight + '" />');
			used = 1;
		}
		
		photoJSON += '{' +
			'"used": ' + used + ',' +
            '"resource_uri": "' + val.resource_uri + '"' +
        '},';
		count++;
	});
	
	if ( photoJSON.substr(-1, 1) == "," )
	{
		photoJSON = photoJSON.slice(0, -1);
	}
	photoJSON += '],' + '"count": ' + count + '}';
	
	$('#photos').cycle({ 
	    fx:    'fade', 
	    speed:  2500,
	    timeout: 10000,
	    nowrap: true,
	    before: function()
	    {
		    $("#spinner").fadeOut("fast");
	    },
	    end:  function(currSlideElement, nextSlideElement) 
		{
			updateSlideshow(photoCount, photoJSON);
		}
	 });
}
