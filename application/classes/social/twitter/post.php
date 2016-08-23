<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Twitter_Post extends Social_Twitter_API {
	
	const MAX_LENGTH = 140;
	const TCO_LENGTH = 25;
	
	protected $message;
	
	public function set_message($value)
	{
		$this->message = $value;
	}
	
	public function save()
	{
		$this->twitter->setAccessToken($this->access_token);
		$params = array('status' => $this->message);
		try { $status = $this->twitter->post('statuses/update', $params); }
		catch (Exception $e) { return null; }
		if (!isset($status->id_str)) return null;
		return $status->id_str;
	}
	
}

?>