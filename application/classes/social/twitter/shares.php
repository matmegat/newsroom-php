<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Twitter_Shares {

	public static function get($url)
	{
		$class = get_class();
		$cache_name = "{$class}_{$url}";
		$cache_name = md5($cache_name);
		$shares = Data_Cache::read($cache_name);
		if ($shares !== false)
			return (int) $shares;
		
		$data_url = urlencode($url);
		$data_url = "http://urls.api.twitter.com/1/urls/count.json?url={$data_url}";
		$source = @file_get_contents($data_url);
		$data = @json_decode($source);
		
		if (!empty($data->count))
			$shares = $data->count;
		$shares = (int) $shares;
		
		Data_Cache::write($cache_name, $shares, 300);
		return $shares;
	}
	
}

?>