<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SnapAuth class
 */
class SnapAuth {

    public static function getInstance() {
        return get_instance();
    }

    // signin
    public static function signin($email, $hash=null) {
        $user = self::validate($email, $hash);
        if ($user) {
            $sess_array = array(
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_uri' => $user->resource_uri,
                'account_uri' => $user->accounts[0],

                // TODO deprecated (look through the code and try and stop using these)
                'resource_uri' => $user->resource_uri,
                'loggedin' => true,
            );
            self::getInstance()->session->set_userdata('logged_in', $sess_array);
            return $sess_array;
        } else {
            return false;
        }
    }

    // signin (no network/API call: use a user API response object)
    public static function signin_nonetwork($user) {
        if (isset($user)) {
            $sess_array = array(
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_uri' => $user->resource_uri,
                'account_uri' => $user->accounts[0],

                // TODO deprecated (look through the code and try and stop using these)
                'resource_uri' => $user->resource_uri,
                'loggedin' => true,
            );
            self::getInstance()->session->set_userdata('logged_in', $sess_array);
            return $sess_array;
        } else {
            return false;
        }
    }

    // signout
    public static function signout() {
        self::getInstance()->session->unset_userdata('logged_in');
    }

    // check if logged in
    public static function is_logged_in() {
        $logged_in = self::getInstance()->session->userdata('logged_in');
        if ($logged_in && $logged_in['loggedin']) {
            return $logged_in;
        } else {
            return false;
        }
    }

    // validate
    public static function validate($email, $hash) {
        $verb = 'GET';
        $path = '/user/auth/';
        $params = array();
        $headers = array(
            'x-SNAP-User' => $email . ':' . $hash,
        );
        $resp = SnapApi::send($verb, $path, $params, $headers);

        return ($resp['code'] == 200) ? json_decode($resp['response']) : false;
    }

    // calculate the password hash
    public static function snap_hash($email, $password) {
        // get the user by email
        $verb = 'GET';
        $path = '/user/';
        $params = array(
            'email' => $email,
        );
        $resp = SnapApi::send($verb, $path, $params);
        $users = json_decode($resp['response']);

        // if we found a user
        if ($resp['code'] == 200 && $users->meta->total_count > 0) {
            return self::snap_pbkdf2($users->objects[0]->password_algorithm, $password, $users->objects[0]->password_salt, $users->objects[0]->password_iterations);
        } else {
            return false;
        }
    }

    //***** GUEST *****\\
    // guest signin
    public static function guest_signin($email, $event) {
        $guests = self::guest_validate($email, $event);
        if ($guests) {
            $guestParts = explode('/', $guests->objects[0]->resource_uri);
            $sess_array = array(
                'id' => $guestParts[3],
                'name' => $guests->objects[0]->name,
                'email' => $email,
                'loggedin' => true
            );
            self::getInstance()->session->set_userdata('guest_login', $sess_array);
            return $sess_array;
        } else {
            return false;
        }
    }

    // guest singin (no network/API call: use a guest API response object)
    public static function guest_signin_nonetwork($guest) {
        if (isset($guest)) {
            $guestParts = explode('/', $guest->resource_uri);
            $sess_array = array(
                'id' => $guestParts[3],
                'name' => $guests->objects[0]->name,
                'email' => $email,
                'loggedin' => true
            );
            self::getInstance()->session->set_userdata('guest_login', $sess_array);
            return $sess_array;
        } else {
            return false;
        }
    }

    // guest signout
    public static function guest_signout() {
        self::getInstance()->session->unset_userdata('guest_login');
    }

    // check if guest is logged in
    public static function is_guest_logged_in() {
        $logged_in = self::getInstance()->session->userdata('guest_login');
        if ($logged_in && $logged_in['loggedin']) {
            return $logged_in;
        } else {
            return false;
        }
    }

    // guest validate
    public static function guest_validate($email, $event) {
        $eventParts = explode('/', $event);
        $verb = 'GET';
        $path = '/guest/';
        $params = array(
            'email' => $email,
            'event' => $eventParts[3],
        );
        $resp = SnapApi::send($verb, $path, $params);
        $response = json_decode($resp['response']);

        return ($resp['code'] == 200 && $response->meta->total_count > 0) ? $response : false;
    }

    //***** INTERNAL *****\\
    // calculate the pbkdf2 password hash
    private static function snap_pbkdf2($algorithm, $password, $salt, $count) {
        if (isset($algorithm) && $algorithm == 'pbkdf2_sha256') {
            $algorithm = 'sha256';
        }

        return base64_encode(self::pbkdf2($algorithm, $password, $salt, $count, 32, true));
    }

    // internal class helper stuff
    private static function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false) {
        $algorithm = strtolower($algorithm);
        if(!in_array($algorithm, hash_algos(), true))
            die('PBKDF2 ERROR: Invalid hash algorithm.');
        if($count <= 0 || $key_length <= 0)
            die('PBKDF2 ERROR: Invalid parameters.');
    
        $hash_length = strlen(hash($algorithm, "", true));
        $block_count = ceil($key_length / $hash_length);
    
        $output = "";
        for($i = 1; $i <= $block_count; $i++) {
            // $i encoded as 4 bytes, big endian.
            $last = $salt . pack("N", $i);
            // first iteration
            $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
            // perform the other $count - 1 iterations
            for ($j = 1; $j < $count; $j++) {
                $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
            }
            $output .= $xorsum;
        }
    
        if($raw_output)
            return substr($output, 0, $key_length);
        else
            return bin2hex(substr($output, 0, $key_length));
    }

}