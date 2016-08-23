<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Facebook_Auth {
	
	public $company_id;
	public $access_token;
	public $page;
	public $date_renewed;
	
	public static function find($company)
	{
		$ci =& get_instance();
		$result = $ci->db->get_where('nr_social_auth_facebook', 
			array('company_id' => $company));
		
		$row = $result->row();
		$auth = new static();
		foreach ($row as $k => $v)
			$auth->$k = $v;
			
		return $auth;
	}
	
	public function is_valid()
	{
		$dt_expire = new DateTime($this->date_renewed);
		$dt_expire->modify('+60 days');
		
		return $this->access_token && $dt_expire > Date::$now;
	}
	
	public function test()
	{
		if (!$this->is_valid()) 
			return $this->delete();
		
		$res = false;
		$facebook = Social_Facebook_API::instance();
		$facebook->setAccessToken($this->access_token);
		try { $res = $facebook->api('/me'); }
		catch (Exception $e) {}
		
		if (!isset($res['id']))
			$this->delete();
	}
	
	public function page_list()
	{
		return Social_Facebook_Post::page_list($this->access_token);
	}
	
	public function renew()
	{
		$ci =& get_instance();
		$new_access_token = null;
		$facebook = Social_Facebook_API::instance();
		$facebook->setAccessToken($this->access_token);
		if ($facebook->setExtendedAccessToken() !== false)
			$new_access_token = $facebook->getAccessToken();
		
		if ($new_access_token)
		{
			$this->access_token = $new_access_token;
			$this->date_renewed = Date::$now
				->format(Date::FORMAT_MYSQL);	
						
			$ci->db->update('nr_social_auth_facebook', 
				array('access_token' => $this->access_token,
				      'date_renewed' => $this->date_renewed),
				array('company_id' => $this->company_id));
		}
		else
		{
			$ci->db->delete('nr_social_auth_facebook', 
				array('company_id' => $this->company_id));
		}
	}
	
	public function delete()
	{
		$ci =& get_instance();
		$facebook = Social_Facebook_API::instance();
		$facebook->setAccessToken($this->access_token);
		try { $facebook->api('/me/permissions', 'delete'); }
		catch (Exception $e) {}
		
		$ci->db->delete('nr_social_auth_facebook', 
			array('company_id' => $this->company_id));
		$this->access_token = null;
	}
	
	public function set_page($page)
	{
		$ci =& get_instance();
		$this->page = $page;
		$ci->db->update('nr_social_auth_facebook', 
			array('page' => $this->page),
			array('company_id' => $this->company_id));
	}
	
	public function renew_if_needed()
	{
		$dt_desired_renew = new DateTime($this->date_renewed);
		$dt_desired_renew->modify('+7 days');
			
		if (Date::$now > $dt_desired_renew)
			try { $this->renew(); } catch (Exception $e) {}
	}
	
}

?>