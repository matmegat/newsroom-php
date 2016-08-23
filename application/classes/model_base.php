<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Base extends stdClass {

	protected $__original = null;
	
	public function __get($name)
	{
		return null;
	}
	
	public static function from_object($source)
	{
		$ob = new static();
		foreach ($source as $k => $v)
			$ob->$k = $v;
		return $ob;
	}
	
	public static function from_db_object($source)
	{
		$ob = static::from_object($source);
		$ob->__original = $source;
		return $ob;
	}
	
	public static function from_db($db_result)
	{
		$rows = $db_result->num_rows();
		if ($rows === 0) return false;
		
		$ob = new static();
		$ob->__original = $db_result->row();
		foreach ($ob->__original as $k => $v)
			$ob->$k = $v;
		
		$db_result->free_result();
		return $ob;
	}
	
	public static function from_db_all($db_result) 
	{
		$obs = array();
		
		foreach ($db_result->result() as $data)
		{
			$ob = new static();
			$ob->__original = $data;
			foreach ($ob->__original as $k => $v)
				$ob->$k = $v;
			$obs[] = $ob;
		}
		
		$db_result->free_result();		
		return $obs;
	}

}

?>