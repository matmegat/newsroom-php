<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Youtube_Profile {
	
	public static function parse_id($str)
	{		
		$pattern = '#^https?://([a-z\-\.]+\.)?youtube\.com/(user/)?([a-z0-9\_\-]+)#is';
		if (preg_match($pattern, $str, $match)) 
			return $match[3];
		
		$pattern = '#^[a-z0-9\_\-]+$#is';
		if (preg_match($pattern, $str)) 
			return $str;
		
		return null;
	}
	
}

?>