<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Linkedin_Profile {
	
	public static function parse_id($str)
	{		
		$pattern = '#^https?://([a-z\-\.]+\.)?linkedin.com/profile/view\?id=([0-9]+)([^0-9]|$)#is';
		if (preg_match($pattern, $str, $match)) 
			return $match[2];
		
		$pattern = '#^[0-9]+$#is';
		if (preg_match($pattern, $str)) 
			return $str;
		
		return null;
	}
	
}

?>