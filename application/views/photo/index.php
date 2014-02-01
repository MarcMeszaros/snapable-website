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
</script>
<div class="container" <?php if (!IS_AJAX) { ?>style="margin-top:140px;"<?php } ?>>
    <div class="row">
        <div class="col-lg-4 <?php if (!IS_AJAX) { ?>col-lg-push-1<?php } ?>">
            <?php if(!IS_AJAX) { ?>
        	<a class="btn btn-primary" href="/event/<?= $event_url ?>"><span class="glyphicon glyphicon-chevron-left"></span> Back to Event</a>
        	<?php } ?>

        	<?php 
        	if ( $caption != "" ) {
        		echo "<h2>&#8220;" . $caption . "&#8221;</h2>";
        	}
        	?>
        	
        	<!-- event details -->
            <p>
        	   Taken <strong><?= $date ?></strong><br />
        	   at <a href="/event/<?= $event_url ?>"><?= $event_name ?></a><br />
        	   by <strong><?php echo (strlen($photographer) > 0) ? $photographer:'Anonymous'; ?></strong>
            </p>
        	
        	<h3>Share</h3>

        	<?php if ($caption != '') { ?>
        	<span id="photo-share-twitter" class='st_twitter_vcount' displayText='Tweet' onclick="ga('send', 'event', 'Share', 'Twitter', 'Photo'); _gaq.push(['_trackEvent', 'Share', 'Twitter', 'Photo']);" st_via='GetSnapable' st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>" st_title="<?= $caption ?>"></span>
        	<?php } else { ?>
        	<span id="photo-share-twitter" class='st_twitter_vcount' displayText='Tweet' onclick="ga('send', 'event', 'Share', 'Twitter', 'Photo'); _gaq.push(['_trackEvent', 'Share', 'Twitter', 'Photo']);" st_via='GetSnapable' st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>" st_title="Check out this great photo"></span>
        	<?php } ?>
        	<span id="photo-share-facebook" class='st_facebook_vcount' displayText='Facebook' onclick="ga('send', 'event', 'Share', 'Facebook', 'Photo'); _gaq.push(['_trackEvent', 'Share', 'Facebook', 'Photo']);" st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>"></span>
        	<span id="photo-share-pinterest" class='st_pinterest_vcount' displayText='Pinterest' onclick="ga('send', 'event', 'Share', 'Pinterest', 'Photo'); _gaq.push(['_trackEvent', 'Share', 'Pinterest', 'Photo']);" st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>" st_image="<?= 'https://snapable.com/p/get_photo/'.$this->encrypt->encode($photo_id).'/crop' ?>"></span>
        	<span id="photo-share-googleplus" class='st_googleplus_vcount' displayText='Google+' onclick="ga('send', 'event', 'Share', 'Google+', 'Photo'); _gaq.push(['_trackEvent', 'Share', 'Google+', 'Photo']);" st_url="https://snapable.com/p/<?= $this->encrypt->encode($photo_id) ?>"></span>
        </div>
        <div class="<?php if (IS_AJAX) { ?>col-lg-8<?php } else { ?>col-lg-6 col-lg-push-1<?php } ?>">
            <img id="photo" class="img-thumbnail" src="/p/get/<?= $photo_id ?>/480x480" width="480" height="480" alt="Photo" />
        </div>
    </div>
</div>