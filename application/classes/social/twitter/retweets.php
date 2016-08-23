<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Twitter_Retweets extends Social_Twitter_API {

	public $screen_name = null;
	
	public function get($id)
	{
		if ($id === null) return 0;
		$params = array("id" => $id);
		$this->twitter->setAccessToken($this->access_token);
		try {	$tweet = $this->twitter->get("statuses/show", $params); }
		catch (Exception $e) { return 0; }
		$this->screen_name = $tweet->user->screen_name;
		if (!isset($tweet->retweet_count)) return 0;
		return (int) $tweet->retweet_count;
	}
	
}

?>