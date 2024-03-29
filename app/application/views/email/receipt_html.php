<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <h2>Snapable Purchase Receipt</h2>
    <table border="1" style="width:300px;">
        <tr><th>Item(s)</th><th>Price</th></tr>
        <?php foreach ($items as $name => $details) {
            // name can take up to 40 chars
            echo '<tr><td>'.$name.'</td><td>$'.currency_cents_to_dollars($details['price']).'</td></tr>'.PHP_EOL;
        } ?>
    </table>
    <p>
        Total: <?php echo '$'.currency_cents_to_dollars($total).PHP_EOL; ?>
    </p>

    <p>&copy; <?php echo date('Y'); ?> Snapable</p>

</body>
</html>