	<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyAofUaaxFh5DUuOdZHmoWETZNAzP1QEya0&sensor=false"></script>
	<div id="notification"></div>

	<div id="restOfPageWrap">
		
		<div id="topOthaPage">
			<img src="/assets/img/snapable-sm.png" width="200" height="54" alt="Snapable" border="0" />
			<div>Every Moment, Captured.</div>
		</div>
		
		<form name="create_event" method="post" action="/signup/complete">
		
			<input type="hidden" id="lat" name="event[lat]" value="0" />
			<input type="hidden" id="lng" name="event[lng]" value="0" />
			<input type="hidden" id="timezone" name="event[timezone]" value="0" />

			<section id="event-details" class="form-fields">
				
				<h1>Sign-up</h1>
				<h2>It’ll only take a minute to setup your event.</h2>
				
				<h3>Event Details</h3>
				
				<div class="form-box">
					
					<div class="form-field field-separated">
						<label for="event_title">Title</label>
						<input id="event_title" name="event[title]" size="40" type="text"> 
						<div class="field-error" id="event_title_error">You must provide a title for your event.</div>
					</div>
							
					<div class="small-field">
						<div class="small-field-inner">
							<label for="event-start-date">Date</label>
							<input class="longer" id="event-start-date" name="event[start_date]" type="text" value="<?= date("M j, Y", time()) ?>">
						</div>
						<div class="small-field-inner">
							<label for="event-start-time">Time</label>
							<input id="event-start-time" name="event[start_time]" type="text" value="<?= date("h:00 A", time() + 3600) ?>">
						</div>
						<div class="small-field-inner-wide">
							<label for="event-duration-num">Duration</label>
							<select id="event-duration-num" name="event[duration_num]">
							<?php
							for ($i=1; $i<=23; $i++)
							{
								if ( $i == 12 )
								{
									$selected = " SELECTED";
								} else {
									$selected = "";
								}
								echo "<option value='" . $i . "'" . $selected . ">" . $i . "</option>";
							}
							?>
							</select> 
							<select id="event-duration-type" name="event[duration_type]"><option value="hours">Hours</option><option value="days">Days</option></select>
						</div>
					</div>
					
					<div class="form-field">
						<label for="event_location">Location</label>
						<span id="event_location_status">&nbsp;</span>
						<input id="event_location" name="event[location]" size="40" type="text"> 
						<div class="field-error" id="event_location_error">You must provide a location for your event.</div>
						<div class="form-field_hint">Example: 255 Bremner Blvd, Toronto, Canada, M5V 3M9</div>
					</div>
					
					<div id="map_canvas_container" class="form-field" style="display:none;">
						<div id="map_canvas" style="width: 350px; height: 300px;"></div>
						<p style="width:350px; margin-top:10px;">Here's where we've got your event, if we're wrong you can drag the location marker to the correct location.</p>
					</div>
					
					<hr />
					
					<div class="form-field">
						<label for="event_url">Pick a custom URL</label>
						<span class="info left">snapable.com/event/</span><input id="event_url" name="event[url]" type="text" />
						<span id="event_url_status">&nbsp;</span>
						<div class="clearit">&nbsp;</div>
						<div class="field-error" id="event_url_error">This URL is already in use.</div>
						<div class="form-field_hint">Example: http://snapable.com/event/<b>my-big-fat-greek-wedding</div>
					</div>
					<div class="clearit">&nbsp;</div>
				</div>
				
			</section>

			<div class="clearit">&nbsp;</div>
			
			<section id="your-details" class="form-fields">
		
				<hr class="thick" />
			
				<h3>Your Details</h3>
			
				<div class="form-box">
					
					
					<div class="form-field field-separated">
						<label for="user_first_name">First name</label>
						<input id="user_first_name" name="user[first_name]" size="30" type="text">
						<div class="field-error" id="user_first_name_error">You must provide a first name.</div>
					</div>
		    
					<div class="form-field">
						<label for="user_last_name">Last name</label>
						<input id="user_last_name" name="user[last_name]" size="30" type="text">
						<div class="field-error" id="user_last_name_error">You must provide a last name.</div>
					</div>
		  
					<div class="form-field field-separated">
						<label for="user_email">Email address <em>(you'll use this to sign in)</em></label>
						<span id="email_status">&nbsp;</span>
						<input id="user_email" name="user[email]" size="40" type="text">
						<div class="field-error" id="user_email_error">You must provide a properly formatted email address.</div>
					</div>
		  
					<hr />
		  
					<div class="password-field field-separated">
						<label for="user_password">Password<br /><em>(6 characters or longer)</em></label>
						<input id="user_password" name="user[password]" size="30" type="password">
						<div class="field-error" id="user_password_error">Error.</div>
					</div>
		      
					<div class="password-field">
						<label for="user_password_confirmation">Enter password again<br /><em>(for confirmation)</em></label>
						<input id="user_password_confirmation" name="user[password_confirmation]" size="30" type="password">
						<div class="field-error" id="user_password_confirmation_error">Error.</div>
					</div>
		    
					<div class="clearit">&nbsp;</div>
					
				</div>
			
			</section>
			
			<div class="clearit">&nbsp;</div>

			<!-- The following code is invisible to humans and
			     contains some trap text fields                -->

			<div style="display: none">
			If you can read this, don't touch the following text fields.<br/>

			<input type="text" name="re-cap[address]" value="" /><br/>
			<input type="text" name="re-cap[contact]" value="" /><br/>
			<textarea cols="40" rows="6" name="re-cap[comment]"></textarea>
			</div>

			<!-- End spam bot trap -->
			
			<a href="#" id="btn-sign-up"><img src="/assets/img/complete.png" width="250" height="75" alt="Complete Purchase" border="0" /></a>
					
		</form>		
		
		<section id="footer">
	    	&copy; <?= date('Y') ?> Snapable
	    	
	    	<div id="sm-links">
	    		<a id="sm-twitter" href="http://twitter.com/getsnapable" target="_blank">Follow us</a>
	    		<a id="sm-facebook" href="http://facebook.com/snapable" target="_blank">Like us</a>
	    	</div>
	    </section>
	    
	</div>
