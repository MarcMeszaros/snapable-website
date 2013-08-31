<div class="container">
	
	<form role="form" id="signinWrap" class="col-lg-6 col-lg-push-3" name="signin" action="/event/guests/<?= $eventDeets->url ?>/validate" method="post" data-validate="parsley" novalidate>
		<?php 
		if ( isset($upload_photo)) {
			echo '<input type="hidden" name="upload_photo" value="1" />';	
		} 
		?>
		
		<h1>Sign in to submit your photo!</h1>
		<h2><?= $eventDeets->display_timedate ?></h2>
		<h2>Are you the organizer? <a href="/account/signin?redirect=<?php echo '/'.$this->uri->uri_string(); ?>">Login here</a>.</h2>
		
		<hr class="dotted" />
		
		<?php 
		if ( isset($error) && $error == true ) {
			echo "<div id='error'>Incorrect password or email address.</div>";	
		} 
		?>
		
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" class="form-control text-center" name="name" />
		</div>

		<div class="form-group">
			<label for="email">Email Address</label>
			<input type="email" class="form-control text-center" data-type="email" name="email" required="required" data-required="true" />
		</div>

		<?php if(!$eventDeets->public) {?>
			<div class="form-group">
				<label for="pin">Event PIN</label>
				<input id="pinInput" class="form-control text-center" type="text" name="pin" required="required" data-required="true" data-required-message="You need to provide a pin to sign in." />
			</div>
		<?php } ?>
		<hr class="dotted" />
		
		<div class="form-group">
			<button type="submit" name="submit" class="form-control btn btn-primary">Sign in</button>
		</div>

		<!-- <h2 id="guestH2"> -->
		<?php if(!$eventDeets->public) { ?>
		<!-- Don't know the event PIN?<br /> -->
		<?php } ?>
		<!-- <a href="/event/message/organizer">Message the event organizer</a>.</h2> -->
		
	</form>
</div>