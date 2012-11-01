<div class="checkoutWrap">

	<div class="form-box">
	
		<h1>How would you like to pay?</h1>

		<form id="billingForm" name="billing" action="/checkout/dobilling" method="post">
			
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