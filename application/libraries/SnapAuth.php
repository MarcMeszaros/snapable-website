<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SnapAuth class
 *
 * Note: In the case of PBKDF2 Django encodes the hash in base64
 */
class SnapAuth {

    // signin

    // signout

    // validate
    public function validate($email, $hash)
    {
        $verb = 'GET';
        $path = '/user/';
        $params = array();
        $headers = array(
            'x-SNAP-User' => $email . ':' . $hash,
        );
        $resp = SnapApi::send($verb, $path, $params, $headers);
        
        $response = $resp['response'];
        $httpcode = $resp['code'];

        //return $response;
        
        if ( $httpcode == 200 )
        {
            $result = json_decode($response);
            
            if ( $result->meta->total_count > 0 )
            {
                $returnData = array(
                    'status' => 200,
                    'email' => $result->objects[0]->email,
                    'fname' => $result->objects[0]->first_name,
                    'lname' => $result->objects[0]->last_name,
                    'resource_uri' => $result->objects[0]->resource_uri,
                    'account_uri' => $result->objects[0]->accounts[0],
                );
                return json_encode($returnData);
            } else {
                return json_encode(array('status' => 404));
            }
        } else {
            return json_encode(array('status' => 404));
        }
    }

    // calculate the pbkdf2 password hash
    public function snap_pbkdf2($algorithm, $password, $salt, $count)
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