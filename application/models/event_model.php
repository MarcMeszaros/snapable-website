<?php
Class Event_model extends CI_Model
{

	function getEventDetailsFromURL($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/event/?url=' . $url . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                 
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
				
				if ( date("m-d", $start_epoch) == date("m-d", $end_epoch) )
				{
					$display_timedate = date("D M j", $start_epoch) . ", " . date("g:i A", $start_epoch) . " - " . date("g:i A", $end_epoch);
				} else {
					$display_timedate = date("D M j, g:i A", $start_epoch) . " to " . date("D M j, g:i A", $end_epoch);
				}
				
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
					"privacy": 3
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
	
	function validateGuest($details, $email, $pin)
	{
		// check events privacy setting, if email address is in guest list and if PIN matches event's pin
		$privacy = $details->event->privacy;
		$pin = $details->event->pin;
		$eventID = explode("/", $details->event->resource_uri);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/guest/?email=' . $email . '&event=' . $eventID[3] . '&format=json'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                 
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
				"name": "' . $result->objects[0]->name . '"
			}';
		} else {
			$json = '{
				"status": 404
			}';
		}
		return $json;
	}

}