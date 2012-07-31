<?php
Class Buy_model extends CI_Model
{

	function getPackageDetails($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/package/?format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                               
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
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/user/?email=' . $email . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                               
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
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/event/?url=' . $url . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                               
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
	
	function createEvent($event, $user, $cc, $address)
	{
		// Charge Credit Card then do rest if it comes back ok
		
	        /*
	    [cc] => Array
	        (
	            [name] => Andrew Draper
	            [number] => 4111111111111111
	            [month] => 6
	            [year] => 2012
	            [verification_value] => 123
	        )
	        */
	        
	     /*
	    [user] => Array
	        (
	            [first_name] => Andrew
	            [last_name] => Draper
	            [email] => andrew.draper@gmail.com
	            [password] => golden123
	            [password_confirmation] => golden123
	        )
	     [address] => Array
	        (
	            [zip] => K2W 1G8
	        )
	    [terms] => on
	        */
	    
	    // USER
	    $json = '{
			"billing_zip": "' . $address['zip'] . '",
		    "email": "' . $user['email'] . '",
		    "first_name": "' . $user['first_name'] . '",
		    "last_name": "' . $user['last_name'] . '",
		    "password": "' . $user['password'] . '",
		    "terms": true
		}';
		
		$ch = curl_init('http://devapi.snapable.com/private_v1/user/');                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Content-Length: ' . strlen($json))                                                                       
		);                                                                                                                   
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 201 )
		{
		
			$result = json_decode($response);
			$user_uri = $result->resource_uri;
			
			/*
			 [event] => Array
		        (
		            [package] => 1
		            [lat] => 45.355772
		            [lng] => -75.938238
		            [title] => Big Awesome Event
		            [start_date] => Jun 27, 2012
		            [start_time] => 06:00 PM
		            [end_date] => Jun 27, 2012
		            [end_time] => 10:00 PM
		            [location] => 1110 Halton Terrace, Kanata, ON
		            [url] => big-awesome-event
		        )
		     */
		     
		    // EVENT
		    $min = 1000;
			$max = 9999;
			$event_pin = rand($min,$max);
			
			$start_timestamp = strtotime($event['start_date'] . " " . $event['start_time']);
		    $end_timestamp = strtotime($event['end_date'] . " " . $event['end_time']);
			$start = date( "Y-m-d", $start_timestamp ) . "T" . date( "H:i:s", $start_timestamp ); // formatted: 2010-11-10T03:07:43
			$end = date( "Y-m-d", $end_timestamp ) . "T" . date( "H:i:s", $end_timestamp ); 
			
			$json = '{
				"user": "' . $user_uri . '",
				"package": "/private_v1/package/' . $event['package'] . '/",
				"title": "' . $event['title'] . '",
			    "url": "' . $event['url'] . '",
			    "start": "' . $start . '",
			    "end": "' . $end . '",
			    "pin": "' . $event_pin . '",
			    "enabled": true
			}';
			
			$ch = curl_init('http://devapi.snapable.com/private_v1/event/');                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			    'Content-Type: application/json',                                                                                
			    'Content-Length: ' . strlen($json))                                                                       
			);                                                                                                                                
			 
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
				
				$ch = curl_init('http://devapi.snapable.com/private_v1/address/');                                                                      
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				    'Content-Type: application/json',                                                                                
				    'Content-Length: ' . strlen($json))                                                                       
				);                                                                                                   
				
				$response = curl_exec($ch);
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				
				if ( $httpcode == 201 )
				{
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