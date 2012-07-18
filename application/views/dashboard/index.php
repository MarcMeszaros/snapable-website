<h1>It's never been easier to see every moment of your big day.</h1>
<h2>So what's next?</h2>
	
	
<div id="dash-list">	
	
	<h3>Here's a few things you can do to help make your wedding day photos a success.</h3>
	<h4>Don't worry, you can come back later and complete these!</h4>
	
	<ul>
		<li>
			<div class="dash-list-title">Before the event</div>
			<div class="dash-list-text">
				
				<h5>Share</h5>
				<p>Your event URL is: <input readonly id="event-url" type="text" value="http://snapable.com/event/<?= $url ?>" />
					<br />Share on <a href="#">Facebook</a>, <a href="#">Twitter</a> or copy/paste it to share elsewhere.</p>
				
				<hr />
				
				<h5>Notify</h5> 
				<p>Add guests &amp; let them know to get the Snapable app</p>
				
				<a href="/event/setup/<?= $url ?>/guests" id="add-guests" class="button" rel="facebox">Add Guests &rarr;</a>
				
				<hr />
				
				<h5>Add Photos</h5>
				
				<p>Upload photos and setup albums leading up to the big day. <br /><em>This can also be done at any time from your event page.</em></p>
				
				<a href="/event/setup/<?= $url ?>/upload" id="upload-photos" class="button" rel="facebox">Upload Photos &rarr;</a>
				
			</div>
		</li>
		
		<li>
			<div class="dash-list-title">During the event</div>
			<div class="dash-list-text">
				<h5>Encourage Guests to Participate</h5>
				<p>Let guests know how to take part via customized table cards</p>
				<a href="/event/setup/<?= $url ?>/cards" id="get-table-card" class="button" rel="facebox">Get Table Card &rarr;</a>
				
				<hr />
				
				<h5>Setup a Slideshow</h5>
				
				<p>Create an album to be used as a slideshow at the reception.</p>
				
				<a href="/event/setup/<?= $url ?>/slideshow" id="create-slideshow" class="button" rel="facebox">Create slideshow &rarr;</a>
			</div>
		</li>
		
		<li>
			<div class="dash-list-title">After the event</div>
			<div class="dash-list-text">
				<h5>Create Photo Albums</h5>
				<p>Make albums  of your favorite photos</p>
				
				<a href="/event/setup/<?= $url ?>/albums" id="make-albums" class="button" rel="facebox">Make Albums &rarr;</a>
				
				<hr />
				
				<h5>Get prints</h5>
				<p>Order prints of your favorites (your package includes 100)</p>
				
				<a href="/event/setup/<?= $url ?>/prints" id="order-prints" class="button" rel="facebox">Order Prints &rarr;</a>
				
				<hr />
				
				<h5>Remind your guests</h5>
				<p>Let your guests know to come view the photos (and allow them to order prints too!)</p>
				
				<a href="/event/setup/<?= $url ?>/reminders" id="send-reminders" class="button" rel="facebox">Send Reminders &rarr;</a>
			</div>
		</li>
	</ul>
	
	<a id="event-link" href="/event/123">Go To Your Event Page &rarr;</a>
	
</div>