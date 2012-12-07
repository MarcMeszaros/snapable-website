<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SnapText {

    public static $FEATURE_DESC = array(
        'guest_reminders' => array(
            'name' => 'Guest Reminders',
            'desc' => 'Automatic guest reminder emails.',
        ),
        'slideshow' => array(
            'name' => 'Slideshow',
            'desc' => 'Slide',
        ),
        'storage_12months' => array(
            'name' => '1 Year of Storage',
            'desc' => 'One year of storage',
        ),
        'table_cards' => array(
            'name' => 'Table Cards',
            'desc' => 'Table',
        ),
    );

    public static $MODIFIER_DESC = array(
        'albums' => array(
            'name' => 'Albums',
            'desc' => 'The number of albums.',
        ),
        'price_per_print' => array(
            'name' => 'Price Per Print',
            'desc' => 'The price per print.',
        ),
        'table_cards' => array(
            'name' => 'Table Cards',
            'desc' => 'Physical Table Cards',
        ),
    );

}