<?php
Class Signup_model extends CI_Model
{

	function getPackageDetails($url)
	{
		$verb = 'GET';
		$path = '/private_v1/package/';
		$resp = SnapApi::send($verb, $path);

		$response = $resp['response'];
		$httpcode = $resp['code'];
		
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
		$verb = 'GET';
		$path = '/private_v1/user/';
		$params = array(
			'email' => $email,
		);
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		$httpcode = $resp['code'];

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
		$verb = 'GET';
		$path = '/private_v1/event/';
		$params = array(
			'url' => $url,
		);
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		$httpcode = $resp['code'];
		
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
	    $email_address = $user['email'];

		$verb = 'POST';
		$path = '/private_v1/user/';
		$params = array(
			"billing_zip" => "00000",
		    "email" => $user['email'],
		    "first_name" => $user['first_name'],
		    "last_name" => $user['last_name'],
		    "password" => $user['password'],
		    "terms" => true,
		);
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		$httpcode = $resp['code'];
		
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
			$start = gmdate( "c", $start_timestamp ); //date( "Y-m-d", $start_timestamp ) . "T" . date( "H:i:s", $start_timestamp ); // formatted: 2010-11-10T03:07:43
			$end = gmdate( "c", $end_timestamp ); //date( "Y-m-d", $end_timestamp ) . "T" . date( "H:i:s", $end_timestamp ); 
			
			$created = date( "Y-m-d" ) . "T" . date( "H:i:s" );
			
			$verb = 'POST';
			$path = '/private_v1/event/';
			$params = array(
				"creation_date" => $created,
				"user" => $user_uri,
				"package" => "/private_v1/package/1/",
				"title" => $event['title'],
			    "url" => $event['url'],
			    "start" => $start,
			    "end" => $end,
			    "pin" => $event_pin,
			    "type" => "/private_v1/type/5/",
			    "enabled" => true,
			);
			$resp = SnapApi::send($verb, $path, $params);

			$response = $resp['response'];                                                                                
			$httpcode = $resp['code'];
			
			if ( $httpcode == 201 )
			{

				$result = json_decode($response);
				$event_uri = $result->resource_uri; 
				
				// ADDRESS
				$verb = 'POST';
				$path = '/private_v1/address/';
				$params = array(
					"event" => $event_uri,
					"address" => $event['location'],
					"lat" => $event['lat'],
				    "lng" => $event['lng'],
				);
				$resp = SnapApi::send($verb, $path, $params);
				
				$response = $resp['response'];                                                                                
				$httpcode = $resp['code'];

				// add the user as the first guest
				$verb = 'POST';
				$path = '/private_v1/guest/';
				$params = array(
					'event' => $event_uri,
					'type' => '/private_v1/type/1/',
					'email' => $user['email'],
				    'name' => $user['first_name'] . ' ' . $user['last_name'],
				);
				$resp = SnapApi::send($verb, $path, $params);
				$httpcode = $resp['code'];

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
					
					// SEND SIGN-UP NOTIFICATION EMAIL
	
					$url = 'http://sendgrid.com/';
					$user = 'snapable';
					$pass = 'Snapa!23'; 
					
					$to = 'team@snapable.com';
					$from = 'Snapable@snapable.com';
					
					$subject = 'Say Cheese, a Snapable Sign-up!';
					$message_html = '<p><b>Woot!</b> ' . $email_address . ' just signed up to Snapable.</p><p>Their event starts ' . date( "Y-m-d", $start_timestamp ) . " @ " . date( "H:i:s", $start_timestamp ) . ' until ' . date( "Y-m-d", $end_timestamp ) . " @ " . date( "H:i:s", $end_timestamp ) . '.</p>';
					$message_text = 'Woot! ' . $email_address . ' just signed up to Snapable. Their event starts ' . date( "Y-m-d", $start_timestamp ) . " @ " . date( "H:i:s", $start_timestamp ) . ' until ' . date( "Y-m-d", $end_timestamp ) . " @ " . date( "H:i:s", $end_timestamp ) . '.';
					
					$params = array(
					    'api_user'  => $user,
					    'api_key'   => $pass,
					    'to'        => $to,
					    'subject'   => $subject,
					    'html'      => $message_html,
					    'text'      => $message_text,
					    'from'      => $from,
					  );
					
					$request =  $url.'api/mail.send.json';
					
					// Generate curl request
					$session = curl_init($request);
					// Tell curl to use HTTP POST
					curl_setopt ($session, CURLOPT_POST, true);
					// Tell curl that this is the body of the POST
					curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
					// Tell curl not to return headers, but do return the response
					curl_setopt($session, CURLOPT_HEADER, false);
					curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
					
					// obtain response
					$response = json_decode(curl_exec($session));
					curl_close($session);
					
					echo "sent";
					
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