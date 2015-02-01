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
        $curl_timeout = 30;
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
            'User-Agent' => 'SnapApi/0.1.0',
            'Accept' => 'application/json',
            'Authorization' => 'SNAP '.implode(',',$sign_array),
        );

        // if it's a GET request, put params in query string
        $requestUrl = self::$api_host . $path;
        if ($verb == 'GET') {
            $paramArray = array();
            foreach ($params as $key => $value) {
                $paramArray[] = urlencode($key).'='.urlencode($value);
            }
            $paramString = implode('&', $paramArray);
            $queryString = (isset($paramString) && strlen($paramString) > 0)? '?'.$paramString:'';
            $requestUrl = $requestUrl . $queryString;
        } else {
            if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'multipart/form-data' && count($params) > 0) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            } else {
                // json encode the params
                $json = json_encode($params);
                // PHP json_encode() sucks, it puts "" around JSON boolean values.
                // So we need to fix it ourselves... -_-
                $needle = array('":"true"', '":"false"');
                $replace = array('":true', '":false');
                $json = str_ireplace($needle, $replace, $json);
                // set the content headers headers
                $defaultHeaders['Content-Length'] = strlen($json);
                $defaultHeaders['Content-Type'] = 'application/json';
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            }

            // modify the request to include the json in body
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
        } 

        // set the timeout a little longer
        if ($verb = 'POST') {
            $curl_timeout = 60;
        }
        if(in_array('X-SNAP-Timeout', $headers)) {
            $curl_timeout = $headers['X-SNAP-Timeout'];
        }

        // merge and replace default headers with passed in parameter
        $headers = array_replace($defaultHeaders, $headers);
        // format the headers before appending
        $headersArray = array();
        foreach ($headers as $key => $value) {
            $headersArray[] = $key.': '.$value;
        }

        // set various curl parameters
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, $curl_timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);

        // execute the request and parse response
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $duration = curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000; // get the duration in ms
        curl_close($ch);

        // create the API call log message
        //logger.info('{0} {1} [{2}] ({3}) {4}'.format(request.method, response.status_code, duration, request.META['HTTP_ACCEPT'], request.path))
        $message = sprintf('[%s] - %s %s %s (%s) %s', $headers['User-Agent'], $verb, $httpcode, $duration, $headers['Accept'], $requestUrl);

        // log the API call
        $output = fopen('php://stdout', 'w');
        ob_start();
        fwrite($output, $message."\n");
        ob_end_flush();

        // return the response string and response code in an array
        return array(
            'response' => $response,
            'code' => $httpcode, // deprecated
            'status' => $httpcode, // preferred
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

    /**
     * A helper function to create a resource uri using a base resource name
     * and a resource primary key.
     */
    public static function resource_uri($resource_name, $resource_pk) {
        return '/'.self::$api_version.'/'.$resource_name.'/'.$resource_pk.'/';
    }

    /**
     * A helper function to get a resource pk using the resource uri.
     */
    public static function resource_pk($resource_uri) {
        // if the value is numeric return it
        if (is_numeric($resource_uri)) {
            return (int)$resource_uri;
        } else {
            // parse/return the pk if we have a pattern matching a resource URI
            // ie. '/<api_version>/<resource>/<pk>/'
            if(preg_match('/^\/(\w|[-])+\/(\w|[-])+\/(\d)+\/$/', $resource_uri)) {
                $parts = array_filter(explode('/', $resource_uri));
                return end($parts);
            }
        }
        return false;
    }
}