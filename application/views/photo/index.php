<div id="photo"><img src="/p/get/<?= $photo_id ?>/480x480" width="480" height="480" alt="Photo" /></div>

<div id="details">
	
	<a id="back" href="<a href="/event/<?= $event_url ?>">‹ Back</a>
	
	<?php 
	if ( $caption != "" )
	{
		echo "<h1>&#8220;" . $caption . "&#8221;</h1>";
	}
	?>
	
	<!--From the photo album “<a href="#">Andrew’s Big Ass Album</a>”, t-->Taken <strong><?= $date ?></strong><br />at “<a href="/event/<?= $event_url ?>"><?= $event_name ?></a>”<br />by <strong><?= $photographer ?></strong>
	<!--
	<h2>Share:</h2>
	
	<a id="twitter" href="#">Tweet</a>
	<a id="facebook" href="#">Share</a>
	<a id="email" href="#">Email</a>
	-->
	<!-- ONLY APPEARS IF IT"S EITHER THE OWNER OF THE GROUP OR TAKER OF THE PHOTO WHO"S VIEWING -->
	<!--<h2><a id="photo-download" href="#">Download Photo</a></h2>-->
	
</div>

<div class="clearit">&nbsp;</div>