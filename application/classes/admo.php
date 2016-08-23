<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admo {
	
	public static function url($relative_url = null, $admo_user_id = false)
	{
		$ci =& get_instance();
		$prefix = $ci->conf('admo_prefix');
		$suffix = $ci->conf('admo_suffix');
		
		if (!$admo_user_id) 
			  $admo_user_id = Auth::user()->id;
		else $admo_user_id = (int) $admo_user_id;
		
		$host = "{$prefix}{$admo_user_id}{$suffix}";
		$url = "http://{$host}/{$relative_url}";
		return $url;
	}
	
}

?>