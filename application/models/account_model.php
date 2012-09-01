<?php
Class Account_model extends CI_Model
{

	function userDetails($email)
	{
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = 'abc123';
		$api_secret = '123';
		$verb = 'GET';
		$path = '/private_v1/user/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("gis", time()) . 'Z';
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com' . $path . '?email=' . $email . '&format=json'); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                  
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                               
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response); 
			if ( $result->meta->total_count > 0 )
			{
				/*
				{
				    "meta": {
				        "limit": 50,
				        "next": null,
				        "offset": 0,
				        "previous": null,
				        "total_count": 1
				    },
				    "objects": [
				        {
				            "billing_zip": "K2W 1G8",
				            "email": "andrew@hashbrown.ca",
				            "first_name": "Andrew",
				            "last_name": "Draper",
				            "password_algorithm": "pbkdf2_sha256",
				            "password_iterations": "10000",
				            "password_salt": "qsdzj7DWkA4J",
				            "resource_uri": "/private_v1/user/67/",
				            "terms": true
				        }
				    ]
				}
				*/
				return '{
					"status": 200,
					"password_algorithm": "' . $result->objects[0]->password_algorithm . '",
					"password_iterations": "' . $result->objects[0]->password_iterations . '",
				    "password_salt": "' . $result->objects[0]->password_salt . '"
				}';
			} else {
				return '{
					"status": 404
				}';
			}
		} else {
			return '{
				"status": 404
			}';
		}
	}
	
	function checkPassword($email,$hash)
	{
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = 'abc123';
		$api_secret = '123';
		$verb = 'GET';
		$path = '/private_v1/user/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("gis", time()) . 'Z';
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init('http://devapi.snapable.com' . $path);                                                                
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature ,
		    'X-SNAP-User: ' . $email . ':' . $hash                                                                       
		)); 
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response);
			if ( $result->meta->total_count > 0 )
			{
				return '{
					"status": 200,
					"email": "' . $result->objects[0]->email . '",
					"fname": "' . $result->objects[0]->first_name . '",
					"lname": "' . $result->objects[0]->last_name . '",
					"resource_uri": "' . $result->objects[0]->resource_uri . '"
				}';	
			} else {
				return '{
					"status": 404
				}';
			}
		} else {
			return '{
				"status": 404
			}';
		}
	}
	
	function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
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
	
	
	function eventDeets($resource_uri)
	{
		$uri_split = explode("/", $resource_uri);
		
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = 'abc123';
		$api_secret = '123';
		$verb = 'GET';
		$path = '/private_v1/event/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("gis", time()) . 'Z';
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/event/?user=' . $uri_split[3] . '&format=json');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                               
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response);
			return $array = array(
			    "status" => 200,
			    "title" => $result->objects[0]->title,
			    "url" => $result->objects[0]->url,
			    "start_timedate" => $result->objects[0]->start,
			    "start_epoch" => strtotime($result->objects[0]->start)
			);
		} else {
			return $array['status'] = 404;
		}
	}
	
}