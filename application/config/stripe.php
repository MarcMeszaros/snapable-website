<?php
// Configuration options

$allowed_hosts = array('snapable.com', 'www.snapable.com');
$referer = explode("/", substr($_SERVER['HTTP_REFERER'], 7));

$config['stripe_key_test_public']         = '***REMOVED***';
$config['stripe_key_test_secret']         = 'eo3mtWU6bUj2b7XJKxPZOtAouFT0WWJI';

$config['stripe_key_live_public']         = 'pk_Jfq6cp5WGO4JHBQgAtz4zJOeTftLf';
$config['stripe_key_live_secret']         = '3IOP8FwXOan7tnw2bjr4Ovmw1Ibn4OEK';
			
if ( !in_array($referer, $allowed_hosts) )
{
	$config['stripe_test_mode']               = TRUE;
	$config['stripe_verify_ssl']              = FALSE;
} else {
	$config['stripe_test_mode']               = FALSE;
	$config['stripe_verify_ssl']              = TRUE;
}

$stripe = new Stripe( $config );
?>