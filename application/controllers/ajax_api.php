<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_Api extends CI_Controller {

    public static $ALLOWED_VERBS = array('GET');
    public static $ALLOWED_RESOURCES = array('event', 'photo');
        
    function __construct() {
        parent::__construct();

        // always check if it's an AJAX call
        //if (!IS_AJAX) {
        //    show_error('Not a proper AJAX call.', 403);
        //}
    }

    public function index($path) {
        // setup the API call
        $verb = $_SERVER['REQUEST_METHOD'];        
        $path_str = implode('/', $path);
        $params = array();

        // if get, use query params
        if ($verb == 'GET') {
            $params = $_GET;
        }

        // call the API
        if (in_array($verb, self::$ALLOWED_VERBS) && in_array($path[0], self::$ALLOWED_RESOURCES)) {
            $resp = SnapApi::send($verb, $path_str, $params);
            $this->output->set_status_header($resp['code']);
            echo $resp['response'];
        } else {
            $this->output->set_status_header(403);
        }
    }

    /**
     * Use the _remap to override the path logic.
     * If the method exists in the controller, it takes precedence
     * over a path name. If the controller function doesn't exist,
     * use the path name and pass all the parameters in as well.
     */
    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            array_unshift($params, $method); // add the method as the first param
            $details = array(
                $params,
            );
            return call_user_func_array(array($this, 'index'), $details);
        }
    }

}