<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('common/auth_request');

class Twitter_Auth_Request_Controller extends Auth_Request_Base {
	
	protected $twitter_config;
	
	public function __construct()
	{
		parent::__construct();
		
		lib_autoload('twitter_oauth');
		$this->twitter_config = $this->conf('twitter_app');
		$api_key = $this->twitter_config['api']['key'];
		$api_secret = $this->twitter_config['api']['secret'];
		$this->twitter = new Twitter($api_key, $api_secret);
	}
	
	protected function failed()
	{
		$view_data = array('url' => $this->login_url());
		$this->load->view('common/twitter_denied', $view_data);
		return;
	}
	
	protected function login_url()
	{
		$newsroom_name = $this->newsroom->name;
		$base_url = $this->twitter_config['base_url'];
		$redirect_uri = "{$base_url}/callback?newsroom={$newsroom_name}";
		
		$this->twitter->setAccessToken(null);
		$t_token = $this->twitter->getRequestToken($redirect_uri);
		$this->session->set('twitter_app_t_token', $t_token);
		return $this->twitter->getAuthorizeURL($t_token, false);
	}
	
	public function callback()
	{
		$oauth_verifier = $this->input->get('oauth_verifier');
		$t_token = $this->session->get('twitter_app_t_token');
		
		if (empty($oauth_verifier))
			return $this->failed();
		
		if (empty($t_token['oauth_token_secret']))
			return $this->failed();
		
		$this->session->delete('twitter_app_t_token');
		$this->twitter->setAccessToken($t_token);
		
		if (!($cred_token = $this->twitter->getAccessToken($oauth_verifier)))
			return $this->failed();
				
		$this->db->query(
			"INSERT INTO nr_social_auth_twitter (company_id, 
			 oauth_token, oauth_token_secret, username, date_renewed) 
			 VALUES (?, ?, ?, ?, UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE 
			 oauth_token = ?, oauth_token_secret = ?, 
			 username = ?, date_renewed = UTC_TIMESTAMP()",
			array($this->newsroom->company_id, 
			      $cred_token['oauth_token'],
			      $cred_token['oauth_token_secret'],
			      $cred_token['screen_name'],
			      $cred_token['oauth_token'],
			      $cred_token['oauth_token_secret'],
			      $cred_token['screen_name']));
		
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