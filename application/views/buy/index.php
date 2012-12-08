<form id="payment-form" method="POST" action="/buy/complete/">

	<section id="package-details" class="form-fields">

		<h1>Billing</h1>

		<h3><strong>Your Package: </strong><br /><em><?= $package->name ?></em> for $<?php echo currency_cents_to_dollars($package->price); ?></h3>
		
		<?php if (isset($package->items->features)) { ?>
			<h3><strong>This package includes:</strong></h3>
			<ul>
				<?php foreach ($package->items->features as $key) {
					echo '<li><strong>'.SnapText::$FEATURE_DESC[$key]['name'].'</strong><br><em>'.SnapText::$FEATURE_DESC[$key]['desc'].'</em></li>'.PHP_EOL;
				} ?>
			</ul>

		<?php } ?>

		<?php if (isset($package->items->modifiers)) { ?>
			<h3><strong>This package also includes:</strong></h3>
			<ul>
				<?php foreach ($package->items->modifiers as $key => $value) {
					// if price is in the key, conver the value into a price string
					if (preg_match('/price/', $key)) {
						echo '<li><strong>'.SnapText::$MODIFIER_DESC[$key]['name'].'</strong> - ($'.currency_cents_to_dollars($value).')<br><em>'.SnapText::$MODIFIER_DESC[$key]['desc'].'</em></li>'.PHP_EOL;
					} else {
						echo '<li><strong>'.SnapText::$MODIFIER_DESC[$key]['name'].'</strong> - ('.$value.')<br><em>'.SnapText::$MODIFIER_DESC[$key]['desc'].'</em></li>'.PHP_EOL;
					}
				} ?>
			</ul>

		<?php } ?>
		
	</section>
	
	<div id="refund-policy" class="form-column-right">
		
		<h4>Refund Policy</h4>
		<p>
			You will be billed right away, however at any time you can request a full refund up to 30 days 
			after you signup for a Snapable package and we will immediately delete all information 
			(including photos) of the event.<br>
			<a href="/site/terms" target="_blank">› See Full Terms of Service</a>.
		</p>
		
	</div>
	
	<div class="clearit">&nbsp;</div>
	
	<section id="billing-info" class="form-fields">
	
		<hr class="thick" />
	
		<h3>Billing Information &nbsp; <img src="/assets/img/lock.png" width="16" height="16" alt="SECURE" /><span>Secure</span></h3>
	
		<div class="form-box">

			<div class="form-field field-separated" id="cc_card_name" style="position:relative">
				<label for="creditcard_name">Name on card</label>
				<input type="text" id="creditcard_name" name="cc[name]" size="30" autocomplete="off" />
				<div class="field-error" id="creditcard_name_error">You must provide the name on your credit card.</div>
			</div>

			<div class="form-field field-separated" id="cc_card_number" style="position:relative">
				<label for="creditcard_number">Credit card number</label>
				<input type="text" id="creditcard_number" size="30" autocomplete="off" />
				<div class="field-error" id="creditcard_number_error">The number you have entered is seems to be invalid.</div>
			</div>
			
			<div class="expires-field" id="cc_expiration">
				<label for="creditcard_month">Expiration date</label>
				<select id="creditcard_month">
					<option value="1">1</option>
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
				<select id="creditcard_year">
					<?php
						// get the current year
						$year = date('Y');
						// print this year as the default selected one
						echo '<option value="'.$year.'" selected="selected">'.$year.'</option>'.PHP_EOL;
						// loop through and add the years for the next 10
						for ($i = 0; $i < 10; $i++)
						{
							$year++;
							echo '<option value="'.$year.'">'.$year.'</option>'.PHP_EOL;
						}
					?>
				</select>
			</div>
			
			<div class="cvv-field" id="cc_cvv">
				<label class="input" for="creditcard_cvc">Security code</label>
				<input type="text" id="creditcard_cvc" size="4" autocomplete="off"/> 
				<img src="/assets/img/cc_security_code.png" width="48" height="30" alt="-" />
				<div class="field-error" id="creditcard_cvc_error">You must provide the security from the back of your credit card.</div>
			</div>

			<div class="clearit">&nbsp;</div>
			
			<hr />
			
			<div class="form-field" id="zip_field">
				<label class="caps" for="address_zip">Billing ZIP <em>(postal code if outside the USA)</em></label>
				<input type="text" id="address_zip" name="address[zip]" size="30" />
				<div class="field-error" id="creditcard_verification_value_error">For verification purposes you must provide the zip (postal) code connected to your credit card.</div>
			</div>
			
			<div class="clearit">&nbsp;</div>
		 
		</div>
		
	</section>
	
	<div class="form-column-right">
	
		<div id="credit_cards">
			<img src="/assets/img/cards.png" width="154" height="30" alt="Visa, Mastercard, Paypal" />
		</div>
		<div id="receipt_text">
			You'll receive a receipt via email upon completion of your order.
		</div>
		
		<img src="/assets/img/RapidSSL_SEAL-90x50.gif" width="90" height="50" alt="Secured By RapidSSL" />
	
	</div>
	
	<div class="clearit">&nbsp;</div>
	
	<input type="submit" id="btn-sign-up" class="submit-button" value="Submit"/> <!--<img src="/assets/img/complete.png" width="250" height="75" alt="Complete Purchase" border="0" />-->	
</form>