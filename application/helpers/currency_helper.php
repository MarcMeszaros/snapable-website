<?php
    
    function currency_cents_to_dollars($int, $decimal=2) {
        return number_format(($int/100.00), $decimal);
    }