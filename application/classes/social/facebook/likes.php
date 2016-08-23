<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Facebook_Likes extends Social_Facebook_API {
	
	public function get($id)
	{
		if ($id === null) return 0;
		$this->facebook->setAccessToken($this->access_token);
		try {	$data = $this->facebook->api("/{$id}/likes?summary=true"); }
		catch (Exception $e) { return 0; }
		if (!isset($data['summary']['total_count'])) return 0;
		return (int) $data['summary']['total_count'];
	}
	
}

?>