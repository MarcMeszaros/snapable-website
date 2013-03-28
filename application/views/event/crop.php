<div id="cropBox">
	<img id="target" src="/tmp-files/<?= $image ?>" width="<?= $width ?>" height="<?= $height ?>" />
	<a id="cropDone" href="#">Done</a>
</div>
<div id="cropLoader">Cropping photo...<div class="bar"><span></span></div></div>

<style media="screen" type="text/css">
.jcrop-holder {
	margin: 0 auto;
}

</style>

<script type="text/javascript">

var xVal = 0;
var x2Val = 0;
var yVal = 0;
var y2Val = 0;
var wVal = 0;
var hVal = 0;

$('#target').Jcrop({
	aspectRatio: 1/1,
	trueSize: [<?= $orig_width ?>, <?= $orig_height ?>],
	onSelect: function(c) {
		// update the cropping values
		xVal = c.x;
		x2Val = c.x2;
		yVal = c.y;
		y2Val = c.y2;
		wVal = c.w;
		hVal = c.h;
	}
},function(){
	<?php if ($orig_width > $orig_height) { ?>
		this.setSelect([0,0,<?= $orig_height ?>,<?= $orig_height ?>]);
	<?php } else { ?>
		this.setSelect([0,0,<?= $orig_width ?>,<?= $orig_width ?>]);
	<?php } ?>
});

$("#cropDone").click( function()
{
	// switch content of Facebox to a load graphic
	$("#cropBox").fadeOut("fast", function()
	{
		$("#cropLoader").fadeIn("fast");

		// params for uploading image
		var params = { image:'<?= $image ?>', x:xVal, x2:x2Val, y:yVal, y2:y2Val, w:wVal, h:hVal };
		if (typeof guestID !== 'undefined') {
    		params.guest = guestID;
		}
		if (typeof eventID !== 'undefined') {
    		params.event = eventID;
		}
		if (typeof typeID !== 'undefined') {
    		params.type = typeID;
		}

		$.post("/upload/square", params, function(data, textStatus, jqXHR) {
			// set photo id
			var resource_uri = data.result.resource_uri.split("/");

			// PREPEND PHOTO TO PHOTOSTREAM
			var viewData = { 
				id: resource_uri[3],
				url: '/p/'+resource_uri[3],
				photo: '/p/get/' + resource_uri[3] + '/200x200',
				caption: data.result.caption,
				photographer: data.result.author_name,
				owner: owner
			};

			// load the photo into the DOM
			loadPhoto(viewData, {method:'prepend'});
			$('#uploadedArea').modal('hide');

		}, "json").fail(function(){
			$("#cropLoader").fadeOut();
			$('#uploadedArea').modal('hide');
			$.pnotify({
				type: 'error',
				title: 'Image Upload',
				text: "Something went wrong during upload and your photo didn't get added."
			});
		});
	})
});

// reset the upload modal screen
$('#uploadedArea').on('hidden', function () {
  $(this).data('modal', null);
});

</script>