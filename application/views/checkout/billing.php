<div class="checkoutWrap">

	<div class="form-box">
	
		<h1>How would you like to pay?</h1>
		
		<?php if ( isset($error) && $error == true )
		{
			echo '<div id="chargeFail">We were unable to charge your card.<br />If you think this is an error, contact us: <a href="mailto:team@scratchpad.co">team@snapable.com</a></div>';
		} 
		?>
		
		<form action="/checkout/pay" method="post" id="billingForm">
		<script src="/assets/js/jquery.validate.min.js" type="text/javascript"></script>
			
			<div class="form-field field-separated" id="card_type">
				<label for="billing_type">Credit Card Type</label>
				<img src="/assets/img/icons/cards/visa.png" width="50" height="34" border="0" alt="Visa" />
				<img src="/assets/img/icons/cards/mastercard.png" width="50" height="34" border="0" alt="Mastercard" />
				<img src="/assets/img/icons/cards/amex.png" width="50" height="34" border="0" alt="Amex" />
				<img src="/assets/img/icons/cards/discover.png" width="50" height="34" border="0" alt="Discover" />
			</div>
			
			<input type="hidden" name="amount" value="10" />
			<input type="hidden" name="email" class="email required" value="<?= $email ?>" />
			
			<div class="form-field field-separated">
				<label for="name">Name on Card</label>
				<input type="text" name="name" class="card-name required" />			
			</div>
			
			<div class="form-field field-separated">
				<label for="card-number">Card Number</label>
				<input type="text" name="card-number" class="card-number stripe-sensitive required" />
			</div>
			
			<div class="form-field field-separated">
				<label for="card-expiry-month">Expiration Date:</label> 
				<select name="card-expiry-month" class="card-expiry-month stripe-sensitive required"> 
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
				
				<select name="card-expiry-year" class="card-expiry-year stripe-sensitive required"> 
					<option value="2012">2012</option> 
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
					<option value="2023">2023</option> 
					<option value="2024">2024</option> 
				</select>
			</div>
			
			<div class="small-field field-separated">
				<label for="card-cvc">CVV</label>
				<input type="text" name="card-cvc" class="shortInput card-cvc stripe-sensitive required" />
			</div>
			
			<div class="divider">&nbsp;</div>
			
			<div class="payment-errors"></div>
			
			<input type="submit" name="submit-button" value="Complete" />
			
			<!--
			<div class="form-field field-separated" id="card_type">
				<label for="billing_type">Credit Card Type</label>
				<img src="/assets/img/icons/cards/visa.png" width="50" height="34" border="0" alt="Visa" />
				<img src="/assets/img/icons/cards/mastercard.png" width="50" height="34" border="0" alt="Mastercard" />
				<img src="/assets/img/icons/cards/amex.png" width="50" height="34" border="0" alt="Amex" />
				<img src="/assets/img/icons/cards/discover.png" width="50" height="34" border="0" alt="Discover" />
			</div>	
						
			<div class="form-field field-separated">
				<label for="billing_name">Name on Card</label>
				<input id="billing_name" name="billing[name]" type="text">
			</div>	
			
			<div class="form-field field-separated">
				<label for="billing_num">Card Number</label>
				<input id="billing_num" name="billing[num]" type="text">
			</div>	
			
			
			<div class="small-field field-left field-separated">
				<label for="billing_expiry_month">Expiry Date</label>
				<select name="billing_expiry_month" class="select">
					<option value="0">Jan</option>
					<option value="1">Feb</option>
					<option value="2">Mar</option>
					<option value="3">Apr</option>
					<option value="4">May</option>
					<option value="5">Jun</option>
					<option value="6">Jul</option>
					<option value="7">Aug</option>
					<option value="8">Sep</option>
					<option value="9">Oct</option>
					<option value="10">Nov</option>
					<option value="11">Dec</option>
				</select>
			</div>			
							
			<div class="small-field field-right" style="margin-left:15px;">
				<label for="billing_expiry_year">&nbsp;</label>
				<select name="billing_expiry_year" class="select">
					<?php
					$total_years = 8;
					$year = date('Y');
					
					for ($i=1; $i<=$total_years; $i++)
					{
						echo '<option value="' . $year . '">' . $year . '</option>';
						$year++;
					}
					?>
				</select>
			</div>			
			
			<div class="clearit" style="margin-bottom:20px;">&nbsp;</div>
			
			<div class="small-field field-left field-separated">
				<label for="billing_cvv">CVV</label>
				<input id="billing_cvv" name="billing[cvv]" type="text">
			</div>	
			
			<div class="small-field field-right">
				<label for="billing_zip">Zip/Postal Code</label>
				<input id="billing_zip" name="billing[zip]" size="30" type="text">
			</div>
			
			<div class="clearit">&nbsp;</div>
			
			<div class="continueWrap">
				<input type="submit" name="continueBTN" value="Continue" />
			</div>
			-->
		</form>
		
	</div>
	
	<div class="orderReview">
		
		<div class="secureInfo">Your information is secure</div>
		
		<h2>Your Order:</h2>
		
		<ul class="orderContents"><?= $order_contents ?></ul>
		
		<div class="questionsInfo"><h2>Questions? </h2> Email us at <a href="mailto:team@snapable.com">team@snapable.com</a>, we're always happy to answer any questions you have.</div>
		
	</div>
	
	<div class="clearit">&nbsp;</div>
	
</div>