<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Facebook_Profile {
	
	public static function parse_id($str)
	{		
		$pattern = '#^https?://([a-z\-\.]+\.)?facebook\.com/([a-z0-9\_\-]+)#is';
		if (preg_match($pattern, $str, $match)) 
			return $match[2];
		
		$pattern = '#^[a-z0-9\_\-]+$#is';
		if (preg_match($pattern, $str)) 
			return $str;
		
		return null;
	}
	
	public static function details($auth, $id = null)
	{
		if (!$id) $id = 'me';
		if (!$auth) return null;
		if (!$auth->is_valid()) return null;
		$facebook = Social_Facebook_API::instance();
		$facebook->setAccessToken($auth->access_token);
		try { return $facebook->api("/{$id}"); }
		catch (Exception $e) { return null; }
	}
	
	public static function name($auth)
	{
		$details = static::details($auth);
		if (isset($details['name']))
		    return $details['name'];
		return null;
	}
	
}

?>