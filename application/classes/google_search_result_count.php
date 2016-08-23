<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google_Search_Result_Count {
	
	public static function count($title)
	{
		$class = get_class();
		$cache_name = "{$class}_{$title}";
		$cache_name = md5($cache_name);
		
		$value = Data_Cache::read($cache_name);
		if ($value !== false) 
			return $value;
		
		$url = static::url($title);		
		$request = new HTTP_Request($url);
		$request->set_header('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');
		$request->set_header('Accept-Language', 'en-us,en;q=0.5');
		$request->set_header('User-Agent', User_Agent::random());
		$response = @$request->get();
		
		$pattern = '#(about\s+)?([0-9,]+)\s+results#is';
		if (!preg_match($pattern, @$response->data, $match)) 
		{
			Data_Cache::write($cache_name, 0, 7200);
			return 0;
		}
		
		Data_Cache::write($cache_name, $match[2], 7200);
		return $match[2];
	}
	
	public static function url($title)
	{
		$params = array();
		$params['ie'] = 'utf-8';
		$params['oe'] = 'utf-8';
		$params['q'] = "\"{$title}\"";
		$params = http_build_query($params);
		$url = "http://www.google.com/search?{$params}";
		return $url;
	}
	
}

?>