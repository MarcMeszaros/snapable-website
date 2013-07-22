<form action="/signup/setup" method="post" name="signupForm" id="payment-form">
	<script src="/assets/js/libs/jquery.validate.min.js" type="text/javascript"></script>
						
	<div id="wrap">
		
		<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyAofUaaxFh5DUuOdZHmoWETZNAzP1QEya0&sensor=false"></script>
		<div id="notification"></div>
		
		<div class="left">
			
			<a id="logo" href="/">Snapable</a>
			
			<ul>
				<li><a id="navEvent" href="#event" class="active">Event Details</a></li>
				<li><a id="navYour" href="#your">Your Details</a></li>
				<li><a id="navBilling" href="#billing">Billing Info</a></li>
			</ul>
			
		</div>
		
		<div class="right">
		
			<h1>SIGN-UP</h1>
			<h2>It takes only a minute to setup your event</h2>
			
			<div class="form">
			
				<div id="event">
				
					<h3>Event Details</h3>

					<!-- some required data magically figured out via AJAXy stuff -->
					<input type="hidden" id="lat" name="event[lat]" value="0" />
					<input type="hidden" id="lng" name="event[lng]" value="0" />
					<input type="hidden" id="timezone" name="event[tz_offset]" value="0" />
					
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
					
					<div class="form-field">
						<label for="event_url">Pick a custom URL</label>
						<span id="event_url-start">snapable.com/event/</span><input id="event_url" name="event[url]" type="text" />
						<span id="event_url_status">&nbsp;</span>
						<div class="clearit">&nbsp;</div>
						<div class="field-error" id="event_url_error">This URL is already in use.</div>
						<div class="form-field_hint">Example: http://snapable.com/event/<b>my-big-fat-greek-wedding</div>
					</div>
					<div class="clearit">&nbsp;</div>
					
					<hr />
					
					<a class="button" id="eventDeets" href="#">Next: Your Details ›</a>
					
					
				</div>
				
				<div id="your">
				
					<h3>Your Details</h3>
					
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
					
					<hr />
					
					
					<a class="button" id="yourDeets" href="#">Next: Billing Info ›</a>
					
				</div>
				
				<div id="billing">
				
					<h3>Billing Info</h3>
			
					<div class="form-field field-separated" id="card_type">
						<label for="billing_type">We Accept:</label>
						<img src="/assets/img/icons/cards/visa.png" width="50" height="34" border="0" alt="Visa" />
						<img src="/assets/img/icons/cards/mastercard.png" width="50" height="34" border="0" alt="Mastercard" />
						<img src="/assets/img/icons/cards/amex.png" width="50" height="34" border="0" alt="Amex" />
						<img src="/assets/img/icons/cards/discover.png" width="50" height="34" border="0" alt="Discover" />
					</div>
					
					<input type="hidden" name="amount" value="10" />
					
					<div class="form-field field-separated">
						<label for="name">Name on Card</label>
						<input type="text" name="name" id="creditcard_name" class="card-name required" />		
						<div class="field-error" id="creditcard_name_error">You must provide the name on your credit card.</div>	
					</div>
					
					<div class="form-field field-separated">
						<label for="card-number">Card Number</label>
						<input type="text" name="card-number" id="creditcard_number" class="card-number stripe-sensitive required" />
						<div class="field-error" id="creditcard_number_error">The number you have entered is invalid.</div>
					</div>
					
					<div class="form-field field-separated">
						<label for="card-expiry-month">Expiration Date:</label> 
						<select name="card-expiry-month" id="creditcard_month" class="card-expiry-month stripe-sensitive required"> 
							<option value="01">1 - January</option> 
							<option value="02">2 - February</option> 
							<option value="03">3 - March</option> 
							<option value="04">4 - April</option> 
							<option value="05">5 - May</option> 
							<option value="06">6 - June</option> 
							<option value="07">7 - July</option> 
							<option value="08">8 - August</option> 
							<option value="09">9 - September</option> 
							<option value="10">10 - October</option> 
							<option value="11">11 - November</option> 
							<option value="12">12 - December</option> 
						</select> 
						
						<select name="card-expiry-year" id="creditcard_year" class="card-expiry-year stripe-sensitive required"> 
							<?php
								// get the current year
								$year = date('Y');
								// print this year as the default selected one
								echo '<option value="'.$year.'" selected="selected">'.$year.'</option>'.PHP_EOL;
								// loop through and add the years for the next 10
								for ($i = 0; $i < 5; $i++)
								{
									$year++;
									echo '<option value="'.$year.'">'.$year.'</option>'.PHP_EOL;
								}
							?> 
						</select>
						<div class="field-error" id="creditcard_exp_error">Expiration date is invalid.</div>
					</div>
					
					<div class="small-field field-separated">
						<label for="card-cvc">CVC</label>
						<input type="text" name="card-cvc" id="creditcard_cvc" class="shortInput card-cvc stripe-sensitive required" />
						<div class="field-error" id="creditcard_cvc_error">You must provide the security code from the back of your credit card.</div>
					</div>
					
					<div class="payment-errors"></div>
					
					<div class="clearit">&nbsp;</div>
					<hr />
					
					
					<div class="secureInfo">Your information is secure</div>
					<input id="completSignup" type="submit" name="submit-button" value="Setup Event ›" />
					
				</div>
			
			</div>
			
			<div class="package">
			
				<h4>You get all this for your event:</h4>
			        	
	        	<ul>
	        		<li>Unlimited guests</li>
	        		<li>Unlimited photos </li>
	        		<li>Download your photos anytime</li>
	        		<li>Upload additional photos</li>
	        		<li>Unlimited guest email notifications</li>
	        		<li>Personalized instruction cards for guests</li>
	        	</ul>
        	
	        	<div class="package-big-bottom">
	        	
	        		$<span id="package-amount"><?= $amount_in_cents/100 ?></span>
	        		
	        	</div>
	        	
	        	<div class="package-promo">
	        		Got a promo code? Enter it here:
	        		<br /><input type="text" name="promo-code" /> <a id="apply-promo-code" href="#">Apply</a>
	        		<input type="hidden" name="promo-code-applied" value="0" />
	        		<input type="hidden" name="promo-code-amount" value="0" />
	        	</div>
				
			</div>
			
		</div>
		
	</div>

</form>
	
<div class="clearit">&nbsp;</div>