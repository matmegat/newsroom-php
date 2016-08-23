<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_GPlus_Profile {
	
	public static function parse_id($str)
	{		
		$pattern = '#([0-9]{15,30})#is';
		if (preg_match($pattern, $str, $match)) 
			return $match[1];
		
		return null;
	}
	
}

?>