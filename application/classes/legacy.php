<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class LEGACY {

	protected static $__db = null;
	
	public static function database()
	{
		if (static::$__db === null)
		{
			$ci =& get_instance();
			$db = $ci->load->database('legacy', TRUE);
			static::$__db = $ci->ldb = $db;
		}
		
		return static::$__db;
	}
	
}