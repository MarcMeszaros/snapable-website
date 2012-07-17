<?php
Class Buy_model extends CI_Model
{

	function getPackageDetails($url)
	{
		$details = $this->db->where('url', $url)->where('active', 1)->get('package', 1,0);
		return $details->result();
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
	     $data = array(
   			'fname' => $user['first_name'],
   			'lname' => $user['last_name'],
   			'email' => $user['email'],
   			'password' => md5($user['password']),
   			'billing_zip' => $address['zip'],
   			'type' => 1,
   			'creation_date' => time(),
   			'last_accessed' => time()
		);
		$this->db->insert('user', $data);
		$user_id = $this->db->insert_id();
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
	     $start = strtotime($event['start_date'] . " " . $event['start_time']);
	     $end = strtotime($event['end_date'] . " " . $event['end_time']);
	     
	     $data = array(
   			'user_id' => $user_id,
   			'package_id' => $event['package'],
   			'start_timestamp' => $start,
   			'end_timestamp' => $end,
   			'title' => $event['title'],
   			'url' => $event['url'],
   			'creation_date' => time(),
   			'last_accessed' => time(),
   			'times_accessed' => 0,
   			'active' => 1
		);
		$this->db->insert('event', $data);
		$event_id = $this->db->insert_id();
		
		$data = array(
   			'event_id' => $event_id,
   			'address' => $event['location'],
   			'lat' => $event['lat'],
   			'lng' => $event['lng'],
   			'creation_date' => time()
		);
		$this->db->insert('addresses', $data);
		$address_id = $this->db->insert_id();
		
		return 1;
	}

}