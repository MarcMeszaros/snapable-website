<div id="event-top">

	<?php
		$eid = explode('/', $eventDeets->resource_uri);
	?>
	<img id="event-cover-image" src="/p/get_event/<?php echo $eid[3]; ?>/150x150" />
	<div id="event-title-wrap">
		<h2 id="event-title" class="<?php echo ($ownerLoggedin)? 'edit': '';?>"><?= $eventDeets->title ?></h2>
		<h1 id="event-address"><?= (!$eventDeets->public && isset($eventDeets->addresses[0]->{'address'})) ? $eventDeets->addresses[0]->{'address'} : '&nbsp;' ?></h1>
		<h1 id="event-timestamp-start"><?= $eventDeets->human_start ?></span> to <span id="event-timestamp-end"><?= $eventDeets->human_end ?></h1>
		<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
		<form id="event-settings" class="form">
			<h3>Edit Your Event Details</h3>
			<input id="event-settings-lat" name="lat" type="hidden" value="<?= (isset($eventDeets->addresses[0])) ? $eventDeets->addresses[0]->{'lat'} : '0' ?>"/>
			<input id="event-settings-lng" name="lng" type="hidden" value="<?= (isset($eventDeets->addresses[0])) ? $eventDeets->addresses[0]->{'lng'} : '0' ?>"/>
			<input id="event-settings-timezone" name="tz_offset" type="hidden" value="<?php echo $eventDeets->tz_offset; ?>"/>
			<input id="event-settings-start" name="start" type="hidden" value="<?php echo $eventDeets->start_epoch; ?>"/>

			<div class="small-field">
				<label for="event-settings-title">Event Title</label>
				<input id="event-settings-title" name="title" type="text" value="<?php echo $eventDeets->title; ?>"/>
			</div>
			<div class="small-field">
				<label for="event-settings-url">Event URL</label>
				<input id="event-settings-url" name="url" type="text" data-orig="<?php echo $eventDeets->url; ?>" value="<?php echo $eventDeets->url; ?>"/><span id="event-settings-url-status" class="status">&nbsp;</span>
			</div>
			<div class="small-field">
				<div class="small-field-inner">
					<label for="event-start-date">Date</label>
					<input type="text" id="event-start-date" class="longer datepicker" name="event[start_date]" value="<?= date("M j, Y", $eventDeets->start_epoch + ($eventDeets->tz_offset * 60)) ?>">
				</div>
				<div class="small-field-inner">
					<label for="event-start-time">Time</label>
					<input id="event-start-time" name="event[start_time]" type="text" value="<?= date("h:i A", $eventDeets->start_epoch + ($eventDeets->tz_offset * 60)) ?>">
				</div>
				<div class="small-field-inner-wide">
					<label for="event-duration-num">Duration</label>
					<select id="event-duration-num" name="event[duration_num]">
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
					<select id="event-duration-type" name="event[duration_type]">
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
			<div class="small-field">
				<label for="event-settings-address">Event Location</label>
				<input id="event-settings-address" name="address" type="text" data-resource-uri="<?= (isset($eventDeets->addresses[0]->{'resource_uri'})) ? $eventDeets->addresses[0]->{'resource_uri'} : '' ?>" value="<?= (isset($eventDeets->addresses[0]->{'address'})) ? $eventDeets->addresses[0]->{'address'} : '' ?>"/><span class="help tooltip"></span><span id="event-settings-address-status" class="status">&nbsp;</span>
				<div id="map_canvas-wrap" style="display:none;">
					<div class="form-field_hint">Tip: Drag the pin to your event address.</div>
					<div id="map_canvas" style="width:412px; height:280px;"></div>
				</div>
			</div>
			<div class="small-field">
				<div id="event-settings-save-wrap">
					<input type="button" class="btn btn-default cancel" value="Cancel" />
					<input type="button" class="btn btn-primary save" value="Save" />
				</div>
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

			<li><span>Photostream</span></li>
			<?php if ( (isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user) || (isset($guestLoggedin) && $guestLoggedin == true) ) { ?>
				<li><a id="uploadBTN" href="#">Submit Photo</a></li>
			<?php } else { ?>
				<li><a id="uploadBTN" href="/event/<?= $url ?>/guest_signin?upload-photo=1" data-signin="true">Submit Photo</a></li>
			<?php } ?>
			<?php if ( $eventDeets->photos > 0 )
			{
				//echo '<li><a href="/event/' . $eventDeets->url . '/slideshow">Slideshow</a></li>';
			} ?>

			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
			<li><a href="#guest" id="guestBTN">Invite Guests</a></li>
			<li><a href="#tablecards" id="tableBTN">Table Cards</a></li>
			<li><a id="event-nav-contact" href="#nav-contact">Contact</a></li>
			<li>
				<a id="event-nav-privacy" href="#">Privacy</a>
				<div id="event-nav-menu-privacy" class="event-nav-menu">
					<form method="post" action="/event/privacy" enctype="multipart/form-data" class="form-horizontal">
						<input type="hidden" name="event" value="<?php echo $eventDeets->resource_uri; ?>" />
						<p>Choose private if you prefer photos are only viewed by guests. Public events will be visible to anyone who visits your album.</p>
						<ul>
							<li><input type="radio" name="privacy-setting" value="0" <?php echo (!$eventDeets->public) ? 'checked="checked"':''; ?>/> Private</li>
							<li><input type="radio" name="privacy-setting" value="1" <?php echo ($eventDeets->public) ? 'checked="checked"':''; ?>/> Public</li>
						</ul>
						<div class="clearit">&nbsp;</div>
						<div id='privacySaveWrap'><input type="submit" class="btn btn-primary" value="Save" /></div>
					</form>
				</div>
			</li>
			<?php } ?>
		</ul>

	</div>

	<div id="event-pin" <?php echo ($eventDeets->public) ? 'style="display:none;"':''; ?>>
		Event PIN:
		<div><?= $eventDeets->pin ?></div>
	</div>
</div>

<div id="uploadArea" class="mustache-box hide slidContent">
	<div class="hint">
		Photos must be in jpeg format and a maximum of 10 MB. If your photos are rather large, 
		please be patient! It might take a few minutes :)
	</div>
	<form method="post" action="/upload" enctype="multipart/form-data" class="form-horizontal" style="width:500px;margin:0 auto;">
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
			<input type="submit" id="photo-upload-btn" value="Upload" />
			<span id="photo-upload-spinner" class="spinner-wrap hide" data-color="#366993"></span>
		</fieldset>
	</form>
</div>

<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
<div id="contact" class="mustache-box hide slidContent">
	<div class="section">
        <form id="questionForm" action="/ajax/send_email" method="post">
            <input type="hidden" name="from" value="<?= $owner_email ?>" />
            <input type="hidden" name="subject" value="Message From Customer" />
            <h3>Got a question? We're happy to answer it</h3>
            <p>Your question may already be answered! Make sure to checkout our <a href="/site/faq">FAQ</a> page.</p>

            <textarea class="message" name="message">Enter a question, comment or message...</textarea>
            <input type="submit" name="submit" value="Send" />
        </form>
    </div>
    <div class="section" style="margin-top:50px;">
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
<div id="guest" class="mustache-box slidContent"></div>
<?php endif; ?>

<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
<div id="tablecards" class="mustache-box hide slidContent" data-url="<?php echo $url; ?>">
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
	<a class="download" href="/pdf/download/<?php echo $url; ?>">Download Your Table Cards</a>
</div>
<?php endif; ?>

<div id="photoArea"></div>

<div class="clearit">&nbsp;</div>