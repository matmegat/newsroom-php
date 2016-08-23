<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CSV_Writer {
	
	protected $filename;
	protected $handle;
	
	public function __construct($filename)
	{
		$this->filename = $filename;
		$this->handle = fopen($filename, 'w');
	}
	
	public function handle()
	{
		return $this->handle;
	}
	
	public function write($data)
	{
		if (feof($this->handle)) 
		{
			fclose($this->handle);
			return;
		}
		
		$res = fputcsv($this->handle, $data);
		return $res !== false;
	}
	
	public function close()
	{
		fclose($this->handle);
	}
	
}

?>