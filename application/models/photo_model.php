<?php
Class Photo_model extends CI_Model
{

	function deets($id)
	{
		$url = "http://devapi.snapable.com/private_v1/photo/" . $id . "/";
			
		$length = 8;
		$nonce = "";
		while ($length > 0) {
		    $nonce .= dechex(mt_rand(0,15));
		    $length -= 1;
		}
		
		$api_key = 'abc123';
		$api_secret = '123';
		$verb = 'GET';
		$path = '/private_v1/photo/' . $id . '/';
		$x_path_nonce = $nonce;
		$x_snap_date = gmdate("c");
		
		$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
		$signature = hash_hmac('sha1', $raw_signature, $api_secret);
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'X-SNAP-Date: ' . $x_snap_date ,
			'X-SNAP-nonce: ' . $x_path_nonce ,
			'Authorization: SNAP ' . $api_key . ':' . $signature 
    	));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		$request = json_decode($response);
		
		if ( $httpcode == 200 )
		{
			$status = 200;
			
			// GET EVENT NAME
			$event = explode("/", $request->event);
			
			$length = 8;
			$nonce = "";
			while ($length > 0) {
			    $nonce .= dechex(mt_rand(0,15));
			    $length -= 1;
			}
			
			$api_key = 'abc123';
			$api_secret = '123';
			$verb = 'GET';
			$path = '/private_v1/event/' . $event[3] . '/';
			$x_path_nonce = $nonce;
			$x_snap_date = gmdate("c");
			
			$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
			$signature = hash_hmac('sha1', $raw_signature, $api_secret);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://devapi.snapable.com/private_v1/event/' . $event[3] . '/'); 
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
			
			if ( $httpcode == 200 )
			{
				$name = $result->title;
				$url = $result->url;
			} else {
				$name = "Unknown";
				$url="/404";
			}
			
			$_taken = strtotime($request->timestamp);
			$taken = date("F j", $_taken);
			
			$deets = ',"id": ' . $id . ',
			"caption": "' . $request->caption . '",
			"photographer": "' . $request->author_name . '",
			"date": "' . $taken . '",
			"event_url": "' . $url . '",
			"event_name": "' . $name . '"';
		} else {
			$status = 404;
			$deets = "";
		}
		
		return '{
			"status": ' . $status . $deets . '
		}';
	}
	
}