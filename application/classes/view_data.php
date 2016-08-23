<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class View_Data extends stdClass {
	
	public function esc($content)
	{
		$content = htmlspecialchars($content);
		return $content;
	}
	
	public function add_all($data)
	{
		foreach ($data as $k => $v)
			$this->$k = $v;
	}

	public function pure($content)
	{
		if ($content === null) return null;
		lib_autoload('html_purifier');		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$content = $purifier->purify($content);
		return $content;
	}	
	
	public function cut($content, $length)
	{
		if (mb_strlen($content) > $length)
		{
			$content = mb_substr($content, 0, $length - 4);
			$content = "{$content} ...";
		}
		
		return $content;
	}
	
}