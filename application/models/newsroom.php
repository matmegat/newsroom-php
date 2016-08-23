<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Newsroom extends Model {
	
	public $name;
	public $company_id;
	public $company_name;
	public $domain;
	public $timezone;
	public $user_id;
	public $is_common;
	
	protected $_custom = null;
	protected $_profile = null;
	protected $_contact = null;
	
	protected static $__table = 'nr_newsroom';
	protected static $__primary = 'company_id';
	
	public static function find_company_id($company)
	{		
		$ci =& get_instance();
		if (isset($ci->newsroom))
			if ($ci->newsroom->company_id == $company)
				return $ci->newsroom;
		
		return static::find($company);
	}
	
	public static function find_name($name)
	{
		return static::find('name', $name);
	}
	
	public static function find_domain($domain)
	{
		return static::find('domain', $domain);
	}
	
	public static function find_user_id($user_id)
	{
		$criteria = array();
		$criteria[] = array('user_id', $user_id);
		$criteria[] = array('is_archived', 0);
		$order = array('order_default', 'desc');
		$newsrooms = static::find_all($criteria, $order);
		return $newsrooms;
	}
	
	public static function find_user_default($user)
	{
		if ($user instanceof Model_User)
			$user = $user->id;
		
		$criteria = array();
		$criteria[] = array('user_id', $user);
		$criteria[] = array('is_archived', 0);
		$order = array('order_default', 'desc');
		$newsrooms = static::find_all($criteria, $order, 1);
		if (!count($newsrooms)) return null;
		return $newsrooms[0];
	}
	
	public static function current()
	{
		$ci =& get_instance();
		return $ci->newsroom;
	}
	
	public static function common()
	{
		$ci =& get_instance();
		$common = new static();
		$common->name = $ci->conf('common_host_name');
		$common->company_name = 'iNewswire';
		$common->company_id = 0;
		$common->is_common = true;
		$common->user_id = 0;
		return $common;
	}
	
	public function set_domain($domain)
	{
		$ci =& get_instance();
		$admo_suffix = $ci->conf('admo_suffix');
		$detached_suffix = $ci->conf('detached_suffix');
		$host_suffix = $ci->conf('host_suffix');
		if (str_ends_with($domain, $admo_suffix)) return false;
		if (str_ends_with($domain, $detached_suffix)) return false;
		if (str_ends_with($domain, $host_suffix)) return false;
		if (Model_Newsroom::find_domain($domain)) return false;
		if (!Newsroom_Assist::reaches_newsroom($domain)) return false;
		$this->domain = $domain;
		return true;
	}
	
	public static function create($user, $company_name = null)
	{
		return Newsroom_Assist::create($user, $company_name);		
	}
	
	public function requires_own_domain()
	{
		if (Auth::requires_user())
			return false;
		
		$ci =& get_instance();
		$uri = $ci->uri->uri_string();			
		return (!preg_match('#^shared/log(in|out)#i', $uri));
	}
	
	public function url($relative_url = null, $use_domain = false)
	{
		$ci =& get_instance();
		$suffix = $ci->conf('host_suffix');
		$host = "{$this->name}{$suffix}";
		if ($use_domain && $this->domain)
			$host = $this->domain;
		
		$url = "http://{$host}/{$relative_url}";
		return $url;
	}
		
	public static function name_available($name)
	{
		return Newsroom_Assist::name_available($name);
	}
	
	public static function normalize_name($name)
	{
		return Newsroom_Assist::normalize_name($name);
	}
	
	public function custom()
	{
		if ($this->_custom === null)
		{
			$company_id = $this->company_id;
			$this->_custom = Model_Newsroom_Custom::find($company_id);
		}
		
		return $this->_custom;
	}
	
	public function contact()
	{		
		if ($this->_contact === null)
		{
			$cc_id = $this->company_contact_id;
			$this->_contact = Model_Company_Contact::find($cc_id);
		}
		
		return $this->_contact;
	}
	
	public function profile()
	{
		if ($this->_profile === null)
		{
			$company_id = $this->company_id;
			$this->_profile = Model_Company_Profile::find($company_id);
		}
		
		return $this->_profile;
	}

}

?>