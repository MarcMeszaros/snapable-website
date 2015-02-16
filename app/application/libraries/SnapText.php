<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SnapText {

    public static $FEATURE_DESC = array(
        'guest_reminders' => array(
            'name' => 'Guest Reminders',
            'desc' => 'Automatic email reminders for guests about the event.',
        ),
        'slideshow' => array(
            'name' => 'Slideshow',
            'desc' => 'Real-time photo slideshow.',
        ),
        'storage_12months' => array(
            'name' => '1 Year of Storage',
            'desc' => 'One year of original photo storage.',
        ),
        'table_cards' => array(
            'name' => 'Table Cards Printable Template',
            'desc' => 'A digital version of table cards that can be printed.',
        ),
    );

    public static $MODIFIER_DESC = array(
        'albums' => array(
            'name' => 'Albums',
            'desc' => 'The number of albums available.',
        ),
        'price_per_print' => array(
            'name' => 'Price Per Extra Print',
            'desc' => 'The price for individual photo prints.',
        ),
        'table_cards' => array(
            'name' => 'Physical Table Cards',
            'desc' => 'Physical table cards for guests.',
        ),
    );

}