<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class URL_Shortener {
	
	protected $url;
	
	public function set_url($url)
	{
		$this->url = $url;
	}
	
	public function shorten($url = null)
	{
		if ($url !== null)
			$this->url = $url;
			
		$enc_url = urlencode($this->url);
		$api_call_url = "http://is.gd/create.php?format=simple&url={$enc_url}";
		if (!($result = @file_get_contents($api_call_url)))
		    return false;
		    
		return trim($result);
	}
	
}

?>