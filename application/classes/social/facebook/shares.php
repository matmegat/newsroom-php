<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Facebook_Shares {

	public static function get($url)
	{
		$class = get_class();
		$cache_name = "{$class}_{$url}";
		$cache_name = md5($cache_name);
		$shares = Data_Cache::read($cache_name);
		if ($shares !== false)
			return (int) $shares;
		
		$data_url = urlencode($url);
		$data_url = "https://graph.facebook.com/?ids={$data_url}";
		$source = @file_get_contents($data_url);
		$data = @json_decode($source);
		
		if (!empty($data->{$url}->shares))
			$shares = $data->{$url}->shares;
		$shares = (int) $shares;
		
		Data_Cache::write($cache_name, $shares, 300);
		return $shares;
	}
	
}

?>