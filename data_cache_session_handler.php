<?php

class Data_Cache_Session_Handler
{
	public static $session_duration = 86400;
	
	protected static function cache_name($id)
	{
		return sprintf('dc_session_%s', $id);
	}
	
	public static function open()
	{
		return true;
	}

	public static function close()
	{
		return true;
	}
	
	public static function commit()
	{
		$id = session_id();
		$data = session_encode();
		static::write($id, $data);
	}
	
	public static function reload()
	{
		$id = session_id();
		$data = static::read($id);
		session_decode($data);
	}

	public static function read($id)
	{
		$name = static::cache_name($id);
		return (string) Data_Cache::read($name);
	}

	public static function write($id, $data)
	{
		$name = static::cache_name($id);
		Data_Cache::write($name, $data, 86400);
	}

	public static function destroy($id)
	{
		$name = static::cache_name($id);
		Data_Cache::delete($name);
		return true;
	}

	public static function gc()
	{
		return true;
	}
}

session_set_save_handler(
	array('Data_Cache_Session_Handler', 'open'),
	array('Data_Cache_Session_Handler', 'close'),
	array('Data_Cache_Session_Handler', 'read'),
	array('Data_Cache_Session_Handler', 'write'),
	array('Data_Cache_Session_Handler', 'destroy'),
	array('Data_Cache_Session_Handler', 'gc')
);

?>