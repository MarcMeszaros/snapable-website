// some global variables (required to make the on DOM load stuff work)
var photoArr = new Array();
var photoAPI;
var photoAPIOffset = 0;

var slideCount = 1;
function firstRunSlideshow()
{
	$("#eventFirstRunText .displayMe").fadeOut("normal", function()
    {
    	if ( slideCount >= 2 )
    	{
	    	$(this).removeClass("displayMe")
	    	$("#uploadText").fadeIn("normal").addClass("displayMe");
	    	$("a.blue").removeClass("blue");
	    	$("#uploadDot").addClass("blue"); 
	    	slideCount = 1;
    	} else {
	    	$(this).removeClass("displayMe").next("li").fadeIn("normal").addClass("displayMe");
	    	$("a.blue").removeClass("blue").next("a").addClass("blue"); 
	    	slideCount++;
	    } 
    })
}

function sanitizeUrl(url)
{
	// replace spaces with dashes change uppercase to lowercase
	var new_url = url.replace(/&/g,"and");
	new_url = new_url.replace(/ /g,"-");
	new_url = new_url.replace(/[^a-zA-Z0-9_-]/g,"");
	new_url = new_url.toLowerCase();
	return new_url;
}

function checkUrl(url)
{
	$("#event-settings-url-status").removeClass("good").removeClass("bad").addClass("spinner-16px");
	$.getJSON("/signup/check", { "url": url }, function(data) {		
		if ( data['status'] == 404 && url.length > 0) {
			$("#event-settings-url-status").removeClass("bad").removeClass("spinner-16px").addClass("good");	
		} else {
			$("#event-settings-url-status").removeClass("good").removeClass("spinner-16px").addClass("bad");	
		}
	});
}

// when the DOM is ready
$(document).ready(function() 
{  
	var csvFilename = "";

	if ( photo_count > 0 )
	{
		// Display Loader
		$("#photoArea").css({"text-align":"center","font-weight":"bold"}).html('<div id="photoRetriever">Retrieving Photos...<div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div></div>');
		// Get photos for event
		$.getJSON('/event/get/photos/' + $('#event-top').data('event-id'), function(json) {
			if ( json.status == 200 )
			{
				$("#photoRetriever").css({"display":"none"});
				$.Mustache.load('/assets/js/templates.html').done(function () 
				{
					// check if any photos are in the cart
					var photoArr = new Array();
					
					// add initial photos
					photoAPI = json;
					loadPhotos(photoAPI);

					// hook up the 'Load More' button
					$(document).on("click", ".loadMore", function(e)
					{ 
						e.preventDefault();
						$.Mustache.load('/assets/js/templates.html').done(function () 
						{
							loadPhotos(photoAPI);
						});
						return false;
					});

				});
			} else {
				// hide loader and display error
				$("#photoArea").html("Something went wrong while fetching the photos for this event.");
			}
		});
	} else {
		$("#photoArea").addClass("noPhotos");
		
		$.Mustache.load('/assets/js/templates.html').done(function () 
		{
	        $('#photoArea').mustache('event-first-run');
	        // start dot cycle
	        setInterval ( "firstRunSlideshow()", 5000 );
	        // set dot buttons
	        $(document).on("click", "#eventFirstRunDots a", function()
	        {  
	        	if ( $(this).attr("id") == "uploadDot" )
	        	{
		        	slideCount = 1;
	        	} else {
		        	slideCount = 2;
	        	}
	        	var clicked = $(this).attr("href").substring(1);
	        	$("#eventFirstRunDots a").removeClass("blue");
	        	$(this).addClass("blue");
	        	$("#eventFirstRunText .displayMe").fadeOut("normal", function()
	        	{
		        	$(this).removeClass("displayMe");
		        	$("#" + clicked + "Text").fadeIn("normal").addClass("displayMe");
	        	})
	        });
		});
	}
});

/*
Add photo to the DOM.
*/
function loadPhoto(photoData, options) {
	// make sure the first run is hidden
	$("#eventFirstRun").css({"display":"none"});

	// declare the options variable if it's not set
	if (typeof options === 'undefined') {
		options = {};
	}
	// set the filter position
	var filter_position = ':last';
	if (options.method === 'prepend') {
		filter_position = ':first';
	}
	var $domPhoto = $('#photoArea').mustache('event-list-photo', photoData, options);

	// set cover photo
	$domPhoto.find('div.photo-buttons a.add-cover').filter(filter_position).click(function(){
		// make an ajax call
		$.ajax('/ajax/put_event/'+$('#event-top').data('event-id'), {
			type: 'POST',
			data: {
				cover: $(this).data('photo_id')
			},
			success: function(){
				// update the DOM
				var imgSrc = $('img#event-cover-image').attr('src').split('?');
				$('img#event-cover-image').attr('src', imgSrc[0]+'?'+new Date().getTime());
				$.pnotify({
			    	type: 'success',
			        title: 'Event Cover Photo Updated',
			        text: 'Your event cover photo has been successfully updated.'
		    	});
			}
		});
	});

	// setup the delete per photo
	$domPhoto.find('div.photo a.photo-delete').filter(filter_position).click(function(){
		var deleteButton = $(this); // save a reference to that button

		$.ajax('/ajax/delete_photo/'+$(deleteButton).attr('data-photo_id'), {
			success: function(data, textStatus, jqXHR) {
				if (jqXHR.status == 200 || jqXHR.status == 204) {
					// remove it from the ui
					$(deleteButton).closest('div.photo').remove();
					$.pnotify({
			    		type: 'info',
			        	title: 'Photo Deleted',
			        	text: 'The photo was successfully deleted.',
		        	});
				} else {
					$.pnotify({
			    		type: 'error',
			        	title: 'Photo Not Deleted',
			        	text: 'Oops. Something went wrong and we could not delete the photo.',
		        	});
				}	
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$.pnotify({
					type: 'error',
					title: 'Photo Delete',
					text: 'There was an error deleting the photo.'
				});
			}
		});

		return false;
	});

	// Trigger photo overlay code
	$domPhoto.find('div.photo').filter(filter_position).hover(function () {
	    $(".photo-overlay", this).fadeIn("fast");
	  },
	  function () {
	    $(".photo-overlay", this).fadeOut("fast");
	  }
	);
	$domPhoto.find('div.photo a.photo-enlarge').filter(filter_position).facebox();
	$domPhoto.find('div.photo a.photo-share').filter(filter_position).facebox();

	// setup the tooltips
	$domPhoto.find('div.photo .photo-credit').filter(filter_position).tooltip();

	// setup the download
	$domPhoto.find('a.photo-download').filter(filter_position).click(function(){
		document.location = '/download/photo/'+$(this).attr('data-photo_id')+'/orig';
		return false; // end execution of the javascript
	});
}

/*
Function used to load photos into the photo stream and hook up all the
buttons and events to each photo.
*/
function loadPhotos(photos) {
	// setup some variables
	var count = 0;

	//$.each(photos.response.objects, function(key, val) {
	offset = 0;
	if (photos.response.objects.hasOwnProperty('offset')) {
		offset = photos.response.objects.offset;
		$(".loadMoreWrap").html("<span></span>").addClass("bar");
	} else {
		photos.response.objects.offset = offset;
	}
	for (var key = offset; key < photos.response.objects.length ; key++) {
		var val = photos.response.objects[key];

		if ( count < 12 )
		{
			var resource_uri = val.resource_uri.split("/");
			var inPhotoArr = $.inArray(resource_uri[3], photoArr);
			
			var viewData = {
				id: resource_uri[3], 
				url: '/p/' + resource_uri[3],
				photo: '/p/get/' + resource_uri[3] + '/200x200',
				caption: val.caption,
				photographer: val.author_name,
				owner: $('form#event-settings').length
			};
			// add photo to dom
			loadPhoto(viewData);
			delete photos.response.objects[key];
			count++;
		}
	}
	photos.response.objects.offset += count; // used to know where to resume looping
	$(".loadMoreWrap").remove();
	
	if ( photos.response.objects.offset < photos.response.objects.length-1 )
	{
		$("#photoArea").append("<div class='loadMoreWrap'><a class='loadMore' href='#'>Load More</a></div>");
	} else if (photo_count > 50 && (photoAPIOffset + 1) < photo_count) {
		console.log('we should load more from the api');
		photoAPIOffset += 50;

		$.getJSON('/event/get/photos/' + $('#event-top').data('event-id') + '/' + photoAPIOffset, function(json) {
			if ( json.status == 200 ) {
				photoAPI = json;
				$("#photoArea").append("<div class='loadMoreWrap'><a class='loadMore' href='#'>Load More</a></div>");
			}
		});
	}

}