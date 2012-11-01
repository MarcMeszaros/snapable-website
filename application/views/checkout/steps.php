<ul id="checkoutSteps">
	<li<?php if ( isset($step) && $step == "shipping" ) { echo ' class="active"'; } ?>>Shipping Address</li>
	<li<?php if ( isset($step) && $step == "billing" ) { echo ' class="active"'; } ?>>Billing Info</li>
	<li<?php if ( isset($step) && $step == "complete" ) { echo ' class="active"'; } ?>>Complete</li>
</ul>