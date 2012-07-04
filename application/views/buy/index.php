<form name="create_event" method="post" action="/buy/complete">

	<input type="hidden" id="package" name="event[package]" value="<?= $package[0]->package_id ?>" />
	<input type="hidden" id="lat" name="event[lat]" value="0" />
	<input type="hidden" id="lng" name="event[lng]" value="0" />

	<h1>Sign-up now and receive 10 additional prints <strong>FREE</strong></h1>
	
	<h2>Your Details</h2>
	
	<section id="your-details" class="form-fields">
	
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
				<input id="user_email" name="user[email]" size="40" type="text">
				<div class="field-error" id="user_email_error">You must provide a properly formatted email address.</div>
			</div>
  
			<hr />
  
			<div class="form-field field-separated">
				<label for="user_password">Password <em>(6 characters or longer)</em></label>
				<input id="user_password" name="user[password]" size="30" type="password">
				<div class="field-error" id="user_password_error">Error.</div>
			</div>
      
			<div class="form-field">
				<label for="user_password_confirmation">Enter password again <em>(for confirmation)</em></label>
				<input id="user_password_confirmation" name="user[password_confirmation]" size="30" type="password">
				<div class="field-error" id="user_password_confirmation_error">Error.</div>
			</div>
    
			<div class="clearit">&nbsp;</div>
			
		</div>
	
		<div class="form-fields-bottom">&nbsp;</div>
	</section>
	
	<div class="form-column-right form-package-details">
		
			<p><strong>Your Package: </strong><?= $package[0]->name ?> for $<?= $package[0]->price ?></p>

			<p>This package includes <strong><?= $package[0]->prints ?> prints</strong>, <strong><?php if ( $package[0]->albums == 0 ) { echo "Unlimited"; } else { echo $package[0]->albums; } ?> online albums</strong> and more.</p>
			
	</div>
	
	<div class="clearit">&nbsp;</div>
	
	
	
	<h2>Event Details</h2>
	
	<section id="event-details" class="form-fields">
	
		<div class="form-box">
			
			<div class="form-field field-separated">
				<label for="event_title">Title</label>
				<input id="event_title" class="long-field" name="event[title]" size="40" type="text"> 
				<div class="field-error" id="event_title_error">You must provide a title for your event.</div>
			</div>
			
			<!--
			<div class="form-field field-separated">
				<label for="event_date">Date</label>
				<input id="event_date" class="long-field" name="event[date]" size="40" type="text" value="Make date/time picker for start and end of event"> 
				<div class="field-error" id="event_date_error">You must specify a date and time for your event.</div>
			</div>
			-->
			
			<div class="small-field field-separated">
				<div class="small-field-inner">
					<label for="event-start-date">Start Date</label>
					<input class="longer" id="event-start-date" name="event[start_date]" type="text" value="<?= date("M j, Y", time()) ?>">
				</div>
				<div class="small-field-inner">
					<label for="event-start-time">Time</label>
					<input id="event-start-time" name="event[start_time]" type="text" value="<?= date("h:00 A", time() + 3600) ?>">
				</div>
				
				<div class="small-field-inner">
					<label for="event-start-date">End Date</label>
					<input class="longer" id="event-end-date" name="event[end_date]" type="text" value="<?= date("M j, Y", time()) ?>">
				</div>
				<div class="small-field-inner">
					<label for="event-end-time">Time</label>
					<input id="event-end-time" name="event[end_time]" type="text" value="<?= date("h:00 A", time() + 18000) ?>">
				</div>
			</div>
			
			<div class="location-field field-separated">
				<label for="event_location">Location</label>
				<span id="event_location_status">&nbsp;</span>
				<input id="event_location" class="long-field" name="event[location]" size="40" type="text"> 
				<div class="field-error" id="event_location_error">You must provide a location for your event.</div>
				<div class="form-field_hint">Example: 255 Bremner Blvd, Toronto, Canada, M5V 3M9</div>
			</div>
			
			<hr />
			
			<div class="form-field long-field">
				<label for="event_url">Pick a custom URL</label>
				<span class="info left">http://getsnapable.com/</span><input id="event_url" name="event[url]" size="30" type="text" />
				<span id="event_url_status">&nbsp;</span>
				<div class="clearit">&nbsp;</div>
				<div class="field-error" id="event_url_error">This URL is already in use.</div>
				<div class="form-field_hint">Example: http://getsnapable.com/<b>my-big-fat-greek-wedding</div>
			</div>
			<div class="clearit">&nbsp;</div>
		</div>
		
		<div class="form-fields-bottom">&nbsp;</div>
	</section>
	
	<div class="clearit">&nbsp;</div>
	
	
	
	<h2>Billing Information &nbsp; <img src="/assets/img/lock.png" width="16" height="16" alt="SECURE" /><span>Secure</span></h2>
	
	<section id="billing-info" class="form-fields">
	
		<div class="form-box">
		 
			<div class="form-field field-separated" id="cc_card_name" style="position:relative">
				<label for="creditcard_name">Name on card</label>
				<input autocomplete="off" id="creditcard_name" name="cc[name]" size="30" type="text" />
				<div class="field-error" id="creditcard_name_error">You must provide the name on your credit card.</div>
			</div> 
		 
			<div class="form-field field-separated" id="cc_card_number" style="position:relative">
				<label for="creditcard_number">Credit card number</label>
				<input autocomplete="off" id="creditcard_number" name="cc[number]" size="30" type="text" />
				<div class="field-error" id="creditcard_number_error">You must provide the number on your credit card.</div>
			</div> 
			
			<div class="form-field" id="cc_expiration">
				<label for="creditcard_month">Expiration date</label>
				<select id="creditcard_month" name="cc[month]"><option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6" selected="selected">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
				</select>
				<span class="info left">/<span>
				<select id="creditcard_year" name="cc[year]">
					<option value="2012" selected="selected">2012</option>
					<option value="2013">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
					<option value="2016">2016</option>
					<option value="2017">2017</option>
					<option value="2018">2018</option>
					<option value="2019">2019</option>
					<option value="2020">2020</option>
					<option value="2021">2021</option>
					<option value="2022">2022</option>
				</select>
			</div>
			
			<div class="clearit">&nbsp;</div>
			
			<div class="form-field" id="cc_cvv">
				<label class="input" for="creditcard_verification_value">Security code </label>
				<input id="creditcard_verification_value" name="cc[verification_value]" size="4" type="text" /> 
				<img src="/assets/img/cc_security_code.png" width="48" height="30" alt="-" />
				<div class="field-error" id="creditcard_verification_value_error">You must provide the security from the back of your credit card.</div>
			</div>
			
			<hr />
			
			<div class="form-field" id="zip_field">
				<label class="caps" for="address_zip">Billing ZIP <em>(postal code if outside the USA)</em></label>
				<input id="address_zip" name="address[zip]" size="30" type="text" />
				<div class="field-error" id="creditcard_verification_value_error">For verification purposes you must provide the zip (postal) code connected to your credit card.</div>
			</div>
			
			<div class="clearit">&nbsp;</div>
		 
		</div>
		
		<div class="form-fields-bottom">&nbsp;</div>
	</section>
	
	<div class="form-column-right">
	
		<div id="credit_cards">CARDS</div>
		<div id="receipt_text">
			You'll receive a receipt via email upon completion of this form.
		</div>
	
	</div>
	
	<div class="clearit">&nbsp;</div>
	
	<h2>Please read &amp; agree to our terms and refund policy</h2>
	
	<section id="terms-refund" class="form-fields">
	
		<div class="terms-box">

			<p>You will be billed right away, however at any time leading up to 48 hours before the event you can request a full refund. Post event should no prints have been mailed you can request a refund and we will immediately delete all information (including photos) of the event and refund 50% of the package cost. <a href="/site/terms" target="_blank">See Full Terms of Service</a>.</p>

			<h3><input id="terms-service" name="terms" type="checkbox" /> I agree to these Terms of Service</h3>
			<div class="field-error" id="terms-error">You must agree to the terms before signing up.</div>
			
		</div>
		
		<div class="form-fields-bottom">&nbsp;</div>
	</section>
	
	<div class="clearit">&nbsp;</div>
	
	<a href="#" id="btn-sign-up"><img src="/assets/img/finalize_setup.png" width="453" height="75" alt="Finalize and setup setup" border="0" /></a>
			
</form>