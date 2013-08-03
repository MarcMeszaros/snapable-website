<script type="text/javascript">
	<?php if ($caption != '') { ?>
	stWidget.addEntry({
        "service":"twitter",
        "element":document.getElementById('photo-share-twitter'),
        "url":"https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>",
        "title":"<?= $caption ?>",
        "type":"vcount",
        "image":"<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"
    });
    <?php } else { ?>
    stWidget.addEntry({
        "service":"twitter",
        "element":document.getElementById('photo-share-twitter'),
        "url":"https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>",
        "title":"Check out this great photo",
        "type":"vcount",
        "image":"<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"
    });
    <?php } ?>
	stWidget.addEntry({
        "service":"facebook",
        "element":document.getElementById('photo-share-facebook'),
        "url":"https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>",
        "title":"<?= $caption ?>",
        "type":"vcount",
        "image":"<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"
    });
    stWidget.addEntry({
        "service":"pinterest",
        "element":document.getElementById('photo-share-pinterest'),
        "url":"https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>",
        "title":"<?= $caption ?>",
        "type":"vcount",
        "image":"<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"
    });
    stWidget.addEntry({
        "service":"googleplus",
        "element":document.getElementById('photo-share-googleplus'),
        "url":"https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>",
        "title":"<?= $caption ?>",
        "type":"vcount",
        "image":"<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"
    });
    stWidget.addEntry({
        "service":"email",
        "element":document.getElementById('photo-share-email'),
        "url":"https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>",
        "title":"<?= $caption ?>",
        "type":"vcount",
        "image":"<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"
    });
</script>
<img id="photo" src="/p/get/<?= $photo_id ?>/480x480" width="480" height="480" alt="Photo" />
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
	<span id="photo-share-twitter" class='st_twitter_vcount' displayText='Tweet' onclick="_gaq.push(['_trackEvent', 'Share', 'Twitter', 'Photo']);" st_via='GetSnapable' st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>" st_title="<?= $caption ?>"></span>
	<?php } else { ?>
	<span id="photo-share-twitter" class='st_twitter_vcount' displayText='Tweet' onclick="_gaq.push(['_trackEvent', 'Share', 'Twitter', 'Photo']);" st_via='GetSnapable' st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>" st_title="Check out this great photo"></span>
	<?php } ?>
	<span id="photo-share-facebook" class='st_facebook_vcount' displayText='Facebook' onclick="_gaq.push(['_trackEvent', 'Share', 'Facebook', 'Photo']);" st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>"></span>
	<span id="photo-share-pinterest" class='st_pinterest_vcount' displayText='Pinterest' onclick="_gaq.push(['_trackEvent', 'Share', 'Pinterest', 'Photo']);" st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>" st_image="<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"></span>
	<span id="photo-share-googleplus" class='st_googleplus_vcount' displayText='Google+' onclick="_gaq.push(['_trackEvent', 'Share', 'Google+', 'Photo']);" st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>"></span>
	<span id="photo-share-email" class='st_email_vcount' displayText='Email' onclick="_gaq.push(['_trackEvent', 'Share', 'Email', 'Photo']);" st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>"></span>

</div>

<div class="clearit">&nbsp;</div>