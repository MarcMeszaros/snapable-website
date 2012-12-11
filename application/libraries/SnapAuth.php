<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SnapAuth class
 */
class SnapAuth {

    // signin
    public function signin($email, $hash=null)
    {
        $user = self::validate($email, $hash);
        if ($user) {
            $sess_array = array(
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_uri' => $user->resource_uri,
                'account_uri' => $user->accounts[0],

                // TODO deprecated (look through the code and try and stop using these)
                'fname' => $user->first_name,
                'lname' => $user->last_name,
                'resource_uri' => $user->resource_uri,
                'loggedin' => true,
            );
            $this->session->set_userdata('logged_in', $sess_array);
            return true;
        } else {
            return false;
        }
    }

    public function signin_nohash($email)
    {
        $verb = 'GET';
        $path = '/user/auth/';
        $params = array(
            'email' => $email,
        );
        $resp = SnapApi::send($verb, $path, $params);
        $users = json_decode($resp['response']);

        if ($resp['code'] == 200 && $users->meta->total_count > 0) {
            $sess_array = array(
                'email' => $users->objects[0]->email,
                'first_name' => $users->objects[0]->first_name,
                'last_name' => $users->objects[0]->last_name,
                'user_uri' => $users->objects[0]->resource_uri,
                'account_uri' => $users->objects[0]->accounts[0],

                // TODO deprecated (look through the code and try and stop using these)
                'fname' => $users->objects[0]->first_name,
                'lname' => $users->objects[0]->last_name,
                'resource_uri' => $users->objects[0]->resource_uri,
                'loggedin' => true,
            );
            $this->session->set_userdata('logged_in', $sess_array);
            return true;
        } else {
            return false;
        }
    }

    // signout
    public function signout()
    {
        $this->session->unset_userdata('logged_in');
    }

    // check if logged in
    public function is_logged_in()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in && $logged_in['loggedin']) {
            return true;
        } else {
            return false;
        }
    }

    // validate
    public function validate($email, $hash)
    {
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
    public function snap_hash($email, $password)
    {
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

    // calculate the pbkdf2 password hash
    private function snap_pbkdf2($algorithm, $password, $salt, $count)
    {
        if (isset($algorithm) && $algorithm == 'pbkdf2_sha256') {
            $algorithm = 'sha256';
        }

        return base64_encode(self::pbkdf2($algorithm, $password, $salt, $count, 32, true));
    }

    // internal class helper stuff
    private function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
    {
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