<?php
	// get session vars
	$session_owner = $this->session->userdata('logged_in');
	$session_guest = $this->session->userdata('guest_login');

	// check if user is logged in		
	$ownerLoggedin = ( $session_owner['loggedin'] == true ) ? true : false;
	$guestLoggedin = ( $session_guest['loggedin'] == true ) ? true : false;
	
	if ( (isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user) || $guestLoggedin ) 
	{
		$show_upload = true;
	} else {
		$show_upload = false;
	}

	$guestID = '';
	if (isset($session_owner['guest_id'])) {
		$guestID = $session_owner['guest_id'];
	} 
	else if (isset($session_guest['id'])) {
		$guestID = $session_guest['id'];
	}
?>

<script type="text/javascript">
var eventID = "<?= $eventDeets->resource_uri ?>";
var guestID = "/private_v1/guest/<?= $guestID ?>/";
var typeID = "/private_v1/type/<?php echo (isset($session_guest['type']))? $session_guest['type']: '1'; ?>/";
var photo_count = <?= $eventDeets->photos ?>;
var owner = <?= (isset($owner) && $owner == 1) ? 'true' : 'false' ?>;
</script>

<div id="event-top">

	<div id="event-cover-wrap"><img id="event-cover-image" src="/assets/img/FPO/cover-image.jpg" /></div>
	<div id="event-title-wrap">
		<h1><span id="event-timestamp-start"><?= $eventDeets->human_start ?></span> to <span id="event-timestamp-end"><?= $eventDeets->human_end ?></span></h1>
		<h2 id="event-title" class="<?php echo ($ownerLoggedin)? 'edit': '';?>"><?= $eventDeets->title ?></h2>
		<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
		<div id="event-settings">
			<h3>Event Details &amp; Settings</h3>
			<input id="event-settings-lat" name="lat" type="hidden"/>
			<input id="event-settings-lng" name="lng" type="hidden"/>

			<label for="event-settings-title">Event Title</label><br/>
			<input id="event-settings-title" name="title" type="text" value="<?php echo $eventDeets->title; ?>"/><br/>
			<label for="event-settings-url">Event URL</label><br/>
			<input id="event-settings-url" name="url" type="text" data-orig="<?php echo $eventDeets->url; ?>" value="<?php echo $eventDeets->url; ?>"/><span id="event-settings-url-status" class="status">&nbsp;</span><br/>
			<label for="event-settings-location">Event Location</label><br/>
			<input id="event-settings-location" class="" name="location" type="text" disabled="disabled" value="<?php echo $eventDeets->addresses[0]->{'address'}; ?>"/><br/>
			<div id="map_canvas"></div>
			<div class="clearit">&nbsp;</div>
			<div id="event-settings-save-wrap">
				<input type="button" value="Save" />
			</div>
		</div>
		<?php } ?>
		<ul id="event-nav">

			<li><span>Photostream</span></li>
			<li <?php echo ($eventDeets->privacy == 6) ? 'style="display:none;"' : ''; ?>><a id="uploadBTN" href="#">Upload Photos</a></li>
			<?php if ( $eventDeets->photos > 0 )
			{
				//echo '<li><a href="/event/' . $eventDeets->url . '/slideshow">Slideshow</a></li>';
			} ?>

			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ) { ?>
			<li><a href="#guest" id="guestBTN">Invite Guests</a></li>
			<?php } ?>
			
			<?php if ($eventDeets->privacy == 6): ?>
			<!--<li>
				<a id="event-nav-share" href="#">Share</a>
				<div id="event-nav-menu-share" class="event-nav-menu">
					<a class="photo-share-twitter" href="#">Tweet</a> 
					<a class="photo-share-facebook" href="#">Share</a> 
					<a class="photo-share-email" href="#">Email</a>
				</div>
			</li>-->
			<?php endif; ?>

			<?php if ( isset($logged_in_user_resource_uri) && $logged_in_user_resource_uri == $eventDeets->user ): ?>
			<li>
				<a id="event-nav-privacy" href="#">Privacy</a>
				<div id="event-nav-menu-privacy" class="event-nav-menu">
					
					<p>Select if photos are private and viewable by guests only or if all photos are public.</p>
					
					<ul>
						<li><input type="radio" name="privacy-setting" value="0" <?php echo ($eventDeets->privacy < 6) ? 'checked="checked"':''; ?>/> Private</li>
					</ul>
					<ul>
						<li><input type="radio" name="privacy-setting" value="1" <?php echo ($eventDeets->privacy == 6) ? 'checked="checked"':''; ?>/> Public</li>
					</ul>
					<div class="clearit">&nbsp;</div>
					<div id='privacySaveWrap'><input type="button" value="Save" /></div>
				</div>
			</li>
		<?php endif; ?>
		</ul>
	</div>

	<div id="event-pin" <?php echo ($eventDeets->privacy == 6) ? 'style="display:none;"':''; ?>>
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

<div id="uploadArea"></div>
<div id="uploadedArea"></div>
<div class="clearit">&nbsp;</div>

<div id="guest"></div>

<div id="photoArea"></div>

<div class="clearit">&nbsp;</div>