<?php
Class Signup_model extends CI_Model
{

	function getPackageDetails($url)
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
		$path = '/private_v1/package/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("c");
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/package/?format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                             
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response); 
			
			$json = '{
						"status": 404
					}';
			
			foreach ( $result->objects as $r )
			{
				
				if ( $r->short_name == $url)
				{
					// short_name, name, price, prints, album
					$json = '{
						"status": 200,
						"resource_uri": "' . $r->resource_uri . '",
						"short_name": "' . $r->short_name . '",
						"name": "' . $r->name . '",
						"price": "' . $r->price . '",
						"prints": "' . $r->prints . '",
						"albums": "' . $r->albums . '"
					}';
				}
			}
			
			return $json;
		} else {
			return '{
				"status": 404
			}';
		}
	}
	
	function checkEmail($email)
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
		$x_snap_date = gmdate("c");
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/user/?email=' . $email . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                   
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response);
			$return = $result->meta->total_count;
			
			//$status = '{ "status": 200 }';
			
			if ( $return > 0 )
			{
				$status = '{ "status": 200 }';
			} else {
				$status = '{ "status": 404 }';
			}
			//echo $return;
			return $status;
		} else {
			$status = '{ "status": 404 }';
		}
		return $status;
	}
	
	function checkUrl($url)
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
		$path = '/private_v1/event/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("c");
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/event/?url=' . $url . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                 
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response);
			$return = $result->meta->total_count;
			
			//$status = '{ "status": 200 }';
			
			if ( $return > 0 )
			{
				$status = '{ "status": 200 }';
			} else {
				$status = '{ "status": 404 }';
			}
			//echo $return;
			return $status;
		} else {
			$status = '{ "status": 404 }';
		}
		return $status;
	}
	
	function createEvent($event, $user)
	{
		// Charge Credit Card then do rest if it comes back ok
		/*
	    [user] => Array
	        (
	            [first_name] => Andrew
	            [last_name] => Draper
	            [email] => andrew.draper@gmail.com
	            [password] => golden123
	            [password_confirmation] => golden123
	        )
	        */
	    
	    // USER
	    $json = '{
			"billing_zip": "00000",
		    "email": "' . $user['email'] . '",
		    "first_name": "' . $user['first_name'] . '",
		    "last_name": "' . $user['last_name'] . '",
		    "password": "' . $user['password'] . '",
		    "terms": true
		}';
		
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = 'abc123';
		$api_secret = '123';
		$verb = 'POST';
		$path = '/private_v1/user/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("c");
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/user/'); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                            
		    'Content-Length: ' . strlen($json), 
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                   
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		
		if ( $httpcode == 201 )
		{
		
			$result = json_decode($response);
			$user_uri = $result->resource_uri;
			
			// EVENT
		    $min = 1000;
			$max = 9999;
			$event_pin = rand($min,$max);
			
			$start_timestamp = strtotime($event['start_date'] . " " . $event['start_time']);
		    $end_timestamp = strtotime($event['end_date'] . " " . $event['end_time']);
			$start = date( "Y-m-d", $start_timestamp ) . "T" . date( "H:i:s", $start_timestamp ); // formatted: 2010-11-10T03:07:43
			$end = date( "Y-m-d", $end_timestamp ) . "T" . date( "H:i:s", $end_timestamp ); 
			
			$created = date( "Y-m-d" ) . "T" . date( "H:i:s" );
			
			$json = '{
				"creation_date": "' . $created . '",
				"user": "' . $user_uri . '",
				"package": "/private_v1/package/5/",
				"title": "' . $event['title'] . '",
			    "url": "' . $event['url'] . '",
			    "start": "' . $start . '",
			    "end": "' . $end . '",
			    "pin": "' . $event_pin . '",
			    "type": "/private_v1/type/5/",
			    "enabled": true
			}';
			
			$path = '/private_v1/event/';
			$x_path_nonce = $nonce;
			$x_snap_date = gmdate("c");
			
			$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
			$signature = hash_hmac('sha1', $raw_signature, $api_secret);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/event/'); 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			    'Content-Type: application/json',                                                                            
			    'Content-Length: ' . strlen($json), 
			    'X-SNAP-Date: ' . $x_snap_date ,
			    'X-SNAP-nonce: ' . $x_path_nonce ,
			    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
			));                                                                   
			curl_setopt($ch, CURLOPT_TIMEOUT, '3'); 
			$response = curl_exec($ch);                                                                                
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if ( $httpcode == 201 )
			{
				
				$result = json_decode($response);
				$event_uri = $result->resource_uri; 
				
				// ADDRESS
				$json = '{
					"event": "' . $event_uri . '",
					"address": "' . $event['location'] . '",
					"lat": "' . $event['lat'] . '",
				    "lng": "' . $event['lng'] . '"
				}';
				
				$path = '/private_v1/address/';
				$x_path_nonce = $nonce;
				$x_snap_date = gmdate("c");
				
				$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
				$signature = hash_hmac('sha1', $raw_signature, $api_secret);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/address/'); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				    'Content-Type: application/json',                                                                            
				    'Content-Length: ' . strlen($json), 
				    'X-SNAP-Date: ' . $x_snap_date ,
				    'X-SNAP-nonce: ' . $x_path_nonce ,
				    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
				));                                                                   
				curl_setopt($ch, CURLOPT_TIMEOUT, '3'); 
				$response = curl_exec($ch);                                                                                
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);  
				
				if ( $httpcode == 201 )
				{
					$sess_array = array(
			          'email' => $user['email'],
			          'fname' => $user['first_name'],
			          'lname' => $user['last_name'],
			          'resource_uri' => $user_uri,
			          'loggedin' => true
			        );
			        $this->session->set_userdata('logged_in', $sess_array);
			        
					$result = json_decode($response);
				
					return 1;
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	} 

}