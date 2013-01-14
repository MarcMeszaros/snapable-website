<div id="event-top">

	<div id="event-cover-wrap"><img id="event-cover-image" src="/assets/img/FPO/cover-image.jpg" /></div>
	<div id="event-title-wrap">
		<h2 id="event-title" class="<?php echo ($ownerLoggedin)? 'edit': '';?>"><?= $eventDeets->title ?></h2>
		<h1 id="event-address"><?php echo (!$eventDeets->public) ? $eventDeets->addresses[0]->{'address'} : '&nbsp;'; ?></h1>
		<h1 id="event-timestamp-start"><?= $eventDeets->human_start ?></span> to <span id="event-timestamp-end"><?= $eventDeets->human_end ?></h1>
		<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
		<form id="event-settings">
			<h3>Edit Your Event Details</h3>
			<input id="event-settings-lat" name="lat" type="hidden" value="<?php echo $eventDeets->addresses[0]->{'lat'}; ?>"/>
			<input id="event-settings-lng" name="lng" type="hidden" value="<?php echo $eventDeets->addresses[0]->{'lng'}; ?>"/>
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
					<input class="longer" id="event-start-date" name="event[start_date]" type="text" value="<?= date("M j, Y", $eventDeets->start_epoch + ($eventDeets->tz_offset * 60)) ?>">
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
				<input id="event-settings-address" name="address" type="text" data-resource-uri="<?php echo $eventDeets->addresses[0]->{'resource_uri'}; ?>" value="<?php echo $eventDeets->addresses[0]->{'address'}; ?>"/><span id="event-settings-address-status" class="status">&nbsp;</span>
				<div id="map_canvas-wrap" style="display:none;"><div id="map_canvas" style="width:285px; height:285px;"></div></div>
			</div>
			<div class="small-field">
				<div id="event-settings-save-wrap">
					<input type="button" class="cancel" value="Cancel" />
					<input type="button" class="save" value="Save" />
				</div>
			</div>
		</form>
		<?php } ?>
		<ul id="event-nav">

			<li><span>Photostream</span></li>
			<li><a id="uploadBTN" href="#">Add Photos</a></li>
			<?php if ( $eventDeets->photos > 0 )
			{
				//echo '<li><a href="/event/' . $eventDeets->url . '/slideshow">Slideshow</a></li>';
			} ?>

			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
			<li><a href="#guest" id="guestBTN">Invite Guests</a></li>
			<li><a href="#tablecards" id="tableBTN">Table Cards</a></li>
			<?php } ?>
			
			

			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
			<li>
				<a id="event-nav-contact" href="#nav-contact">Contact</a>
			</li>
			<?php endif; ?>
			
			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
			<li>
				<a id="event-nav-privacy" href="#">Privacy</a>
				<div id="event-nav-menu-privacy" class="event-nav-menu">
					
					<p>Choose private if you prefer photos are only viewed by guests. Public events will be visible to anyone who visits your album.</p>
					
					<ul>
						<li><input type="radio" name="privacy-setting" value="0" <?php echo (!$eventDeets->public) ? 'checked="checked"':''; ?>/> Private</li>
					</ul>
					<ul>
						<li><input type="radio" name="privacy-setting" value="1" <?php echo ($eventDeets->public) ? 'checked="checked"':''; ?>/> Public</li>
					</ul>
					<div class="clearit">&nbsp;</div>
					<div id='privacySaveWrap'><input type="button" value="Save" /></div>
				</div>
			</li>
			<?php endif; ?>
			
			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
			<li>
				<a id="event-nav-share" href="#">Share</a>
				<div id="event-nav-menu-share" class="event-nav-menu">
					<p>Share your album with friends and family.</p>
					<div class="share">
						<a class="photo-share-twitter" target="_blank" href="http://twitter.com/share?text=<?= urlencode("Follow the photos on " . date("D M j", $eventDeets->start_epoch) . " at " .  date("g:i a", $eventDeets->start_epoch) . " for " . $eventDeets->title . " with @getsnapable") ?>&url=http://snapable.com/event/<?= $eventDeets->url ?>">Tweet</a> 
						<a class="photo-share-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=http://snapable.com/event/<?= $eventDeets->url ?>">Share</a> 
						<!--<a class="photo-share-email" href="#">Email</a>-->
					</div>
				</div>
			</li>
			<?php endif; ?>
		</ul>
	</div>

	<div id="event-pin" <?php echo ($eventDeets->public) ? 'style="display:none;"':''; ?>>
		Event PIN:
		<div><?= $eventDeets->pin ?></div>
	</div>
	<!--
	<div id="checkout-buttons">
		<div id="in-cart">
			<div id="in-cart-number">0</div>
			Photos in cart
		</div>
		<a id="checkout" href="#">Checkout</a>
	</div>
	-->
</div>

<div id="uploadArea" class="slidContent"></div>
<div id="uploadedArea"></div>
<div class="clearit">&nbsp;</div>

<div id="contact" class="mustache-box slidContent"></div>
<div id="guest" class="mustache-box slidContent"></div>
<div id="tablecards" class="mustache-box slidContent"></div>

<div id="photoArea"></div>

<div class="clearit">&nbsp;</div>