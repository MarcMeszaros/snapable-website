<?php
Class Signup_model extends CI_Model
{

	function getPackageDetails($url)
	{
		$verb = 'GET';
		$path = '/package/';
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
		$path = '/user/';
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
		$path = '/event/';
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
		$successResp = array();
	    
	    // USER
	    $email_address = $user['email'];

		$verb = 'POST';
		$path = '/user/';
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
		
		if ( $resp['code'] == 201 )
		{
			$result = json_decode($response);
			$user_uri = $result->resource_uri;
			$tempResult = json_decode($response, true);
			$account_uri = $tempResult['accounts'][0];

			// set the account/user id's
			$successResp['user'] = $user_uri;
			$successResp['account'] = $account_uri;
			
			// EVENT
		    $min = 1000;
			$max = 9999;
			$event_pin = rand($min,$max);
			
			//GET TIMEZONE 
			//$earth_uri  = "http://www.earthtools.org/timezone/" . $event['lat'] . "/" . $event['lng'];
			//$earth_response = simplexml_load_file($earth_uri);
			//$timezone_offset = $earth_response->offset;
			$timezone_offset_seconds = $event['tz_offset'] * 60;
			// SET TO UTC
			$start_timestamp = strtotime($event['start_date'] . " " . $event['start_time']) + ($timezone_offset_seconds);
			
			$start = gmdate( "c", $start_timestamp ); //date( "Y-m-d", $start_timestamp ) . "T" . date( "H:i:s", $start_timestamp ); // formatted: 2010-11-10T03:07:43 
			
			// CREATE END DATE
			if ( $event['duration_type'] == "days" )
			{
				$duration_in_seconds = $event['duration_num'] * 86400;
			} else {
				$duration_in_seconds = $event['duration_num'] * 3600;
			}
			$end_timestamp = $start_timestamp + $duration_in_seconds;
			$end = gmdate( "c", $end_timestamp );
			
			//$created = date( "Y-m-d" ) . "T" . date( "H:i:s" );
			
			$verb = 'POST';
			$path = '/event/';
			$params = array(
				"account" => $account_uri,
				"title" => $event['title'],
			    "url" => $event['url'],
			    "start" => $start,
			    "end" => $end,
			    "pin" => $event_pin,
			    "private" => true,
			    "enabled" => true,
			    "tz_offset" => $event['tz_offset'],
			);
			$resp = SnapApi::send($verb, $path, $params);
			$response = $resp['response'];
			
			if ( $resp['code'] == 201 )
			{

				$result = json_decode($response);
				$event_uri = $result->resource_uri;
				$successResp['event'] = $event_uri; 
				
				// ADDRESS
				$verb = 'POST';
				$path = '/address/';
				$params = array(
					"event" => $event_uri,
					"address" => $event['location'],
					"lat" => $event['lat'],
				    "lng" => $event['lng'],
				);
				$resp = SnapApi::send($verb, $path, $params);

				// add the user as the first guest
				$verb = 'POST';
				$path = '/guest/';
				$params = array(
					'event' => $event_uri,
					'type' => '/'.SnapApi::$api_version.'/type/1/',
					'email' => $user['email'],
				    'name' => $user['first_name'] . ' ' . $user['last_name'],
				);
				$resp = SnapApi::send($verb, $path, $params);

				if ( $resp['code'] == 201 )
				{
					// set sessions var to log user in
					SnapAuth::signin_nohash($user['email']);

					// SEND SIGN-UP NOTIFICATION EMAIL
					$to = 'team@snapable.com';
					$from = 'snapable@snapable.com';
					
					$subject = 'Say Cheese, a Snapable Sign-up!';
					$message_html = '<p><b>Woot!</b> ' . $email_address . ' just signed up to Snapable.</p><p>Their event starts ' . date( "Y-m-d", $start_timestamp ) . " @ " . date( "H:i:s", $start_timestamp ) . ' until ' . date( "Y-m-d", $end_timestamp ) . " @ " . date( "H:i:s", $end_timestamp ) . '.</p>';
					$message_text = 'Woot! ' . $email_address . ' just signed up to Snapable. Their event starts ' . date( "Y-m-d", $start_timestamp ) . " @ " . date( "H:i:s", $start_timestamp ) . ' until ' . date( "Y-m-d", $end_timestamp ) . " @ " . date( "H:i:s", $end_timestamp ) . '.';
					
					$this->email->from($from, 'Snapable');
					$this->email->to($to);
					$this->email->subject($subject);
					$this->email->message($message_html);
					$this->email->set_alt_message($message_text);		
					if (DEBUG == false) {
						$this->email->send();
					}
					
					return $successResp;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	} 

}