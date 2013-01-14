<?php
    
    include(APPPATH.'libs/stripe-php/lib/Stripe.php');
    Stripe::setApiKey(STRIPE_KEY_SECRET);