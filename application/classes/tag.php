<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tag {
	
	public static function uniform($value)
	{
		$value = strtolower($value);
		$value = preg_replace('#[^a-z0-9]#is', '-', $value);
		$value = preg_replace('#--*#is', '-', $value);
		$value = preg_replace('#(^-|-$)#is', '', $value);
		return $value;
	}

	public static function url($tag) 
	{
		$uniform = static::uniform($tag);
		return "browse/tag/{$uniform}";
	}
	
}

?>