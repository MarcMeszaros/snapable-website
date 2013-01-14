<div id="guestSigninTop"></div>

<form id="signinWrap" name="signin" action="/event/guests/<?= $eventDeets->url ?>/validate" method="post">
	
	<h1><?= $eventDeets->title ?> Guest Sign in</h1>
	<h2><?= $eventDeets->display_timedate ?></h2>
	<h2>Are you the organizer? <a href="/account/signin?redirect=<?php echo '/'.$this->uri->uri_string(); ?>">Login here</a>.</h2>
	
	<hr />
	
	<?php 
	if ( isset($error) && $error == true ) {
		echo "<div id='error'>Incorrect password or email address.</div>";	
	} 
	?>
	
	<label for="email">
		Email Address
		<div>This doesn't look like a proper email address</div>
	</label>
	<input type="text" name="email" />
	
	<?php if(!$eventDeets->public) {?>
		<label for="pin">
			Event PIN
			<div class="error">You need to provide a pin to sign in</div>
		</label>
		<input id="pinInput" type="text" name="pin" />
	<?php } ?>
	<hr />
	
	<input type="submit" name="submit" value="Sign in" />
	
	<h2 id="guestH2">
		<?php if(!$eventDeets->public) {?>Don't know the event PIN?<br /><?php } ?><a href="/event/message/organizer">Message the event organizer</a>.</h2>
	
</form>