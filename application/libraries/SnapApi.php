<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SnapApi class
 */
class SnapApi {

    private static $api_key = API_KEY;
    private static $api_secret = API_SECRET;

    /**
     * Generate a nonce to use for API key signing.
     */
    public static function getNonce($length = 16) {
        $nonce = '';
        while ($length > 0) {
            $nonce .= dechex(mt_rand(0,15));
            $length -= 1;
        }
        return $nonce;
    }

    /**
     * Generate a date timestamp to use for API key signing.
     */
    public static function getDate() {
        return gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
    }

    /**
     * Create a HMAC signature for an API request and return an array with all
     * the required parts required to build the API call.
     */
    public static function sign($verb, $path) {
        $x_snap_nonce = self::getNonce();
        $x_snap_date = self::getDate();
        $raw_signature = self::$api_key . $verb . $path . $x_snap_nonce . $x_snap_date;
        $signature = hash_hmac('sha1', $raw_signature, self::$api_secret);
        return array(
            'x_snap_date' => $x_snap_date,
            'x_snap_nonce' => $x_snap_nonce,
            'signature' => $signature,
            'api_key' => self::$api_key,
        );
    }
}