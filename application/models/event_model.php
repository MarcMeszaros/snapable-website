<?php
Class Event_model extends CI_Model
{

	function getEventDetailsFromURL($url)
	{
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = API_KEY;;
		$api_secret = API_SECRET;
		$verb = 'GET';
		$path = '/private_v1/event/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_HOST . '/private_v1/event/?url=' . $url . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                   
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$response = str_replace("false", "\"0\"", $response);
		$response = str_replace("true", "\"1\"", $response);
		$result = json_decode($response);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 && $result->meta->total_count > 0 )
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
			            "creation_date": "2012-08-01T18:37:05+00:00",
			            "enabled": true,
			            "end": "2012-08-01T19:00:00+00:00",
			            "package": "/private_v1/package/1/",
			            "pin": "3388",
			            "resource_uri": "/private_v1/event/4/",
			            "start": "2012-08-01T15:00:00+00:00",
			            "title": "Ribfest",
			            "url": "ribfest",
			            "user": "/private_v1/user/68/"
			        }
			    ]
			}
			*/
			
			$event = "";
			foreach ( $result->objects as $e )
			{
				// if start and end dates are the same day $display_timedate is in the format "Tue July 31, 7-9 PM"
				// if start and end dates are different days $display_timedate is in the format "Tue July 31, 7 PM to Thu Aug 2, 9PM"
				$start_epoch = strtotime($e->start);
				$end_epoch = strtotime($e->end);
				
				$eventID = explode("/", $e->resource_uri);
				
				if ( date("m-d", $start_epoch) == date("m-d", $end_epoch) )
				{
					$display_timedate = date("D M j", $start_epoch) . ", " . date("g:i A", $start_epoch) . " - " . date("g:i A", $end_epoch);
				} else {
					$display_timedate = date("D M j, g:i A", $start_epoch) . " to " . date("D M j, g:i A", $end_epoch);
				}
				
				// check if there's any photos
				
				$length = 8;
				$nonce = "";
				while ($length > 0) {
				    $nonce .= dechex(mt_rand(0,15));
				    $length -= 1;
				}
				
				$path = '/private_v1/photo/';
				$x_path_nonce = $nonce;
				$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
				
				$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
				$signature = hash_hmac('sha1', $raw_signature, $api_secret);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, API_HOST . '' . $path . '?event=2&format=json'); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				    'Content-Type: application/json',
				    'X-SNAP-Date: ' . $x_snap_date ,
				    'X-SNAP-nonce: ' . $x_path_nonce ,
				    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
				));                                                                   
				curl_setopt($ch, CURLOPT_TIMEOUT, '3');
				                                                                                                    
				$response = curl_exec($ch);
				$result = json_decode($response);
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				
				$event .= '{
					"enabled":' . $e->enabled . ',
					"url": "' . $e->url . '",
					"title": "' . $e->title . '",
					"pin": "' . $e->pin . '",
					"package": "' . $e->package . '",
					"start": "' . $e->start . '",
					"end": "' . $e->end . '",
					"start_epoch": "' . $start_epoch . '",
					"end_epoch": "' . $end_epoch . '",
					"display_timedate": "' . $display_timedate . '",
					"resource_uri": "' . $e->resource_uri . '",
					"user": "' . $e->user . '",
					"privacy": 3,
					"photos": "' . $e->photo_count . '"
				}';
			}
			$json = '{
				"status": 200,
				"event": ' . $event . '
			}';
			return $json;
		} else {
			$json = '{
				"status": 404,
				"event": {
					"enabled":0,
					"url": "' . $url . '"
				}
			}';
			return $json;
		}
		//$details = $this->db->where('url', $url)->where('active', 1)->get('event', 1,0);
		// check if there's a positive result
	}
	
	function getEventsPhotos($id)
	{
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = API_KEY;;
		$api_secret = API_SECRET;
		$verb = 'GET';
		$path = '/private_v1/photo/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_HOST . '' . $path . '?event=' . $id . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                  
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		
		return '{
					"status": 200,
					"response": ' . $response . '
				}';
	}
	
	function validateGuest($details, $email, $pin)
	{
		// check events privacy setting, if email address is in guest list and if PIN matches event's pin
		$privacy = $details->event->privacy;
		$pin = $details->event->pin;
		$eventID = explode("/", $details->event->resource_uri);
		
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = API_KEY;;
		$api_secret = API_SECRET;
		$verb = 'GET';
		$path = '/private_v1/guest/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_HOST . '/private_v1/guest/?email=' . $email . '&event=' . $eventID[3] . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                  
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$response = str_replace("false", "\"0\"", $response);
		$response = str_replace("true", "\"1\"", $response);
		$result = json_decode($response);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 && $result->meta->total_count > 0 )
		{
			$json = '{
				"status": 200,
				"name": "' . $result->objects[0]->name . '",
				"type": "3"
			}';
		} else {
			$json = '{
				"status": 404
			}';
		}
		return $json;
	}
	
	
	function guestCount($resource_uri)
	{
		$event = explode("/", $resource_uri);
		$eventID = $event[3];
		
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = API_KEY;;
		$api_secret = API_SECRET;
		$verb = 'GET';
		$path = '/private_v1/guest/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("c");
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_HOST . '/private_v1/guest/?event=' . $eventID . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                  
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$result = json_decode($response);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$num = $result->meta->total_count;
		} else {
			$num = 0;
		}
		return $num;
	}
	
	
	function addGuests($post)
	{
		ini_set('auto_detect_line_endings', true);
		
		$server_path = $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/";
		$event_uri = $post['event'];
		$file = $post['file'];
		$col1 = $post['col1'];
		$col2 = $post['col2'];
		$col3 = $post['col3'];
		
		$event_id = explode("/", $event_uri);
		/*
		Array
		(
		    [file] => 1346536205-emailsample.csv
		    [col1] => name
		    [col2] => email
		    [col3] => type
		)
		*/
		
		// GET LIST OF CURRENT GUESTS
		
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = API_KEY;;
		$api_secret = API_SECRET;
		$verb = 'GET';
		$path = '/private_v1/guest/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("c");
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_HOST . '/private_v1/guest/?event=' . $event_id[3] . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
		    'X-SNAP-Date: ' . $x_snap_date ,
		    'X-SNAP-nonce: ' . $x_path_nonce ,
		    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
		));                                                                  
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		                                                                                                    
		$response = curl_exec($ch);
		$result = json_decode($response);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ( $httpcode == 200 )
		{
			$existing_guests = $result->meta->total_count;
			if ( $existing_guests > 0 )
			{
				$emails = array();
				foreach ( $result->objects as $g )
				{
					$emails[] = $g->email;
				}
					
				$server_path = $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/";
			    $row_data = "";
				$guest_count = 0;
				
				if (($handle = fopen($server_path . $file, "r")) !== FALSE) 
				{
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
					{
						$num = count($data);
					    $numnum = 1;
						$email = "unknown";
						$name = "unknown";
				        $type_uri = "/private_v1/type/5/";
					    $row_data .= "{";
					    $empty = false;
						
						for ($c=0; $c < $num; $c++) 
						{
							if ( $numnum <= 3 && strtolower($data[$c]) != "name" && strtolower($data[$c]) != "email" && strtolower($data[$c]) != "type" && strtolower($data[$c]) != "email address" && strtolower($data[$c]) != "guest type" && strtolower($data[$c]) != "guest_type" && strtolower($data[$c]) != "guest-type" )
						    {
								if( preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $data[$c]) > 0)
				        	    {
				        	    	// is email
				        	    	$email = $data[$c];
				        	    } else {
					        	    // isn't, check if guest
					        	    $types = array("organizer","bride/groom","wedding party","family","guest"); // SWITCH TO FILL ARRAY FROM API AND ENSURE THEY'RE LOWERCASE!!!!
					        	    if ( in_array(strtolower($data[$c]), $types) )
					        	    {
					        	    	// is guest type
					        	    	$type = strtolower($data[$c]);
					        	    	// convert type into resource_uri
						        	    switch ($type) {
										    case "organizer":
										        $type_uri = "/private_v1/type/1/";
										        break;
										    case "bride/groom":
										        $type_uri = "/private_v1/type/2/";
										        break;
										    case "wedding party":
										        $type_uri = "/private_v1/type/3/";
										        break;
										    case "family":
										        $type_uri = "/private_v1/type/4/";
										        break;
										    case "guest":
										        $type_uri = "/private_v1/type/5/";
										        break;
										}
					        	    } else {
					        	    	// isn't guest or email, must be a name
						        	    $name = $data[$c];
					        	    }
				        	    }
							}
						}
						
						if ( $email != "unknown" )
						{
							if ( !in_array($email, $emails))
							{
				        	    $json = '{
									"email": "' . $email . '",
									"event": "' . $event_uri . '",
									"name": "' . $name . '",
									"type": "' . $type_uri . '"
								}';
								
								// if email address is not already a guest add
								
								$verb = 'POST';
								$x_path_nonce = $nonce;
								$x_snap_date = gmdate("c");
								
								$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
								$signature = hash_hmac('sha1', $raw_signature, $api_secret);
								
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, API_HOST . '/private_v1/guest/'); 
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
									$guest_count++;
								}
							}
						}
					}
				    fclose($handle);
				    
				    $rows = substr($row_data, 0, -1);
					
					unlink($server_path . $file);
					
					return '{
						"status": 200,
						"guest_count": ' . $guest_count . ',
						"row_count": ' . $num . '
					}';
				}
			} else {
				return '{
					"status": 200,
					"guest_count": 0
				}';
			}
		} else {
			return '{ "status": 404 }';
		}
	}
	
	
	function sendInvite($post)
	{
		if ( isset($post['message']) && isset($post['sendTo']) )
		{
			$message = $post['message'];
			$sendTo = $post['sendto'];
			
			$privacy_level = max($sendTo);
			
			// get guests
			
			$length = 8;
			$nonce = "";
			while ($length > 0) {
			    $nonce .= dechex(mt_rand(0,15));
			    $length -= 1;
			}
			
			$api_key = API_KEY;;
			$api_secret = API_SECRET;
			$verb = 'GET';
			$path = '/private_v1/guest/';
			$x_path_nonce = $nonce;
			$x_snap_date = gmdate("c");
			
			$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
			$signature = hash_hmac('sha1', $raw_signature, $api_secret);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, API_HOST . '/private_v1/guest/?event=' . $event_id[3] . '&format=json'); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			    'Content-Type: application/json',
			    'X-SNAP-Date: ' . $x_snap_date ,
			    'X-SNAP-nonce: ' . $x_path_nonce ,
			    'Authorization: SNAP ' . $api_key . ':' . $signature                                                                       
			));                                                                  
			curl_setopt($ch, CURLOPT_TIMEOUT, '3');
			                                                                                                    
			$response = curl_exec($ch);
			$result = json_decode($response);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if ( $httpcode == 200 && $result->meta->total_count > 0 )
			{
				$url = 'http://sendgrid.com/';
				$user = 'snapable';
				$pass = 'Snapa!23'; 
				
				if ( isset($_POST['from']) ) {
					$from = $_POST['from'];
				} else {
					$from = "website@snapable.com";
				}
				
				$subject = 'At [EVENT NAME] use Snapable!';
				
				
				if ( isset($_POST['to']) ) {
					$to = $_POST['to'];
				} else {
					$to = "team@snapable.com";
				}
				
				$message_html = '<p><b>Message:</b></p><p>' . $_POST['message'] . '</p><p>Sent from: ' . $_POST['email'] . '</p>';
				$message_text = 'Message: ' . $_POST['message'] . ' / Sent from: ' . $_POST['email'];
				
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
				
				return 'sent';
			} else {
				return 'failed';
			}
		} else {
			return 'failed';
		}
	}
	

}