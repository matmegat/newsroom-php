<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Twitter_API {
	
	protected $twitter;
	protected $access_token;
	
	public function __construct()
	{
		$this->twitter = static::instance();
	}

	public function set_auth($auth)
	{
		if (!($auth instanceof Social_Twitter_Auth))
			throw new Exception();
		$this->set_access_token((array) $auth);
	}

	public function set_access_token($token)
	{
		// expects as an array with keys:
		// oauth_token, oauth_token_secret
		$this->access_token = $token;
	}
	
	public static function instance()
	{
		$ci =& get_instance();
		lib_autoload('twitter_oauth');
		$config = $ci->conf('twitter_app');
		$api_key = $config['api']['key'];
		$api_secret = $config['api']['secret'];
		$twitter = new Twitter($api_key, $api_secret);
		return $twitter;
	}
	
}

?>