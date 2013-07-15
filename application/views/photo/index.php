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
	
	<?php if ($caption != '') { ?>
	<a class="photo-share-twitter" target="_blank" onclick="_gaq.push(['_trackEvent', 'Share', 'Twitter', 'Photo']);" href="//twitter.com/share?text=<?= urlencode($caption . ' via @GetSnapable') ?>&url=https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>">Tweet</a> 
	<?php } else { ?>
	<a class="photo-share-twitter" target="_blank" onclick="_gaq.push(['_trackEvent', 'Share', 'Twitter', 'Photo']);" href="//twitter.com/share?text=<?= urlencode("Check out this great photo via @GetSnapable") ?>&url=https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>">Tweet</a> 
	<?php } ?>
	<a class="photo-share-facebook" target="_blank" onclick="_gaq.push(['_trackEvent', 'Share', 'Facebook', 'Photo']);" href="//www.facebook.com/sharer.php?u=https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>">Share</a>
	<a class="photo-share-pinterest" target="_blank" onclick="_gaq.push(['_trackEvent', 'Share', 'Pinterest', 'Photo']);" href="//pinterest.com/pin/create/button/?url=<?= urlencode('https://snapable.com/p/') . $this->encrypt->encode($photo_id) ?>&media=<?= urlencode('https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop') ?>">Pin it</a>

</div>

<div class="clearit">&nbsp;</div>