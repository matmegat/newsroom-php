<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class URL {
   
   // safe urls are email and http/https links
   private static $__safe_pattern = '#^(mailto:|https?://)#is';
   
   public static function safe($url)
   {
   	if ($url === null) return null;
   	if (preg_match(static::$__safe_pattern, $url))
   		return $url;
   	return null;
   }
   
}

?>