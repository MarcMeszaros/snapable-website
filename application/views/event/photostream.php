<script type="text/javascript">
var eventID = "<?= $eventDeets->resource_uri ?>";
var guestID = "/private_v1/guest/1/";
var typeID = "/private_v1/type/1/";
var photos = <?= $eventDeets->photos ?>
</script>

<div id="event-top">

	<div id="event-cover-wrap"><img id="event-cover-image" src="/assets/img/FPO/cover-image.jpg" /></div>
	<div id="event-title-wrap">
		<h1><?= $eventDeets->display_timedate ?></h1>
		<h2><?= $eventDeets->title ?></h2>
		<ul id="event-nav">
			<li><span>Photostream</span></li>
			<li><a id="uploadBTN" href="#">Upload Photos</a></li>
			<?php if ( $eventDeets->photos > 0 )
			{
				echo '<li><a href="/event/' . $eventDeets->url . '/slideshow">Slideshow</a></li>';
			} ?>
			<li><a href="#guest" id="guestBTN">Invite Guests</a></li>
			<li>
				<a id="event-nav-share" href="#">Share</a>
				<div id="event-nav-menu-share" class="event-nav-menu">
					<a class="photo-share-twitter" href="#">Tweet</a> 
					<a class="photo-share-facebook" href="#">Share</a> 
					<a class="photo-share-email" href="#">Email</a>
				</div>
			</li>
			<li>
				<a id="event-nav-privacy" href="#">Privacy</a>
				<div id="event-nav-menu-privacy" class="event-nav-menu">
					
					<p>Select the groups you'd like to be able to see photos that are uploaded.</p>
					
					<ul>
						<li><input type="checkbox" name="privacy-setting" value="1" /> Only You</li>
						<li><input type="checkbox" name="privacy-setting" value="2" /> Bride/Groom</li>
						<li><input type="checkbox" name="privacy-setting" value="3" /> Wedding party</li>
					</ul>
					<ul>
						<li><input type="checkbox" name="privacy-setting" value="4" /> Family</li>
						<li><input type="checkbox" name="privacy-setting" value="5" /> All Guests</li>
						<li><input type="checkbox" name="privacy-setting" value="6" CHECKED /> Public</li>
					</ul>
					<div class="clearit">&nbsp;</div>
					<input type="button" value="Save" />
				</div>
			</li>
		</ul>
	</div>
	
	<div id="checkout-buttons">
		<div id="in-cart">
			<div id="in-cart-number">0</div>
			Photos in cart
		</div>
		<a id="checkout" href="#">Checkout</a>
	</div>
	
</div>

<div id="uploadArea"></div>
<div id="uploadedArea"></div>
<div class="clearit">&nbsp;</div>

<div id="guest"></div>

<div id="photoArea"></div>

<div class="clearit">&nbsp;</div>