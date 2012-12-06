<form id="payment-form" method="POST" action="/buy/complete/">

	<section id="package-details" class="form-fields">

		<h1>Billing</h1>

		<h3><strong>Your Package: </strong><br /><em><?= $package->name ?></em> for $<?= $package->price ?></h3>
		
		<?php if (isset($package->items->features)) { ?>
			<h3><strong>This package includes:</strong></h3>
			<ul>
				<?php foreach ($package->items->features as $key) {
					echo '<li>'.SnapText::$FEATURE_DESC[$key]['name'].' - '.SnapText::$FEATURE_DESC[$key]['desc'].'</li>'.PHP_EOL;
				} ?>
			</ul>

		<?php } ?>

		<?php if (isset($package->items->modifiers)) { ?>
			<h3><strong>This package also includes:</strong></h3>
			<ul>
				<?php foreach ($package->items->modifiers as $key => $value) {
					echo '<li>'.SnapText::$MODIFIER_DESC[$key]['name'].' - '.SnapText::$MODIFIER_DESC[$key]['desc'].' ('.$value.')</li>'.PHP_EOL;
				} ?>
			</ul>

		<?php } ?>
		
	</section>
	
	<div id="refund-policy" class="form-column-right">
		
		<h4>Refund Policy</h4>
		
		<p>You will be billed right away, however at any time leading up to 48 hours before the event you can request a full refund.</p>
		
		<p>After the event should no prints have been mailed you can request a refund and we will immediately delete all information (including photos) of the event and refund 50% of the package cost. &nbsp;<a href="/site/terms" target="_blank">› See Full Terms of Service</a>.</p>
		
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
					<option value="2012">2012</option>
					<option value="2013" selected="selected">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
					<option value="2016">2016</option>
					<option value="2017">2017</option>
					<option value="2018">2018</option>
					<option value="2019">2019</option>
					<option value="2020">2020</option>
					<option value="2021">2021</option>
					<option value="2022">2022</option>
					<option value="2023">2023</option>
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
			You'll receive a receipt via email upon completion of this form.
		</div>
		
		<img src="/assets/img/RapidSSL_SEAL-90x50.gif" width="90" height="50" alt="Secured By RapidSSL" />
	
	</div>
	
	<div class="clearit">&nbsp;</div>
	
	<input type="submit" id="btn-sign-up" class="submit-button" value="Submit"/> <!--<img src="/assets/img/complete.png" width="250" height="75" alt="Complete Purchase" border="0" />-->	
</form>