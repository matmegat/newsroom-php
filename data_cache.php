<?php

class Data_Cache {
	
	protected static $memcache;
	protected static function connect()
	{
		if (!static::$memcache)
			static::$memcache = new Memcache();
		static::$memcache->pconnect('127.0.0.1');
	}
	
	public static function delete($name)
	{
		static::connect();
		static::$memcache->delete($name);
	}
	  
	public static function read($name)
	{
		static::connect();
		return static::$memcache->get($name);
	}
	
	public static function write($name, $value, $expires = 86400)
	{
		static::connect();
		return static::$memcache->set($name, $value, MEMCACHE_COMPRESSED, $expires);
	}
	
}

?>