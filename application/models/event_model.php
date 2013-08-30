<?php
Class Event_model extends CI_Model
{

	function getEventDetailsFromURL($url)
	{
		$verb = 'GET';
		$path = '/event/';
		$params = array(
			'url' => $url,
		);
		$resp = SnapApi::send($verb, $path, $params);
		                                                                                                    
		$response = $resp['response'];
		$response = str_replace("false", "\"0\"", $response);
		$response = str_replace("true", "\"1\"", $response);
		$result = json_decode($response);
		$httpcode = $resp['code'];
		
		if ( $httpcode == 200 && $result->meta->total_count > 0 )
		{	
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
				$human_start = date("D M j, g:i A", ($start_epoch + ($e->tz_offset * 60)));
				$human_end = date("D M j, g:i A", ($end_epoch + ($e->tz_offset * 60)));

				$privacyParts = explode('/', $e->type);
				$eventRes = array(
					'addresses' => $e->addresses,
					'enabled' => $e->enabled,
					'url' => $e->url,
					'title' => $e->title,
					'pin' => $e->pin,
					'start' => $e->start,
					'end' => $e->end,
					'human_start' => $human_start,
					'human_end' => $human_end,
					'start_epoch' => $start_epoch,
					'end_epoch' => $end_epoch,
					'display_timedate' => $display_timedate,
					'resource_uri' => $e->resource_uri,
					'user' => $e->user,
					'privacy' => $privacyParts[3],
					'public' => $e->public,
					'photos' => $e->photo_count,
					'tz_offset' => $e->tz_offset,
				);
			}
			$jsonRes = array(
				'status' => 200,
				'event' => $eventRes
			);
			return json_encode($jsonRes);
		} else {
			$jsonRes = array(
				'status' => 404,
				'event' => array(
					'enabled' => 0,
					'url' => $url,
				),
			);
			return json_encode($jsonRes);
		}
		//$details = $this->db->where('url', $url)->where('active', 1)->get('event', 1,0);
		// check if there's a positive result
	}
	
	function getEventsPhotos($id, $offset = 0)
	{
		$verb = 'GET';
		$path = '/photo/';
		$params = array(
			'event' => $id,
			'offset' => $offset,
			'limit' => 50,
		);
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		
		return '{
					"status": 200,
					"response": ' . $response . '
				}';
	}
	
	function guestCount($resource_uri)
	{
		$event = explode("/", $resource_uri);
		$eventID = $event[3];
		
		$verb = 'GET';
		$path = '/guest/';
		$params = array(
			'event' => $eventID,
		);
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		$result = json_decode($response);
		$httpcode = $resp['code'];

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
		
		// GET LIST OF CURRENT GUESTS
		$verb = 'GET';
		$path = '/guest/';
		$params = array(
			'event' => $event_id[3],
		);
		$resp = SnapApi::send($verb, $path, $params);

		$response = $resp['response'];
		$result = json_decode($response);
		$httpcode = $resp['code'];
		
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
				        	    	// isn't guest or email, must be a name
					        	    $name = $data[$c];
				        	    }
							}
						}
						
						if ( $email != "unknown" )
						{
							if ( !in_array($email, $emails))
							{
				        	    // if email address is not already a guest add
								$verb = 'POST';
								$path = '/guest/';
								$params = array(
									"email" => $email,
									"event" => $event_uri,
									"name" => $name,
								);
								$resp = SnapApi::send($verb, $path, $params);

								$response = $resp['response'];
								$httpcode = $resp['code'];

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
		if($this->session->userdata('logged_in'))
		{
			// get event details
			$session_data = $this->session->userdata('logged_in');
			//$event_data = $this->session->userdata('event_deets');
			
			try {
				$message = $post['message'];
				$event_uri = SnapApi::resource_uri('event', $post['event_id']);

				// get event
				$verb = 'GET';
				$path = '/event/'.$post['event_id'];
				$resp = SnapApi::send($verb, $path);
				$event = json_decode($resp['response']);

				// get guests
				$verb = 'GET';
				$path = '/guest/';
				$params = array(
					'event' => $event_uri,
					'invited' => 'false',
				);
				$resp = SnapApi::send($verb, $path, $params);

				$response = $resp['response'];
				$result = json_decode($response);

				if ($resp['code'] == 200) {
					// only send emails if there are some
					if ($result->meta->total_count > 0) {
						// common to all emails
						$subject = 'At ' . $event->title . ' use Snapable!';
						$fromname = $session_data['first_name'] . " " . $session_data['last_name'];

						// do the first batch of results
						foreach($result->objects as $o) {
							$this->email_guest($o->resource_uri, $subject, $o->email, $o->name, $fromname, $message);
						}

						// start looping through the pages of results
				        while (isset($response_loop->meta->next)) {
				            $resp_loop = SnapAPI::next($response_loop->meta->next);
				            $response_loop = json_decode($resp_loop['response']);

				            // the next non invited person
				            foreach ($response_loop->objects as $o) {
				                $this->email_guest($o->resource_uri, $subject, $o->email, $o->name, $fromname, $message);
				            }
				        }
			    	}
			        $this->output->set_status_header(200);
				} else {
					$this->output->set_status_header(500);
				}
			} catch (Exception $e) {
				$this->output->set_status_header(500);
			}
		}
	}
	
	
	function addManualGuests($list, $event_uri)
	{
		if($this->session->userdata('logged_in'))
		{
			// get event details
			$session_data = $this->session->userdata('logged_in');
			$event_data = $this->session->userdata('event_deets');

			$event_id = explode("/", $event_uri);
			$guest_count = 0;

			// GET LIST OF CURRENT GUESTS
			$verb = 'GET';
			$path = '/guest/';
			$params = array(
				'event' => $event_id[3],
			);
			$resp = SnapApi::send($verb, $path, $params);

			$response = $resp['response'];
			$result = json_decode($response);
			$httpcode = $resp['code'];
			
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
				}
				
				if ( stristr($list, ",") )
				{
					$listArr = explode(",", $list);
					$email = "unknown";
					$name = "unknown";

					foreach ( $listArr as $l )
					{
						$l = trim($l);
						if( preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $l) > 0)
		        	    {
		        	    	// is email
		        	    	$email = $l;
		        	    } else {
		        	    	// isn't guest or email, must be a name
			        	    $name = $l;
		        	    }
					}
					if ( $email != "unknown")
					{
		        	    // check if email is already registered as a guest
		        	    if ( !in_array($email, $emails) )
		        	    {
			        	    // add to database	
							$verb = 'POST';
							$path = '/guest/';
							$params = array(
								"email" => $email,
								"event" => $event_uri,
								"name" => $name,
							);
							$resp = SnapApi::send($verb, $path, $params);

							$response = $resp['response'];
							$httpcode = $resp['code'];
							
							if ( $httpcode == 201 )
							{
								$guest_count++;
							}
			        	}
		        	}
				}
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	
	function getGuests($eventID)
	{
		$verb = 'GET';
		$path = '/guest/';
		$params = array(
			'event' => $eventID,
		);
		$resp = SnapApi::send($verb, $path, $params);
		$result = json_decode($resp['response']);

		if ( $resp['code'] == 200 && $result->meta->total_count > 0 ) {
			$guests = array();
			foreach ( $result->objects as $r ) {	
				$gid = explode('/', $r->resource_uri);
				$guests[] = array(
					'id' => $gid[3],
					'name' => $r->name,
					'email' => $r->email,
					'invited' => $r->invited,
				);
			}

			// start looping through the pages of results
	        while (isset($result->meta->next)) {
	            $resp = SnapAPI::next($result->meta->next);
	            $result = json_decode($resp['response']);

	            // the next non invited person
	            foreach ($result->objects as $r) {
	                $gid = explode('/', $r->resource_uri);
					$guests[] = array(
						'id' => $gid[3],
						'name' => $r->name,
						'email' => $r->email,
						'invited' => $r->invited,
					);
	            }
	        }

			$json = array(
				"status" => 200,
				"guests" => $guests,
			);
		} else {
			$json = array("status" => 404);
		}
		return json_encode($json);
	}

	/**
	 * This function allows to query the API and returns the response
	 */
	function query($params=array())
	{
		// get type text
		$verb = 'GET';
		$path = '/event/';
		$resp = SnapApi::send($verb, $path, $params);

		return array(
			'response' => json_decode($resp['response'], true),
			'code' => $resp['code'],
		);
	}

	// private function
	private function email_guest($resource_uri, $subject, $email, $toname, $fromname, $message) {
		$gid = explode('/', $resource_uri);
		// common to all emails
		$this->email->initialize(array('mailtype'=>'html'));
		$this->email->from('robot@snapable.com', 'Snapable');
		$this->email->subject($subject);
		
		// data to pass to the views for the templates
		$data = array(
			'display' => "email",
			'message' => $message,
			'toname' => $toname,
			'fromname' => $fromname
		);

		$this->email->to($email);
		$this->email->message($this->load->view('email/guest_notification_html', $data, true));
		$this->email->set_alt_message($this->load->view('email/guest_notification_txt', $data, true));		      
		if ($this->email->send()) {
		    // mark the user as invited
		    // GET LIST OF CURRENT GUESTS
			$verb = 'PATCH';
			$path = '/guest/'.$gid[3];
			$params = array(
				'invited' => 'false',
			);
			$resp = SnapApi::send($verb, $path, $params);
		}
	}
	

}