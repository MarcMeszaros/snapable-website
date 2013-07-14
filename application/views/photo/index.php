<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "e0bd9da5-9c45-4ad2-b1a3-81ca7d809ede", doNotHash: true, doNotCopy: true, hashAddressBar: false}); </script>

<div id="photo"><img src="/p/get/<?= $photo_id ?>/480x480" width="480" height="480" alt="Photo" /></div>

<div id="details">
	
	<a id="back" href="/event/<?= $event_url ?>">‹ Back</a>
	
	<?php 
	if ( $caption != "" )
	{
		echo "<h1>&#8220;" . $caption . "&#8221;</h1>";
	}
	?>
	
	<!--From the photo album “<a href="#">Andrew’s Big Ass Album</a>”, t-->
	Taken <strong><?= $date ?></strong><br />
	at “<a href="/event/<?= $event_url ?>"><?= $event_name ?></a>”<br />
	by <strong><?php echo (strlen($photographer) > 0) ? $photographer:'Anonymous'; ?></strong>
	
	<h2>Share</h2>
	
	<a class="photo-share-twitter" target="_blank" onclick="_gaq.push(['_trackEvent', 'Share', 'Twitter', 'Event']);" href="http://twitter.com/share?text=<?= urlencode("Follow the photos on at with @getsnapable") ?>&url=https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>">Tweet</a> 
	<a class="photo-share-facebook" target="_blank" onclick="_gaq.push(['_trackEvent', 'Share', 'Facebook', 'Event']);" href="http://www.facebook.com/sharer.php?u=https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>">Share</a>
	<a class="photo-share-pinterest" target="_blank" onclick="_gaq.push(['_trackEvent', 'Share', 'Pinterest', 'Event']);" href="//pinterest.com/pin/create/button/?url=https%3A%2F%2Fsnapable.com%2Fp%2F<?= $this->encrypt->encode($photo_id) ?>&media=<?= urlencode('https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/orig') ?>">Pin it</a>

	
</div>

<div class="clearit">&nbsp;</div>