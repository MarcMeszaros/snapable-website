== User Signup ==
Woot! <?= $email_address ?> just signed up to Snapable.

Their event starts at <?php echo date("Y-m-d H:i:s", $start_timestamp); ?> 
and lasts until <?php echo date( "Y-m-d H:i:s", $end_timestamp ); ?>.

== Signup details ==
Email: <?= $email_address ?>
Coupon: <?= $coupon ?>
Affiliate: <?= $affiliate ?>
Total: <?php echo currency_cents_to_dollars($total); ?>