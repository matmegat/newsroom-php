<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Date {
	
	const FORMAT_MYSQL = 'Y-m-d H:i:s';
	public static $now;
	public static $utc;
	
	protected static $__periods = array(
      // the values for year and month are for 365 and 30 days respectively
      array('name' => 'year', 'name_plural' => 'years', 'divisor' => 31536000),
      array('name' => 'month', 'name_plural' => 'months', 'divisor' => 2592000),
      array('name' => 'day', 'name_plural' => 'days', 'divisor' => 86400),
      array('name' => 'hour', 'name_plural' => 'hours', 'divisor' => 3600),
      array('name' => 'minute', 'name_plural' => 'minutes', 'divisor' => 60),
      array('name' => 'second', 'name_plural' => 'seconds', 'divisor' => 1),
   );
   
   public static function difference_in_words($to, $from = null)
   {
   	if ($from === null) $from = Date::$now;
      $difference = $from->getTimestamp() - $to->getTimestamp();
      if ($difference === 0) return 'now';
      $absolute = abs($difference);
      
      for ($i = 0, $c = count(static::$__periods); $i < $c; $i++)
      {
         $divisor = static::$__periods[$i]['divisor'];
         
         if ($absolute >= $divisor)
         {
            $rounded = (int) round($absolute / $divisor);
            $name = ($rounded === 1 ? static::$__periods[$i]['name'] :
               static::$__periods[$i]['name_plural']);
            
            return ($difference > 0 ?
               sprintf('%s %s ago', $rounded, $name) :
               sprintf('%s %s from now', $rounded, $name));
         }
      }
   }
	
	public static function in($str = 'now', $timezone = null)
	{
		if ($timezone === null)
			$timezone = static::local_tz();
		if (!($timezone instanceof DateTimeZone))
			$timezone = new DateTimeZone($timezone);
		$datetime = new DateTime($str, $timezone);
		$datetime->setTimezone(Date::$utc);
		return $datetime;
	}
	
	public static function out($str = 'now', $timezone = null)
	{
		if ($timezone === null)
			$timezone = static::local_tz();
		if (!($timezone instanceof DateTimeZone))
			$timezone = new DateTimeZone($timezone);
		$datetime = new DateTime($str, Date::$utc);
		$datetime->setTimezone($timezone);
		return $datetime;
	}
	
	public static function local_tz()
	{
		$ci =& get_instance();
		return $ci->local_tz();
	}
	
	public static function relative($amount, $unit, $from = null)
	{
		if ($from === null) $from = Date::$now;
		$from = clone $from;
		$from->modify(sprintf('%+d %s', $amount, $unit));
		return $from;
	}
	
	public static function days($amount, $from = null)
	{
		return static::relative($amount, 'days', $from);
	}
	
	public static function hours($amount, $from = null)
	{
		return static::relative($amount, 'hours', $from);
	}
	
	public static function minutes($amount, $from = null)
	{
		return static::relative($amount, 'minutes', $from);
	}
	
	public static function months($amount, $from = null)
	{
		return static::relative($amount, 'months', $from);
	}
	
	public static function seconds($amount, $from = null)
	{
		return static::relative($amount, 'seconds', $from);
	}
	
	public static function years($amount, $from = null)
	{
		return static::relative($amount, 'years', $from);
	}
	
}

Date::$utc = new DateTimeZone('UTC');
Date::$now = new DateTime();

?>