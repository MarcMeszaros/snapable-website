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

    /**
     * Send an API request and return the results in an array.
     */
    public static function send($verb, $path, $params=array(), $headers=array()) {
        // create the curl object and signature
        $ch = curl_init();
        $sign = self::sign($verb, $path);

        // define default headers
        $defaultHeaders = array(
            'Accept' => 'application/json',
            'X-SNAP-Date' => $sign['x_snap_date'],
            'X-SNAP-nonce' => $sign['x_snap_nonce'],
            'Authorization' => 'SNAP '.$sign['api_key'].':'. $sign['signature'],
        );

        // if it's a GET request, put params in query string
        if ($verb == 'GET') {
            $paramArray = array();
            foreach ($params as $key => $value) {
                $paramArray[] = $key.'='.$value;
            }
            $paramString = implode('&', $paramArray);
            $queryString = (isset($paramString) && count($paramString) > 0)? '?'.$paramString:'';
            curl_setopt($ch, CURLOPT_URL, API_HOST . $path . $queryString); 
        } else {
            if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'multipart/form-data' && count($params) > 0) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            } else {
                // json encode the params
                $json = json_encode($params);
                $defaultHeaders['Content-Length'] = strlen($json);
                $defaultHeaders['Content-Type'] = 'application/json';
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            }

            // modify the request to include the json in body
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
            curl_setopt($ch, CURLOPT_URL, API_HOST . $path);
        } 

        // merge and replace default headers with passed in parameter
        $headers = array_replace($defaultHeaders, $headers);
        // format the headers before appending
        $headersArray = array();
        foreach ($headers as $key => $value) {
            $headersArray[] = $key.': '.$value;
        }

        // set various curl parameters
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);

        // execute the request and parse response
        $response = curl_exec($ch);
        //$response = str_replace('false', '"0"', $response);
        //$response = str_replace('true', '"1"', $response);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // return the response string and response code in an array
        return array(
            'response' => $response,
            'code' => $httpcode,
        );
    }
}