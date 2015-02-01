<?php
Class Account_model extends CI_Model
{

	function userDetails($email)
	{
		$verb = 'GET';
		$path = '/user/';
		$params = array(
			'email' => $email,
		);
		$resp = SnapApi::send($verb, $path, $params);
		$result = json_decode($resp['response']);
		
		if ( $resp['code'] == 200 && $result->meta->total_count > 0 ) {
			return json_encode(array(
				'status' => 200,
				'resource_uri' => $result->objects[0]->resource_uri,
				'password_algorithm' => $result->objects[0]->password_algorithm,
				'password_iterations' => $result->objects[0]->password_iterations,
			    'password_salt' => $result->objects[0]->password_salt,
			));
		} else {
			return json_encode(array(
				'status' => 404
			));
		}
	}

	function eventDeets($account_uri)
	{
		$verb = 'GET';
		$path = '/event/';
		$params = array(
			'account' => SnapApi::resource_pk($account_uri),
		);
		$resp = SnapApi::send($verb, $path, $params);                                                           
		$result = json_decode($resp['response']);
		
		if ( $resp['code'] == 200 )
		{
			return $array = array(
			    "status" => 200,
			    "resource_uri" => $result->objects[0]->resource_uri,
			    "title" => $result->objects[0]->title,
			    "url" => $result->objects[0]->url,
			    "pin" => $result->objects[0]->pin,
			    "start_timedate" => $result->objects[0]->start,
			    "start_epoch" => strtotime($result->objects[0]->start),
			    "owner_resource_uri" => $result->objects[0]->user
			);
		} else {
			return $array['status'] = 404;
		}
	}
	
	function doReset($user_id) {	
		$verb = 'POST';
		$path = '/user/' . $user_id . '/passwordreset/';
		$params = array(
			"url" => "https://snapable.com/account/reset/",
		);
		return SnapApi::send($verb, $path, $params);
	}

	function completeReset($password, $password_nonce) {
		$verb = 'PATCH';
		$path = '/user/passwordreset/';
		$params = array(
			"nonce" => $password_nonce,
			"password" => $password,
		);
		return SnapApi::send($verb, $path, $params);
	}

}
