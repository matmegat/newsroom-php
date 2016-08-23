<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CSV_Reader {
	
	protected $filename;
	protected $handle;
	
	public function __construct($filename)
	{
		$this->filename = $filename;
		$this->handle = fopen($filename, 'r');
	}
	
	public function handle()
	{
		return $this->handle;
	}
	
	public function read()
	{
		if (feof($this->handle)) 
		{
			fclose($this->handle);
			return;
		}
		
		$line = @fgetcsv($this->handle);
		return $line;
	}
	
	public function close()
	{
		fclose($this->handle);
	}
	
}

?>