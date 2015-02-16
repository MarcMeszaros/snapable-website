<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <h2>User Signup</h2>
    <p>
        <strong>Woot!</strong> <?= $email_address ?> just signed up to Snapable.
    </p>
    <p>
        Their event starts at <?php echo date("Y-m-d H:i:s", $start_timestamp); ?> and last until 
        <?php echo date( "Y-m-d H:i:s", $end_timestamp ); ?>.
    </p>

    <h2>Signup details</h2>
    <ul>
        <li>Email: <?= $email_address ?></li>
        <li>Coupon: <?= $coupon ?></li>
        <li>Affiliate: <?= $affiliate ?></li>
        <li>Total: <?php echo currency_cents_to_dollars($total); ?></li>
    </ul>

</body>
</html>