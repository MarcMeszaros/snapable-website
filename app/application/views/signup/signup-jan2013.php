<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyAofUaaxFh5DUuOdZHmoWETZNAzP1QEya0&sensor=false"></script>

<div id="wrap" class="container">
	<div class="row">
		<div class="col-lg-2 col-lg-offset-1">
			<div id="left">
				<a id="logo" href="/">Snapable</a>
				<ul>
					<li><a id="navEvent" href="#event" class="active">Event Details</a></li>
					<li><a id="navYour" href="#your">Your Details</a></li>
					<li><a id="navBilling" href="#billing">Billing Info</a></li>
				</ul>
			</div>
		</div>

		<form id="payment-form" class="form" name="signupForm" action="/signup/setup" method="post" data-validate="parsley" novalidate>
		<div class="col-lg-5">
			<h1>SIGN-UP</h1>
			<h2>It takes only a minute to setup your event</h2>

			<div id="event">
				<h3>Event Details</h3>
				<fieldset>
					<!-- some required data magically figured out via AJAXy stuff -->
					<input type="hidden" id="lat" name="event[lat]" value="0" />
					<input type="hidden" id="lng" name="event[lng]" value="0" />
					<input type="hidden" id="timezone" name="event[tz_offset]" value="0" />

					<div class="form-group">
						<label for="event_title">Title</label>
						<input id="event_title" class="form-control" name="event[title]" type="text" data-required="true" data-notblank="true" data-error-message="You must provide a title for your event." />
					</div>

					<div class="form-group row">
						<div class="form-group col-sm-4">
							<label for="event-start-date">Date</label>
							<input id="event-start-date" class="form-control" name="event[start_date]" type="text" value="<?= date("M j, Y", time()) ?>">
						</div>
						<div class="form-group col-sm-3">
							<label for="event-start-time">Time</label>
							<input id="event-start-time" class="form-control" name="event[start_time]" type="text" value="<?= date("h:00 A", time() + 3600) ?>">
						</div>
						<div class="form-group col-sm-5">
							<label for="event-duration-num">Duration</label>
							<div class="form-inline">
								<select id="event-duration-num" class="form-control" name="event[duration_num]" style="width:49%;">
								<?php
								for ($i=1; $i<=23; $i++) {
									if ( $i == 12 ) {
										$selected = " SELECTED";
									} else {
										$selected = "";
									}
									echo "<option value='" . $i . "'" . $selected . ">" . $i . "</option>";
								}
								?>
								</select>
								<select id="event-duration-type" class="form-control" name="event[duration_type]" style="width:49%;">
									<option value="hours">Hours</option>
									<option value="days">Days</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="event_location">Location</label>
						<div class="form-inline">
							<input id="event_location" class="form-control status" name="event[location]" type="text" data-required="true" data-notblank="true" data-error-message="You must provide a location for your event." />
						</div>
						<span class="help-block">Example: 255 Bremner Blvd, Toronto, Canada, M5V 3M9</span>
					</div>

					<div id="map_canvas_container" class="form-group" style="display:none;">
						<div id="map_canvas" style="width: 350px; height: 300px;"></div>
						<p style="width:350px; margin-top:10px;">Here's where we've got your event, if we're wrong you can drag the location marker to the correct location.</p>
					</div>

					<div class="form-group">
						<label for="event_url">Choose a unique event URL</label>
						<div class="form-inline">
							<span id="event_url-start">snapable.com/event/</span><input id="event_url" class="form-control status" name="event[url]" type="text" data-required="true" data-notblank="true" />
						</div>
						<span class="help-block">Example: https://snapable.com/event/<b>my-big-fat-greek-wedding</b></span>
					</div>

					<hr />

					<button type="button" id="eventDeets" class="btn btn-primary btn-lg">Next: Your Details ›</button>
				</fieldset>
			</div>

			<div id="your">
				<h3>Your Details</h3>
					<fieldset>
					<div class="form-group">
						<label for="user_first_name">First name</label>
						<input id="user_first_name" class="form-control" name="user[first_name]" type="text" data-required="true" data-notblank="true" data-error-message="You must provide a first name." />
					</div>

					<div class="form-group">
						<label for="user_last_name">Last name</label>
						<input id="user_last_name" class="form-control" name="user[last_name]" type="text" data-required="true" data-notblank="true" data-error-message="You must provide a last name." />
					</div>

					<div class="form-group">
						<label for="user_email">Email address <em>(you'll use this to sign in)</em></label>
						<div class="form-inline">
							<input id="user_email" class="form-control status" name="user[email]" type="email" data-required="true" data-notblank="true" data-error-message="You must provide a properly formatted email address." />
						</div>
					</div>

					<hr />

		  			<div class="form-group row">
						<div class="form-group col-sm-6">
							<label for="user_password">Password<br /><em>(6 characters or longer)</em></label>
							<input id="user_password" class="form-control" name="user[password]" type="password" data-required="true" data-notblank="true" data-minlength="6" />
						</div>

						<div class="form-group col-sm-6">
							<label for="user_password_confirmation">Enter password again<br /><em>(for confirmation)</em></label>
							<input id="user_password_confirmation" class="form-control" name="user[password_confirmation]" type="password" data-required="true" data-notblank="true" data-minlength="6" data-equalto="#user_password" />
						</div>
					</div>

					<hr />

					<button type="button" id="yourDeets" class="btn btn-primary btn-lg">Next: Billing Info ›</button>
				</fieldset>
			</div>

			<div id="billing">
				<h3>Billing Info</h3>
				<fieldset>
					<div class="form-group">
						<label for="cc_address_line1">Billing Address</label>
						<input type="text" id="cc_address_line1" class="form-control" data-stripe="address_line1" data-required="true" data-notblank="true" />
						<label for="cc_address_line2">Appartment/Suite</label>
						<input type="text" id="cc_address_line2" class="form-control" data-stripe="address_line2" />
					</div>

					<div class="form-group">
						<label for="cc_country">Country</label>
						<select id="cc_country" class="form-control" data-stripe="address_country" data-required="true">
							<?php
								// loop through the countries
								foreach ($countries as $key => $value) {
									echo '<option value="'.$value.'">'.$value.'</option>'.PHP_EOL;
								}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="cc_city">City</label>
						<input type="text" id="cc_city" class="form-control" data-stripe="address_city" />
					</div>

					<div class="form-group row">
						<div class="form-group col-sm-6">
							<label for="cc_state">State</label>
							<input type="text" id="cc_state" class="form-control" data-stripe="address_state" />
						</div>

						<div class="form-group col-sm-6">
							<label for="cc_zip">Zip/Postal Code</label>
							<input type="text" id="cc_zip" class="form-control" data-stripe="address_zip" />
						</div>
					</div>

					<div class="form-group">
						<label for="cc_name">Name on Credit Card</label>
						<input type="text" id="cc_name" class="form-control" data-stripe="name" data-required="true" data-notblank="true" data-error-message="You must provide the name on your credit card." />
					</div>

					<div class="form-group">
						<label for="cc_number">Credit Card Number</label>
						<input type="text" id="cc_number" class="form-control" data-stripe="number" data-required="true" data-notblank="true" data-error-message="You must provide a valid credit card number." />
						<div class="field-error" id="creditcard_number_error">The number you have entered is invalid.</div>
					</div>

					<div id="cc_type" class="form-group">
						<img src="/assets/img/icons/cards/visa.png" width="50" height="34" alt="Visa" />
						<img src="/assets/img/icons/cards/mastercard.png" width="50" height="34" alt="MasterCard" />
						<img src="/assets/img/icons/cards/amex.png" width="50" height="34" alt="American Express" />
						<img src="/assets/img/icons/cards/discover.png" width="50" height="34" alt="Discover" />
					</div>

					<div class="form-group">
						<label for="cc_exp_month">Expiration Date</label>
						<div class="form-inline">
							<select id="cc_exp_month" class="form-control short" data-stripe="exp_month" data-required="true">
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
							<select id="cc_exp_year" class="form-control short" data-stripe="exp_year" data-required="true">
								<?php
									// get the current year
									$year = date('Y');
									// print this year as the default selected one
									echo '<option value="'.$year.'" selected="selected">'.$year.'</option>'.PHP_EOL;
									// loop through and add the years for the next 5
									for ($i = 0; $i < 5; $i++) {
										$year++;
										echo '<option value="'.$year.'">'.$year.'</option>'.PHP_EOL;
									}
								?>
							</select>
							<div class="field-error" id="cc_exp_error">Expiration date is invalid.</div>
						</div>
					</div>

					<div class="form-group">
						<label for="cc_cvc">Security Code (CVC)</label>
						<input type="text" id="cc_cvc" class="form-control" data-stripe="cvc" data-required="true" data-notblank="true" data-minlength="3" />
						<div class="field-error" id="cc_cvc_error">You must provide the security code from the back of your credit card.</div>
					</div>

					<hr />

					<div class="secureInfo"><span class="glyphicon glyphicon-lock"></span> Your information is secure</div>
					<div>
						<button id="completSignup" class="btn btn-primary btn-lg" type="submit" name="submit-button">Setup Event ›</button>
						<span id="signup-spinner" class="spinner-wrap hide" data-length="8" data-radius="5" data-width="4"></span>
					</div>
				</fieldset>
			</div>

		</div>

		<div id="package" class="col-lg-3">
			<h4>You get all this for your event:</h4>
	    	<ul class="dotted">
	    		<li>Unlimited guests</li>
	    		<li>Unlimited photos </li>
	    		<li>Download your photos anytime</li>
	    		<li>Upload additional photos</li>
	    		<li>Unlimited guest email notifications</li>
	    		<li>Personalized instruction cards for guests</li>
	    	</ul>

	    	<div class="package-big-bottom">
	    		$<span id="package-amount" data-amount="<?= $amount_in_cents ?>"><?= $amount_in_cents/100 ?></span>
	    	</div>

	    	<div class="package-promo">
	    		Have a promo code? Enter it here:
	    		<br /><input type="text" id="promo-code" name="promo-code" data-amount="0" /> <button type="button" id="apply-promo-code" class="btn btn-primary btn-sm" href="#">Apply</button>
	    		<input type="hidden" id="promo-code-applied" name="promo-code-applied" value="" />
	    	</div>
		</div>
		</form>

	</div>
</div>
