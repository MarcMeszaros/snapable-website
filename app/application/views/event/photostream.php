<div class="container">
<div class="row">
<div class="col-lg-12">
<div id="event-top" data-event-id="<?= $event_pk ?>" data-photo-count="<?= $event->photo_count ?>">
	<div class="row" style="margin-top:30px;">
		<div class="col-lg-2">
			<img id="event-cover-image" class="img-thumbnail" src="/p/get_event/<?= $event_pk ?>/150x150" data-event-id="<?= $event_pk ?>" />
		</div>
		<div id="event-title-wrap" class="col-lg-6">
			<h2 id="event-title"><?= $event->title ?></h2>
			<div id="event-address"><?= (!$event->is_public && isset($address_pk)) ? $address : '&nbsp;' ?></div>
			<div><span id="event-timestamp-start"><?= $event->human_start ?></span> to <span id="event-timestamp-end"><?= $event->human_end ?></span>
				<?php if ($ownerLoggedin) { ?> &nbsp;
					<button id="event-settings-btn" class="btn btn-primary btn-xs" style="margin-top:-2px;" onclick="$('#event-settings').slideDown();"><span class="glyphicon glyphicon-edit"></span> Edit Event</button>
					<button id="downloadBTN" class="btn btn-primary btn-xs" style="margin-top:-2px;" onclick="downloadAlbum('<?= $event_pk ?>'); ga('send', 'event', 'Navigation', 'Download_Album');"><span class="glyphicon glyphicon-download"></span> Download Album</button>
				<?php } ?>
			</div>
			<?php if ( $ownerLoggedin ) { ?>
			<form id="event-settings" role="form" method="POST" action="/ajax/put_event/<?= $event_pk ?>">
				<h3>Edit Your Event Details</h3>
				<input id="event-settings-lat" name="lat" type="hidden" value="<?= (isset($address_pk)) ? $address_lat : '0' ?>"/>
				<input id="event-settings-lng" name="lng" type="hidden" value="<?= (isset($address_pk)) ? $address_lng : '0' ?>"/>
				<input id="event-settings-timezone" name="tz_offset" type="hidden" value="<?php echo $event->tz_offset; ?>"/>
				<input id="event-settings-start" name="start" type="hidden" value="<?php echo $event->start_epoch; ?>"/>

				<div class="form-group">
					<label for="event-settings-title">Event Title</label>
					<input id="event-settings-title" class="form-control" name="title" type="text" value="<?php echo $event->title; ?>"/>
				</div>
				<div class="form-group">
					<label for="event-settings-url">Event URL</label>
					<input id="event-settings-url" class="form-control status" name="url" type="text" data-orig="<?php echo $event->url; ?>" value="<?php echo $event->url; ?>"/>
				</div>
				<div class="form-group row">
					<div class="form-group col-sm-4">
						<label for="event-start-date">Date</label>
						<input type="text" id="event-start-date" class="form-control" name="start_date" value="<?= date("M j, Y", $event->start_epoch + ($event->tz_offset * 60)) ?>">
					</div>
					<div class="form-group col-sm-3">
						<label for="event-start-time">Time</label>
						<input type="text" id="event-start-time" class="form-control" name="start_time" value="<?= date("h:i A", $event->start_epoch + ($event->tz_offset * 60)) ?>">
					</div>
					<div class="form-group col-sm-5">
						<label for="event-duration-num">Duration</label>
						<div class="form-inline">
						<select id="event-duration-num" class="form-control" name="duration_num" style="width:49%;">
							<?php
							// get the delta (sec.)
							$delta = $event->end_epoch - $event->start_epoch;

							if ($delta < 60*60*24) {
								for ($i=1; $i<=23; $i++) {
									if ( $i == floor($delta/60/60) )	{
										$selected = " SELECTED";
									} else {
										$selected = "";
									}
									echo "<option value='" . $i . "'" . $selected . ">" . $i . "</option>";
								}
							} else {
								for ($i=1; $i<=7; $i++) {
									if ( $i == floor($delta/60/60/24) )	{
										$selected = " SELECTED";
									} else {
										$selected = "";
									}
									echo "<option value='" . $i . "'" . $selected . ">" . $i . "</option>";
								}
							}
							?>
							</select>
							<select id="event-duration-type" class="form-control" name="duration_type" style="width:49%;">
								<?php if ($delta < 60*60*24) { ?>
								<option value="hours" selected>Hours</option>
								<option value="days">Days</option>
								<?php } else { ?>
								<option value="hours">Hours</option>
								<option value="days" selected>Days</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
				<div class="form-group">
					<input id="event-settings-streamable" name="are_photos_streamable" type="hidden" value="<?= $event->are_photos_streamable ?>"/>
					<label for="event-settings-streamable-toggle">Automatically Add Guest Photos to Stream</label>
					<div class="form-field_hint">Should photos uploaded by guests automatically be available in the stream?</div>
					<input id="event-settings-streamable-toggle" type="checkbox" data-on="primary" data-off="danger" data-on-label="Yes" data-off-label="No" <?php echo ($event->are_photos_streamable) ? 'checked' : ''; ?>>
				</div>
				<div class="form-group">
					<input id="event-settings-public" name="public" type="hidden" value="<?= $event->is_public ?>"/>
					<label for="event-settings-public-toggle">Public Event</label>
					<div class="form-field_hint">Public events allow anyone to view and upload photos to the album. Private events are only viewable by guests that know the event PIN.</div>
					<input id="event-settings-public-toggle" type="checkbox" class="make-switch" data-on="primary" data-off="primary" data-on-label="Yes" data-off-label="No" <?php echo ($event->is_public) ? 'checked' : ''; ?>>
				</div>
				<div class="form-group">
					<label for="event-settings-address">Event Location</label>
					<input type="hidden" name="address_id" value="<?= $address_pk ?>" />
					<input id="event-settings-address" class="form-control status" name="address" type="text" data-resource-uri="<?= (isset($address_pk)) ? $address_uri : '' ?>" value="<?= (isset($address_pk)) ? $address : '' ?>"/>
					<div id="map_canvas-wrap" style="display:none;">
						<div class="form-field_hint">Tip: Drag the pin to your event address.</div>
						<div id="map_canvas" style="width:435px; height:280px;"></div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary save" onclick="return sendForm(this, settingsSuccess, settingsError, settingsBeforeSubmit);">Save</button>
					<span id="settings-save-spinner" class="spinner-wrap hide" data-color="#366993"></span>
				</div>
			</form>
			<?php } ?>
			<ul id="event-social">
				<li><span class='st_twitter_hcount' displayText='Tweet' onclick="ga('send', 'event', 'Share', 'Twitter', 'Event'); _gaq.push(['_trackEvent', 'Share', 'Twitter', 'Event']);" st_via='GetSnapable' st_url="https://snapable.com/event/<?= $event->url ?>" st_title="<?= "Follow the photos on " . date("D M j", $event->start_epoch) . " at " .  date("g:i a", $event->start_epoch) . " for " . $event->title ?>"></span></li>
				<li><span class='st_facebook_hcount' displayText='Facebook' onclick="ga('send', 'event', 'Share', 'Facebook', 'Event'); _gaq.push(['_trackEvent', 'Share', 'Facebook', 'Event']);" st_url="https://snapable.com/event/<?= $event->url ?>" st_title="<?= "Follow the photos on " . date("D M j", $event->start_epoch) . " at " .  date("g:i a", $event->start_epoch) . " for " . $event->title ?>"></span></li>
				<li><span class='st_pinterest_hcount' displayText='Pinterest' onclick="ga('send', 'event', 'Share', 'Pinterest', 'Event'); _gaq.push(['_trackEvent', 'Share', 'Pinterest', 'Event']);" st_url="https://snapable.com/event/<?= $event->url ?>" st_image="<?= 'https://snapable.com/p/get_event/'.$event_pk.'/crop' ?>"></span></li>
				<li><span class='st_googleplus_hcount' displayText='Google+' onclick="ga('send', 'event', 'Share', 'Google+', 'Event'); _gaq.push(['_trackEvent', 'Share', 'Google+', 'Event']);" st_url="https://snapable.com/event/<?= $event->url ?>" st_title="<?= "Follow the photos on " . date("D M j", $event->start_epoch) . " at " .  date("g:i a", $event->start_epoch) . " for " . $event->title ?>"></span></li>
			</ul>
			<ul id="event-nav">
				<li><span class="down">Photostream</span></li>
				<?php if ( $ownerLoggedin || $guestLoggedin ) { ?>
					<li><a id="uploadBTN" href="#" onclick="ga('send', 'event', 'Navigation', 'Upload');">Submit Photo</a></li>
				<?php } else { ?>
					<li><a id="uploadBTN" href="/event/<?= $url ?>/guest_signin?upload-photo=1" data-signin="true" onclick="ga('send', 'event', 'Navigation', 'Upload');">Submit Photo</a></li>
				<?php } ?>
				<?php if ( $ownerLoggedin ) { ?>
				<li><a id="guestBTN" href="#guest" onclick="ga('send', 'event', 'Navigation', 'Invites');">Invite Guests</a></li>
				<li><a id="tableBTN" href="#tablecards" onclick="ga('send', 'event', 'Navigation', 'Table_Cards');">Table Cards</a></li>
				<li><a id="event-nav-contact" href="#nav-contact" onclick="ga('send', 'event', 'Navigation', 'Contact');">Contact</a></li>
                <li><a href="/event/<?= $url ?>/slides" target="_new" onclick="ga('send', 'event', 'Navigation', 'Slides');">Live Slideshow<span class="badge" style="margin-left:3px;">Beta</span></a></li>
				<?php } ?>
			</ul>
		</div>

		<div class="col-lg-2">
			<div id="event-pin" class="col-lg-2 panel panel-default" <?php echo ($event->is_public) ? 'style="display:none;"':''; ?>>
				<div class="panel-body text-center">
					<div class="small">Event PIN:</div>
					<div class="large"><?= $event->pin ?></div>
				</div>
			</div>
		</div>

		<div class="col-lg-2">
			<a id="appstore" href="http://itunes.com/apps/snapable"><img alt="Available on the App Store" src="/assets/home/img/jan2013/appstore.png" width="161" height="56" onclick="ga('send', 'event', 'Downloads', 'App Store', 'iOS');" /></a>
			<a id="playstore" href="https://play.google.com/store/apps/details?id=ca.hashbrown.snapable"><img alt="Android app on Google Play" src="/assets/img/google-play-badge.png" width="161" height="56" onclick="ga('send', 'event', 'Downloads', 'App Store', 'Android');" /></a>
		</div>
	</div><!-- /row -->
</div>

<div id="uploadArea" class="row mustache-box hide slidContent">
	<div class="hint">
		Photos must be in jpeg format and a maximum of 10 MB. If your photos are rather large,
		please be patient! It might take a few minutes :)
	</div>
	<form role="form" method="POST" action="/upload" class="form-horizontal" style="width:500px;margin:0 auto;">
		<input type="hidden" name="event" value="<?php echo $event->resource_uri; ?>" />
		<?php if(isset($guest_uri)) { ?>
		<input type="hidden" name="guest" value="<?php echo $guest_uri; ?>" />
		<?php } ?>
		<fieldset>
			<div class="form-group">
				<label class="control-label" for="upload-caption">Caption</label>
				<input id="upload-caption" class="form-control" type="text" name="caption" />
			</div>
			<div class="form-group">
				<label class="control-label" for="upload-file" style="padding-top:0;">File</label>
				<input id="upload-file" class="form-control" type="file" name="file_element" required />
			</div>
			<button type="submit" id="photo-upload-btn" class="btn btn-primary btn-lg" onclick="return sendForm(this, uploadSuccess, uploadError, uploadBeforeSubmit);">Upload</button>
			<span id="photo-upload-spinner" class="spinner-wrap hide" data-color="#366993"></span>
		</fieldset>
	</form>
</div>

<?php if ( $ownerLoggedin ): ?>
<div id="contact" class="row mustache-box hide slidContent">
	<div class="section">
        <form role="form" id="questionForm" method="POST" action="/ajax/send_email" data-validate="parsley">
            <input type="hidden" name="from" value="<?= $owner_email ?>" />
            <input type="hidden" name="subject" value="Message From Customer" />
            <h3>Got a question? We're happy to answer it</h3>
            <p>Your question may already be answered! Make sure to checkout our <a href="/site/faq">FAQ</a> page.</p>

            <div class="form-group">
            	<textarea class="form-control message" name="message" rows="6" data-required="true" placeholder="Enter a question, comment or message..."></textarea>
        	</div>
        	<div class="form-group">
            	<button type="submit" class="btn btn-primary" onclick="return sendForm(this, contactSuccess, contactError);">Send</button>
        	</div>
        </form>
    </div>
    <div class="section" style="margin-top:30px;">
    	<h4>Connect</h4>
        <ul class="connect">
            <li><a class="twitter" href="http://twitter.com/getsnapable" target="_blank">Twitter</a></li>
            <li><a class="facebook" href="http://facebook.com/snapable" target="_blank">Facebook</a></li>
            <li><a class="pinterest" href="http://pinterest.com/snapable/" target="_blank">Pinterest</a></li>
        </ul>
    </div>
</div>
<?php endif; ?>

<?php if ( $ownerLoggedin ): ?>
<div id="guest" class="row mustache-box slidContent"></div>
<?php endif; ?>

<?php if ( $ownerLoggedin ): ?>
<div id="tablecards" class="row mustache-box hide slidContent" data-url="<?php echo $url; ?>">
	<h3>Table Cards</h3>

	<img src="/assets/img/tablecard.png" alt="Table card sample" />
	<p>
		Make sure your guests know about Snapable! In addition to inviting them before the event, you can also use our custom event cards at the event.
	</p>
	<p>
		We recommend printing on a heavy paper (98 Bright, 100-lb). The PDF comes with 4 cards on a
		sheet (US Letter) to minimize printing costs (just cut them in quarters after printing).
	</p>
	<br>
	<a class="btn btn-primary btn-lg download" href="/pdf/download/<?php echo $url; ?>">Download Your Table Cards</a>
</div>
<?php endif; ?>
</div>
</div><!-- /row -->

<div class="row">
	<div id="photo-preview-modal" class="modal fade bs-modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
	<div id="photoArea" class="col-lg-12">
	</div>
</div>

<div class="row loadMoreWrap hide">
	<div class="col-lg-2 col-lg-push-5">
		<button class="btn btn-primary btn-lg loadMore"><span class="glyphicon glyphicon-plus"></span> Load More</button>
	</div>
</div>

</div><!-- /container -->
