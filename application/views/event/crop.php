<div id="cropBox">
	<div id="cropInstructions">
		Move and resize the box to how you'd like the final image to look then click "Done".
	</div>
	
	<img id="target" src="/tmp-files/<?= $image ?>" width="<?= $width ?>" height="<?= $height ?>" />
	
	<a id="cropDone" href="#">Done</a>
</div>

<div id="cropLoader">Cropping photo...<div class="bar"><span></span></div></div>

<script type="text/javascript">

var xVal = 0;
var x2Val = 0;
var yVal = 0;
var y2Val = 0;
var wVal = 0;
var hVal = 0;

var jcrop_api;

$('#target').Jcrop({
	aspectRatio: 1/1,
	trueSize: [<?= $orig_width ?>, <?= $orig_height ?>],
	onSelect: updateCoords
},function(){
	// Store the API in the jcrop_api variable
	jcrop_api = this;
	<?php if ($orig_width > $orig_height) { ?>
		jcrop_api.setSelect([0,0,<?= $orig_height ?>,<?= $orig_height ?>]);
	<?php } else { ?>
		jcrop_api.setSelect([0,0,<?= $orig_width ?>,<?= $orig_width ?>]);
	<?php } ?>

});

function updateCoords(c)
{
	console.log(c);
	xVal = c.x;
	x2Val = c.x2;
	yVal = c.y;
	y2Val = c.y2;
	wVal = c.w;
	hVal = c.h;
};

$("#cropDone").click( function()
{
	var image = $("#target").attr("src");
	
	// switch content of Facebox to a load graphic
	$("#cropBox").fadeOut("fast", function()
	{
		$("#cropLoader").fadeIn("fast");

		// params for uploading image
		var params = { image:image, x:xVal, x2:x2Val, y:yVal, y2:y2Val, w:wVal, h:hVal };
		if (typeof guestID !== 'undefined') {
    		params.guest = guestID;
		}
		if (typeof eventID !== 'undefined') {
    		params.event = eventID;
		}
		if (typeof typeID !== 'undefined') {
    		params.type = typeID;
		}

		$.post("/upload/square", params, function(data) {
			console.log(data);
			var json = eval(data);
			// set photo id
			var resource_uri = json.result.resource_uri.split("/");
			
			// HIDE FACEBOX
			$.facebox.close();
			// CLOSE ALL UPLOAD ITEMS
			$("#uploadedArea").fadeOut("fast", function() {
				$("#uploadArea").slideToggle("fast");
			});
			// PREPEND PHOTO TO PHOTOSTREAM (IF FIRST PHOTO ENSURE FIRST-RUN STUFF IS HIDDEN)
			$("#eventFirstRun").css({"display":"none"});
			var viewData = { 
				url: resource_uri[3],
				photo: '/p/get/' + resource_uri[3],
				caption: json.result.caption,
				photographer: json.result.author_name
			};
			$.Mustache.load('/assets/js/templates.html').done(function() {
				$('#photoArea').removeClass("noPhotos").mustache('event-list-photo', viewData, { method:"prepend" });
			});
		}, "json").fail(function(){
			$.facebox.close();
			$("#cropLoader").fadeOut();
			$.pnotify({
				type: 'error',
				title: 'Image Upload',
				text: "Something went wrong during upload and your photo didn't get added."
			});
		});
	})
});

</script>