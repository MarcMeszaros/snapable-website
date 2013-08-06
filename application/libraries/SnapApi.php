<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SnapApi class
 */
class SnapApi {

    public static $api_host = API_HOST;
    public static $api_version = API_VERSION;
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
     * Generate a timestamp to use for API key signing.
     */
    public static function getTimestamp() {
        return time();
    }

    /**
     * Create a HMAC signature for an API request and return an array with all
     * the required parts required to build the API call.
     */
    public static function sign($verb, $path) {
        $path = (substr($path, 0, 1) == '/') ? substr($path, 1, strlen($path)-1):$path; // remove the leading '/' if it exists
        $path = (substr($path, -1, 1) == '/') ? substr($path, 0, strlen($path)-1):$path; // remove the trailling '/' if it exists
        $path = '/'.self::$api_version.'/'.$path.'/'; // build the path with the API version

        $x_snap_nonce = self::getNonce();
        $x_snap_timestamp = self::getTimestamp();
        $raw_signature = self::$api_key . $verb . $path . $x_snap_nonce . $x_snap_timestamp;
        $signature = hash_hmac('sha1', $raw_signature, self::$api_secret);
        return array(
            'x_snap_timestamp' => $x_snap_timestamp,
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

        $path = (substr($path, 0, 1) == '/') ? substr($path, 1, strlen($path)-1):$path; // remove the leading '/' if it exists
        $path = (substr($path, -1, 1) == '/') ? substr($path, 0, strlen($path)-1):$path; // remove the trailling '/' if it exists
        $path = '/'.self::$api_version.'/'.$path.'/'; // build the path with the API version

        $sign_array = array(
            'key="'.$sign['api_key'].'"',
            'signature="'.$sign['signature'].'"',
            'nonce="'.$sign['x_snap_nonce'].'"',
            'timestamp="'.$sign['x_snap_timestamp'].'"',
        );
        // define default headers
        $defaultHeaders = array(
            'User-Agent' => 'SnapApi/1.1',
            'Accept' => 'application/json',
            'Authorization' => 'SNAP '.implode(',',$sign_array),
        );

        // if it's a GET request, put params in query string
        if ($verb == 'GET') {
            $paramArray = array();
            foreach ($params as $key => $value) {
                $paramArray[] = urlencode($key).'='.urlencode($value);
            }
            $paramString = implode('&', $paramArray);
            $queryString = (isset($paramString) && strlen($paramString) > 0)? '?'.$paramString:'';
            curl_setopt($ch, CURLOPT_URL, self::$api_host . $path . $queryString);
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
            curl_setopt($ch, CURLOPT_URL, self::$api_host . $path);
        } 

        // merge and replace default headers with passed in parameter
        $headers = array_replace($defaultHeaders, $headers);
        // format the headers before appending
        $headersArray = array();
        foreach ($headers as $key => $value) {
            $headersArray[] = $key.': '.$value;
        }

        // set various curl parameters
        curl_setopt($ch, CURLOPT_TIMEOUT, '30');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);

        // execute the request and parse response
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // return the response string and response code in an array
        return array(
            'response' => $response,
            'code' => $httpcode,
        );
    }

    /**
     * A helper function to get the next page of results
     * by passing the "next" value from a response in the "meta"
     * section of the API response.
     */
    public static function next($next) {
        // get the various parts from the string
        $urlParts = explode('?', $next);
        $pathParts = explode('/', $urlParts[0]);
        $paramsStringArray = explode('&', $urlParts[1]);

        // setup the API call verb and path
        $verb = 'GET';
        $path = $pathParts[count($pathParts)-2];

        // setup the params to pass to 'send'
        $params = array();
        foreach ($paramsStringArray as $value) {
            $param = explode('=', $value);
            $params[urldecode($param[0])] = urldecode($param[1]);
        }
        
        return self::send($verb, $path, $params);
    }
}