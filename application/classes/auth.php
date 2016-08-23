<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth {
	
	const ERROR_NONE				= 0;
	const ERROR_CREDENTIALS 	= 1;
	const ERROR_DISABLED 		= 2;
	const ERROR_NOT_VERIFIED 	= 3;
	
	// stores the current active user
	protected static $user = false;
	
	// stores the current active admin user
	protected static $admin_user = false;
	
	// boolean to indicate if loaded
	protected static $loaded = false;
	
	// last error for authentication
	protected static $error_code = null;
	
	// is the auth from secret
	public static $is_from_secret = false;
	
	// is the address blocked (no users allowed)
	public static $is_blocked = false;

	protected static function load()
	{
		// address blocked => no auth
		if (static::$is_blocked) return;
		
		$ci =& get_instance();
		$id = (int) $ci->session->get('auth_user');
		if (!$id) return;
		
		$remote_addr = $ci->env['remote_addr'];
		static::$user = $user = Model_User::find($id);
		static::$loaded = true;
		
		// update the last seen remote address for this user
		if ($user->remote_addr != $remote_addr)
		{
			$user->remote_addr = $remote_addr;
			$user->save();
		}
		
		// update the last seen remote address for this user
		$date_active = new DateTime($user->date_active);
		if ($date_active < Date::days(-1))
		{
			$user->date_active = Date::$now->format(Date::FORMAT_MYSQL);
			$user->save();
		}
		
		// account disabled => logout
		if (!$user->is_active)
			static::logout();
	}
	
	protected static function save()
	{
		$ci =& get_instance();
		$id = static::$user ? static::$user->id : 0;
		$ci->session->set('auth_user', $id);
	}
	
	public static function from_secret()
	{
		$as = Stored_File::from_uploaded_file('auth-secret');
		if (!$as->exists()) return;
		$ci =& get_instance();
		
		$secret_file = $ci->conf('auth_secret_file');
		$actual_secret = file_get_contents($secret_file);
		$test_secret = $as->read();
		if ($actual_secret !== $test_secret)
			return;
		
		$user_id = $ci->newsroom->user_id;
		$ci->session->set('auth_user', $user_id);
		static::$user = Model_User::find($user_id);
		static::$is_from_secret = true;
		static::$loaded = true;
	}
	
	public static function __error_code()
	{
		return static::$error_code;
	}
	
	public static function is_user_online()
	{
		return (bool) static::user();
	}
	
	public static function is_admin_online()
	{
		return (bool) static::admin_user();
	}
	
	public static function user()
	{
		if (!static::$loaded) static::load();
		return static::$user;
	}
	
	public static function authenticate($email, $password)
	{
		static::$user = Model_User::authenticate($email, $password);
		static::save();
		
		if (!static::$user)
		{
			static::$error_code = self::ERROR_CREDENTIALS;
			return;
		}
		
		if (!static::$user->is_active)
		{
			static::$error_code = self::ERROR_DISABLED;
			return;
		}
		
		if (!static::$user->is_verified)
		{
			static::$error_code = self::ERROR_NOT_VERIFIED;
			return;
		}
		
		static::$error_code = self::ERROR_NONE;
		return static::$user;
	}
	
	public static function login($user)
	{
		if (!($user instanceof Model_User))
			throw new Exception();
		static::$user = $user;
		static::save();
	}
	
	public static function logout()
	{
		static::$user = false;
		static::save();
	}
	
	// this is now the same as is_admin_online()
	public static function is_admin_controlled()
	{
		return (bool) static::admin_user();
	}
	
	public static function admin_user()
	{
		if (!static::is_user_online()) return null;
		if (!static::$admin_user && static::$user->is_admin)
			return static::$user;
		return static::$admin_user;
	}
	
	public static function requires_user()
	{
		$ci =& get_instance();
		$uri = $ci->uri->uri_string();
		
		return ( preg_match('#^manage#i', $uri) 
			 ||	preg_match('#^reseller#i', $uri) 
			 ||	preg_match('#^admin#i', $uri) 
		  ) && ! preg_match('#^shared/log(in|out)#i', $uri);
	}
	
	public static function admo($user_id)
	{
		static::$admin_user = static::user();
		static::$user = Model_User::find($user_id);
	}
	
	public static function check()
	{
		$ci =& get_instance();
		$user = static::user();
		
		if (static::is_user_online())
		{
			$user_id = (int) $user->id;
			$nr_user_id = (int) $ci->newsroom->user_id;			
			// if the user is not on their own newsroom
			if ($nr_user_id && $user_id !== $nr_user_id && $user->is_admin)
				static::admo($nr_user_id);
			$user = static::user();
			$user_id = (int) $user->id;
		}
			
		if (static::requires_user())
		{
			// assume access is allowed
			if (static::$is_from_secret) return;
			
			// no user online
			if (!static::is_user_online())
				$ci->denied();
			
			// on common host then exit
			if ($ci->is_common_host) return;
			
			// if the user is not on their own newsroom
			if ($user_id !== $nr_user_id)
			{
				// redirect the user to their own newsroom
				$url = $user->default_newsroom()->url('default');
				$ci->redirect($url, false);
			}
		}
	}
	
	public function is_admin_mode()
	{
		if (!Auth::is_admin_online()) return false;
		return Auth::admin_user()->id != Auth::user()->id;
	}
	
}

?>