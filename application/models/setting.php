<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Setting extends Model {
	
	const TYPE_INTEGER  = 'INTEGER';
	const TYPE_STRING   = 'STRING';
	const TYPE_BOOLEAN  = 'BOOLEAN';
	const TYPE_TEXT     = 'TEXT';
	const TYPE_VIDEO    = 'VIDEO';
	const TYPE_EXTERNAL = 'EXTERNAL';
	
	protected static $__table = 'nr_setting';
	protected static $__primary = 'name';
	protected static $__setting_cache = null;
	
	public static function find($name)
	{
		if (static::$__setting_cache === null)
			static::populate_cache();		
		if (isset(static::$__setting_cache[$name]))
			return static::$__setting_cache[$name];
		return null;
	}
	
	protected static function populate_cache()
	{
		static::$__setting_cache = array();
		$all = Model_Setting::find_all();
		foreach ($all as $setting)
			static::$__setting_cache[$setting->name] = $setting;
	}
	
	public function set($value)
	{
		if ($this->type === Model_Setting::TYPE_INTEGER)
			$value = (int) $value;
		if ($this->type === Model_Setting::TYPE_BOOLEAN)
			$value = (bool) $value;
		if ($this->type === Model_Setting::TYPE_VIDEO)
			$value = Video_Youtube::parse_video_id($value);
		$this->value = $value;
	}
	
	public static function value($name)
	{
		$setting = static::find($name);
		if (!$setting) return null;
		if ($setting->type === static::TYPE_INTEGER)
			return (int) $setting->value;
		if ($setting->type === static::TYPE_BOOLEAN)
			return (bool) $setting->value;
		return $setting->value;
	}
	
}

?>