<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class UUID {
	
	public static function hashed($source)
	{
		$hashed = md5((string) $source);
		$blocks = array();
		$blocks[] = substr($hashed,  0,  8);
		$blocks[] = substr($hashed,  8,  4);
		$blocks[] = substr($hashed, 12,  4);
		$blocks[] = substr($hashed, 16,  4);
		$blocks[] = substr($hashed, 20, 12);
		return implode(chr(45), $blocks);
	}
	
}

?>