<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Twitter_Auth {
	
	public $company_id;
	public $oauth_token;
	public $oauth_token_secret;
	public $username;
	public $date_renewed;
	
	public static function find($company)
	{
		$ci =& get_instance();
		$result = $ci->db->get_where('nr_social_auth_twitter', 
			array('company_id' => $company));
		
		if (!$result->num_rows())
			return false;
			
		$row = $result->row();		
		$auth = new static();
		foreach ($row as $k => $v)
			$auth->$k = $v;
		
		return $auth;
	}
	
	public function test()
	{
		if (!$this->is_valid()) 
			return $this->delete();
		
		$res = 
		$twitter = Social_Twitter_API::instance();
		$twitter->setAccessToken((array) $this);
		try { $res = $twitter->get('account/verify_credentials'); }
		catch (Exception $e ) {}
		
		if (!isset($res->id))
			$this->delete();
	}
	
	public function delete()
	{
		$ci =& get_instance();
		$ci->db->delete('nr_social_auth_twitter', 
			array('company_id' => $this->company_id));
		$this->oauth_token_secret = null;
		$this->oauth_token = null;
	}
	
	public function is_valid()
	{
		return $this->oauth_token && $this->oauth_token_secret;
	}
	
}

?>