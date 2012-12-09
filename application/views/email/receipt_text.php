===================================================
Snapable Purchase Receipt
===================================================

Item(s)                                 | Price
---------------------------------------------------
<?php foreach ($items as $name => $details) {
    // name can take up to 40 chars
    echo str_pad(substr($name, 0, 40), 40).'  $'.currency_cents_to_dollars($details['price']).PHP_EOL.PHP_EOL;
} ?>
---------------------------------------------------

<?php echo str_pad('Total:', 40).'  $'.currency_cents_to_dollars($total).PHP_EOL; ?>

----------------
(c)2012 Snapable