<?php
Class Event_model extends CI_Model
{

	function getEventDetailsFromURL($url)
	{
	
		//$details = $this->db->where('url', $url)->where('active', 1)->get('event', 1,0);
		// check if there's a positive result
		$json = '{
			"status": 1,
			"details": "results_array"
		}';
		return $json;
	}

}