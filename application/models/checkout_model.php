<?php
Class Checkout_model extends CI_Model
{

	function addAddress($post)
	{
		/*
		Array
		(
		    [type] => shipping
		    [name] => Andrew Draper
		    [address] => 1110 Halton Terrace
		    [city] => Ottawa
		    [state] => ON
		    [country] => CA
		    [zip] => K2W 1G8
		    [email] => andrew@hashbrown.ca
		)
		*/
		return '{
			"status": 200
		}';
	}

}