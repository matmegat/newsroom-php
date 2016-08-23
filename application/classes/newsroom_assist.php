<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsroom_Assist {

	public static function create($user, $company_name = null)
	{
		if ($user instanceof Model_User)
			$user = $user->id;
		
		$company = new Model_Company();
		$company->date_created = Date::$now->format(Date::FORMAT_MYSQL);
		$company->user_id = $user;
		$company->newsroom = substr(md5(microtime(true)), 0, 16);
		$company->name = $company->newsroom;
		$company->save();
		
		$limit = 0;
		set_time_limit(5);
		
		if ($company_name)
		{
			$co_name = $company_name;
			$nr_name = static::normalize_name($co_name);
			$nr_name_base = $nr_name;
						
			// attempt to generate a newsroom name at most 
			// 16 times and then give up and fallback to hash
			while ($limit++ < 16 && !static::name_available($nr_name))
			{
				$code = rand(1000000, 9999999);
				$nr_bits = array($nr_name_base, $code);
				$nr_name = implode($nr_bits);
			}
		}
		else
		{	
			// a nicer looking first attempt
			$nr_name = "company-{$company->id}";
			$co_name = "Company {$company->id}";
			
			// generate a new newsroom name
			// that looks better than the hash		
			// with limit 16 attempts
			while ($limit++ < 16 && !static::name_available($nr_name))
			{
				$code = rand(1000000, 9999999);
				$nr_name = "company-{$code}-{$company->id}";
				$co_name = "Company {$code}-{$company->id}";
			}
		}
		
		set_time_limit(60);
		if (static::name_available($nr_name))
		{
			$company->name = $co_name;
			$company->newsroom = $nr_name;
			$company->save();
		}
		
		return Model_Newsroom::find_company_id($company->id);
	}
	
	public static function name_available($name)
	{
		if (strlen($name) > 64)
			return false;
		
		$ci =& get_instance();		
		$host = implode(array($name, $ci->conf('host_suffix')));
		if ($ci->conf('website_tunnel_host') == $host) return false;
		if ($ci->conf('website_host') == $host) return false;
		if ($ci->conf('common_host') == $host) return false;
		
		$regex_outer = '#%s#is';
		$reserved = $ci->conf('reserved_names');
		foreach ($reserved as $r_name)
		{
			$regex = sprintf($regex_outer, $r_name);
			if (preg_match($regex, $name)) return false;
		}
		
		$regex_outer = '#%s#is';
		$reserved = Model_Reserved_Name::find_all();
		foreach ($reserved as $r_name)
		{
			$regex = sprintf($regex_outer, $r_name->regex);
			if (@preg_match($regex, $name)) return false;
		}
		
		if (Model_Newsroom::find_name($name)) return false;
		return static::reaches_newsroom($host);
	}
	
	public static function normalize_name($name)
	{
		// replace non-allowed characters
		$name = preg_replace('#[^a-z0-9\-]#is', '-', $name);
		$name = preg_replace('#--*#is', '-', $name);
		$name = preg_replace('#(^-|-$)#is', '', $name);
		$name = strtolower($name);
		
		return $name;
	}
	
	public static function reaches_newsroom($host)
	{
		$url = "http://{$host}/is-newsroom";
		$res = @file_get_contents($url);
		return $res === 'is-newsroom';
	}
	
}

?>