<?php

	/*
	*	A simple class for making get REST requests
	*	Authored by Rory Cartwright
	*	GPL2: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
	*/

class SimpleRESTResource
{
	//Properties
	protected $api_url;
	protected $api_key;
	protected $req_string;
	protected $result;

	//Constructor
	public function __construct($api_url, $api_key)
	{
		
		$this->api_url = (string)$api_url;
		$this->api_key = (string)$api_key;
		$this->req_string = '';
		$this->result = false;
	}
	
	//Construct requests
	//args: Formatted query url string/Array of key => value pairs
	public function request($args)
	{
		//Empty request string
		if(empty($args))
			$this->result = $this->getRequestAsArray();
			
		//if args are in string form
		if(is_string($args) && $this->req_string = $this->validateURL($this->getAPIURL() . '&' . $args))
		{
			$this->result = $this->getRequestAsArray();
		}
		
		//if args are array of key-value pairs
		if(is_array($args))
		{
			$request_parameters = '';
			foreach($args as $arg => $val)
			{
				$request_parameters .= '&' . urlencode($arg) . '=' . urlencode($val);
			}		
			if($this->req_string = $this->validateURL($this->getAPIURL() . $request_parameters))
				$this->result = $this->getRequestAsArray();
		}
		return $this->result;
	}
	
	//Return the result
	public function getResult()
	{
		return $this->result;
	}
	
	protected function performGetRequest()
	{
		return file_get_contents($this->req_string);
	}
	
	protected function getRequestAsArray()
	{
		return json_decode($this->performGetRequest($this->req_string), true);
	}
	
	//Return the requested url with the key appended as a parameter
	protected function getAPIURL()
	{
		return $this->api_url . '?apikey=' . $this->api_key;
	}
	
	//A really simple function to test that we have a reasonably sensible URL
	protected function validateURL($string)
	{
		if(parse_url($string))
			return $string;
			
		return false;	
	}
}

?>