<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Iella_Request {
	
	protected static $secret;
	
	public $base;
	public $data;
	public $response;
	public $raw_response;
	
	public function __construct()
	{
		$ci =& get_instance();
		$this->data = new stdClass();
		$this->base = $ci->conf('iella_base_url');
		
		if (!static::$secret)
		{
			$secret_file = $ci->conf('iella_secret_file');
			static::$secret = file_get_contents($secret_file);
		}
	}
	
	public function send($method, $data = null)
	{
		if ($data === null) 
			$data = $this->data;			
		$http_request = new HTTP_Request();
		$http_request->url = "{$this->base}{$method}";
		$http_request->data = array();
		$http_request->data['iella-secret'] = static::$secret;
		$http_request->data['iella-in'] = json_encode($data);
		$this->raw_response = $http_request->post();
		$this->response = json_decode($this->raw_response->data);
		return $this->response;
	}
	
}

?>