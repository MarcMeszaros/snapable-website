<?php
	$eid = explode('/', $eventDeets->resource_uri);
	$aid = explode('/', $eventDeets->addresses[0]->{'resource_uri'});
?>	
<div class="container">
<div class="row">
<div class="col-lg-12">
<div id="event-top" data-event-id="<?= $eid[3] ?>" data-photo-count="<?= $eventDeets->photos ?>">
	<div class="row" style="margin-top:30px;">
		<div class="col-lg-2">
			<img id="event-cover-image" class="img-thumbnail" src="/p/get_event/<?= $eid[3] ?>/150x150" data-event-id="<?= $eid[3] ?>" />
		</div>
		<div id="event-title-wrap" class="col-lg-8">
			<h2 id="event-title"><?= $eventDeets->title ?></h2>
			<div id="event-address"><?= (!$eventDeets->public && isset($eventDeets->addresses[0]->{'address'})) ? $eventDeets->addresses[0]->{'address'} : '&nbsp;' ?></div>
			<div id="event-timestamp-start"><?= $eventDeets->human_start ?></span> to <span id="event-timestamp-end"><?= $eventDeets->human_end ?><?php if ($ownerLoggedin) { ?> &nbsp; <button id="event-settings-btn" class="btn btn-primary btn-xs" style="font-size:10px; margin-top:-2px;" onclick="$('#event-settings').slideDown();">Edit Event</button><?php } ?></div>
			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
			<form id="event-settings" role="form" method="POST" action="/ajax/put_event/<?= $eid[3] ?>">
				<h3>Edit Your Event Details</h3>
				<input id="event-settings-lat" name="lat" type="hidden" value="<?= (isset($eventDeets->addresses[0])) ? $eventDeets->addresses[0]->{'lat'} : '0' ?>"/>
				<input id="event-settings-lng" name="lng" type="hidden" value="<?= (isset($eventDeets->addresses[0])) ? $eventDeets->addresses[0]->{'lng'} : '0' ?>"/>
				<input id="event-settings-timezone" name="tz_offset" type="hidden" value="<?php echo $eventDeets->tz_offset; ?>"/>
				<input id="event-settings-start" name="start" type="hidden" value="<?php echo $eventDeets->start_epoch; ?>"/>

				<div class="form-group">
					<label for="event-settings-title">Event Title</label>
					<input id="event-settings-title" class="form-control" name="title" type="text" value="<?php echo $eventDeets->title; ?>"/>
				</div>
				<div class="form-group">
					<label for="event-settings-url">Event URL</label>
					<input id="event-settings-url" class="form-control status" name="url" type="text" data-orig="<?php echo $eventDeets->url; ?>" value="<?php echo $eventDeets->url; ?>"/>
				</div>
				<div class="form-group row">
					<div class="form-group col-sm-4">
						<label for="event-start-date">Date</label>
						<input type="text" id="event-start-date" class="form-control" name="start_date" value="<?= date("M j, Y", $eventDeets->start_epoch + ($eventDeets->tz_offset * 60)) ?>">
					</div>
					<div class="form-group col-sm-3">
						<label for="event-start-time">Time</label>
						<input type="text" id="event-start-time" class="form-control" name="start_time" value="<?= date("h:i A", $eventDeets->start_epoch + ($eventDeets->tz_offset * 60)) ?>">
					</div>
					<div class="form-group col-sm-5">
						<label for="event-duration-num">Duration</label>
						<div class="form-inline">
						<select id="event-duration-num" class="form-control" name="duration_num" style="width:49%;">
							<?php
							// get the delta (sec.)
							$delta = $eventDeets->end_epoch - $eventDeets->start_epoch;

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
					<input id="event-settings-streamable" name="are_photos_streamable" type="hidden" value="<?= $eventDeets->are_photos_streamable ?>"/>
					<label for="event-settings-streamable-toggle">Automatically Add Guest Photos to Stream</label>
					<div class="form-field_hint">Should photos uploaded by guests automatically be available in the stream?</div>
					<div>
					<div id="event-settings-streamable-toggle" class="make-switch" data-on="primary" data-off="danger" data-on-label="Yes" data-off-label="No">
    					<input type="checkbox" <?php echo ($eventDeets->are_photos_streamable) ? 'checked' : ''; ?>>
					</div>
					</div>
				</div>
				<div class="form-group">
					<input id="event-settings-public" name="public" type="hidden" value="<?= $eventDeets->public ?>"/>
					<label for="event-settings-public-toggle">Public Event</label>
					<div class="form-field_hint">Public events allow anyone to view and upload photos to the album. Private events are only viewable by guests that know the event PIN.</div>
					<div>
					<div id="event-settings-public-toggle" class="make-switch" data-on-label="Yes" data-off-label="No">
    					<input type="checkbox" <?php echo ($eventDeets->public) ? 'checked' : ''; ?>>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label for="event-settings-address">Event Location</label>
					<input type="hidden" name="address_id" value="<?= $aid[3] ?>" />
					<input id="event-settings-address" class="form-control status" name="address" type="text" data-resource-uri="<?= (isset($eventDeets->addresses[0]->{'resource_uri'})) ? $eventDeets->addresses[0]->{'resource_uri'} : '' ?>" value="<?= (isset($eventDeets->addresses[0]->{'address'})) ? $eventDeets->addresses[0]->{'address'} : '' ?>"/>
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
				<li><span class='st_twitter_hcount' displayText='Tweet' onclick="_gaq.push(['_trackEvent', 'Share', 'Twitter', 'Event']);" st_via='GetSnapable' st_url="https://snapable.com/event/<?= $eventDeets->url ?>" st_title="<?= "Follow the photos on " . date("D M j", $eventDeets->start_epoch) . " at " .  date("g:i a", $eventDeets->start_epoch) . " for " . $eventDeets->title ?>"></span></li>
				<li><span class='st_facebook_hcount' displayText='Facebook' onclick="_gaq.push(['_trackEvent', 'Share', 'Facebook', 'Event']);" st_url="https://snapable.com/event/<?= $eventDeets->url ?>" st_title="<?= "Follow the photos on " . date("D M j", $eventDeets->start_epoch) . " at " .  date("g:i a", $eventDeets->start_epoch) . " for " . $eventDeets->title ?>"></span></li>
				<li><span class='st_pinterest_hcount' displayText='Pinterest' onclick="_gaq.push(['_trackEvent', 'Share', 'Pinterest', 'Event']);" st_url="https://snapable.com/event/<?= $eventDeets->url ?>" st_image="<?= 'https://snapable.com/p/get_event/'.$eid[3].'/crop' ?>"></span></li>
				<li><span class='st_googleplus_hcount' displayText='Google+' onclick="_gaq.push(['_trackEvent', 'Share', 'Google+', 'Event']);" st_url="https://snapable.com/event/<?= $eventDeets->url ?>" st_title="<?= "Follow the photos on " . date("D M j", $eventDeets->start_epoch) . " at " .  date("g:i a", $eventDeets->start_epoch) . " for " . $eventDeets->title ?>"></span></li>
				<li><span class='st_email_hcount' displayText='Email' onclick="_gaq.push(['_trackEvent', 'Share', 'Email', 'Event']);" st_url="https://snapable.com/event/<?= $eventDeets->url ?>" st_title="<?= "Follow the photos on " . date("D M j", $eventDeets->start_epoch) . " at " .  date("g:i a", $eventDeets->start_epoch) . " for " . $eventDeets->title ?>"></span></li>
			</ul>
			<ul id="event-nav">
				<li><span class="down">Photostream</span></li>
				<?php if ( (isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user) || (isset($guestLoggedin) && $guestLoggedin == true) ) { ?>
					<li><a id="uploadBTN" href="#">Submit Photo</a></li>
				<?php } else { ?>
					<li><a id="uploadBTN" href="/event/<?= $url ?>/guest_signin?upload-photo=1" data-signin="true">Submit Photo</a></li>
				<?php } ?>
				<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
				<li><a href="#guest" id="guestBTN">Invite Guests</a></li>
				<li><a href="#tablecards" id="tableBTN">Table Cards</a></li>
				<li><a id="event-nav-contact" href="#nav-contact">Contact</a></li>
				<?php } ?>
			</ul>
		</div>

		<div class="col-lg-2">
			<div id="event-pin" class="col-lg-2 panel panel-default" <?php echo ($eventDeets->public) ? 'style="display:none;"':''; ?>>
				<div class="panel-body text-center">
					<div class="small">Event PIN:</div>
					<div class="large"><?= $eventDeets->pin ?></div>
				</div>
			</div>
		</div>
	</div><!-- /row -->
</div>

<div id="uploadArea" class="row mustache-box hide slidContent">
	<div class="hint">
		Photos must be in jpeg format and a maximum of 10 MB. If your photos are rather large, 
		please be patient! It might take a few minutes :)
	</div>
	<form role="form" method="POST" action="/upload" class="form-horizontal" style="width:500px;margin:0 auto;">
		<input type="hidden" name="event" value="<?php echo $eventDeets->resource_uri; ?>" />
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

<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
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

<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
<div id="guest" class="row mustache-box slidContent"></div>
<?php endif; ?>

<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
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
	<div id="photo-preview-modal" class="modal fade">
        <div class="modal-dialog">
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