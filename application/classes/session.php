<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Session {
	
	protected $data = array();
	protected $closed = false;
	protected $duration;
	protected $path;
	protected $domain;
	
	public function __construct($duration = 86400, $path = null, $domain = null)
	{
		$this->duration = $duration;
		$this->domain = $domain;
		$this->path = $path;
				
		if (!$this->id())	
			$this->start();
		
		$this->data =& $_SESSION;
		if (!isset($this->data['session_refresh']))
			$this->data['session_refresh'] = time();			
		$refresh_time = (int) $this->data['session_refresh'];
		$elapsed_time = time() - $refresh_time;
		
		if ($elapsed_time >= ($duration / 2))
		{
			$this->data['session_refresh'] = time();
			session_regenerate_id(true);
		}
	}
	
	public function id()
	{
		return session_id();
	}
	
	public function close()
	{
		$this->commit();
	}
	
	public function commit()
	{
		Data_Cache_Session_Handler::commit();
	}
	
	public function reload()
	{
		Data_Cache_Session_Handler::reload();
	}
	
	public function & reference()
	{
		return $this->data;
	}
	
	public function delete($name)
	{
		unset($this->data[$name]);
	}
	
	public function set($name, $value)
	{
		$this->data[$name] = $value;
	}
	
	public function get($name)
	{
		if (!isset($this->data[$name])) return null;
		return $this->data[$name];
	}
	
	public function write($name, $value)
	{
		return $this->set($name, $value);
	}
	
	public function read($name)
	{
		return $this->get($name);
	}
	
	public function start()
	{
		ini_set('session.gc_maxlifetime', $this->duration); 
		session_set_cookie_params($this->duration, 
			$this->path, $this->domain);
		session_start();
	}
	
}

?>