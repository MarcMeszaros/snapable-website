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
		                                                                                                    
		$response = $resp['response'];
		$httpcode = $resp['code'];
		
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
					"resource_uri": "' . $result->objects[0]->resource_uri . '",
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

	function eventDeets($account_uri)
	{
		$uri_split = explode("/", $account_uri);
		
		$verb = 'GET';
		$path = '/event/';
		$params = array(
			'account' => $uri_split[3],
		);
		$resp = SnapApi::send($verb, $path, $params);
		                                                                                                    
		$response = $resp['response'];
		$httpcode = $resp['code'];
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response);
			
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
	
	
	function doReset($user_id)
	{	
		$verb = 'POST';
		$path = '/user/' . $user_id . '/passwordreset/';
		$params = array(
			"url" => "https://snapable.com/account/reset/",
		);
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		$httpcode = $resp['code'];
		
		if ( $httpcode == 201 )
		{
			return 1;
		} else {
			return 0;
		}
	}
	
	function completeReset($password, $password_nonce)
	{
		$verb = 'GET';
		$path = '/user/passwordreset/' . $password_nonce . '/';
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		$httpcode = $resp['code'];
		
		if ( $httpcode == 200 )
		{
			$result = json_decode($response);
			$email = $result->email;
			$resource_uri = explode("/", $result->resource_uri);

			$verb = 'PUT';
			$path = '/user/' . $resource_uri[3] . '/';
			$params = array(
				"password" => $password,
			);
			$headers = array(
				'X-SNAP-User' => $email . ":" . $password_nonce,
			);
			$resp = SnapApi::send($verb, $path, $params, $headers);
	 
	        $response = $resp['response'];
	        $httpcode = $resp['code'];
	               
	        if($httpcode == 202) {
	            return 1;
	        } else {
	        	return 0;
	        }
		} else {
			return 0;
		}
	}
	
}