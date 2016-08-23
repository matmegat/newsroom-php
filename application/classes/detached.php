<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Detached {
	
	public static function __cache_name($name)
	{
		$ci =& get_instance();
		$session_id = $ci->session->id();
		$name = "{$session_id}_detached_{$name}";
		return $name;
	}
	
	public static function __cache_used($used = null)
	{
		$ci =& get_instance();
		$session_id = $ci->session->id();
		$cache_name = "{$session_id}_names_detached";
		
		if ($used !== null)
		{
			$used = serialize($used);
			Data_Cache::write($cache_name, $used);
		}
		else
		{
			$cached = Data_Cache::read($cache_name);
			if (!$cached) return array();
			return unserialize($cached);
		}
	}
	
	public static function __cache_use($name)
	{
		$cached = static::__cache_used();
		$cached[] = $name;
		static::__cache_used($cached);
	}
	
	public static function __cache_reset()
	{
		$cached = static::__cache_used();
		static::__cache_used(array());
		foreach ($cached as $name)
			Data_Cache::delete(static::__cache_name($name));
	}
	
	public static function is_used()
	{
		return (bool) count(static::__cache_used());
	}
	
	public static function reset()
	{
		static::__cache_reset();
	}
	
	public static function write($name, $model)
	{
		$cache_name = static::__cache_name($name);
		$class_name = get_class($model);
		$class_vars = (object) get_object_vars($model);
		$serialize = new stdClass();
		$serialize->name = $class_name;
		$serialize->vars = $class_vars;
		$model_str = serialize($serialize);
		Data_Cache::write($cache_name, $model_str);
		static::__cache_use($name);
	}
	
	public static function read($name)
	{
		$cache_name = static::__cache_name($name);
		$cache_data = Data_Cache::read($cache_name);
		if (!$cache_data) return;
		
		$unserialized = unserialize($cache_data);
		$class_name = $unserialized->name;
		$class_vars = $unserialized->vars;
		$class_name = preg_replace(
			'#^Model_(?!Detached_)([a-z_]+)$#i',
			'Model_Detached_${1}', 
			$class_name);
		
		$model = $class_name::from_object($class_vars);
		return $model;
	}
	
	public static function delete($name)
	{
		$cache_name = static::__cache_name($name);
		Data_Cache::delete($cache_name);
	}
	
	public static function url($relative_url = null, $use_common_host = false)
	{
		$ci =& get_instance();
		$prefix = $ci->conf('detached_prefix');
		$suffix = $ci->conf('detached_suffix');
		
		if ($use_common_host) 
			  $newsroom = $ci->conf('common_host_name');
		else $newsroom = $ci->newsroom->name;
		
		$host = "{$prefix}{$newsroom}{$suffix}";
		$url = "http://{$host}/{$relative_url}";
		return $url;
	}
	
}

?>