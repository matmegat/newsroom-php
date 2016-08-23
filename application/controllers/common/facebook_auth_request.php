<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('common/auth_request');

class Facebook_Auth_Request_Controller extends Auth_Request_Base {
	
	protected $facebook_config;
	
	protected static $required_perms = array(
		'publish_actions',
		'publish_stream',
		'manage_pages',		
	);
	
	public function __construct()
	{
		parent::__construct();
		
		lib_autoload('facebook_sdk');
		$this->facebook_config = $this->conf('facebook_app');
		$this->facebook = new Facebook($this->facebook_config['api']);
	}
	
	protected function login_url($params = array())
	{
		// remove any existing session
		$this->facebook->destroySession();
		
		$newsroom_name = $this->newsroom->name;
		$base_url = $this->facebook_config['base_url'];		
		$redirect_uri = "{$base_url}/callback?newsroom={$newsroom_name}";
		
		$params['scope'] = static::$required_perms;
		$params['redirect_uri'] = $redirect_uri;
		
		return $this->facebook->getLoginUrl($params);
	}
	
	public function callback()
	{
		if (!$this->facebook->getUser())
		{
			$view_data = array('url' => $this->login_url());
			$this->load->view('common/facebook_denied', $view_data);
			return;
		}
		
		$access_token = $this->facebook->getAccessToken();
		$this->facebook->destroySession();
		
		$this->db->query(
			"INSERT INTO nr_social_auth_facebook (company_id, 
			 access_token, page, date_renewed) VALUES (?, ?, NULL, UTC_TIMESTAMP())
			 ON DUPLICATE KEY UPDATE access_token = ?, 
			 page = NULL, date_renewed = UTC_TIMESTAMP()",
			array($this->newsroom->company_id, 
			      $access_token,
			      $access_token));
		
		$relative_url = 'manage/newsroom/social';
		$url = $this->newsroom->url($relative_url);
		$this->redirect($url, false);
	}
	
	public function index()
	{
		$url = $this->login_url();
		$this->redirect($url, false);
	}
	
}

?>