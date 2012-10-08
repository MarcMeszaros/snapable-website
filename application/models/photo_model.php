<?php
Class Photo_model extends CI_Model
{

	function deets($id)
	{
		$url = API_HOST . "/private_v1/photo/" . $id . "/";

		$verb = 'GET';
		$path = '/private_v1/photo/' . $id . '/';
		$resp = SnapApi::send($verb, $path);

		$response = $resp['response'];
		$httpcode = $resp['code'];
		
		$request = json_decode($response);
		
		if ( $httpcode == 200 )
		{
			$status = 200;
			
			// GET EVENT NAME
			$event = explode("/", $request->event);

			$verb = 'GET';
			$path = '/private_v1/event/' . $event[3] . '/';
			$resp = SnapApi::send($verb, $path);

			$response = $resp['response'];
			$response = str_replace("false", "\"0\"", $response);
			$response = str_replace("true", "\"1\"", $response);
			$result = json_decode($response);
			$httpcode = $resp['code'];
			
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