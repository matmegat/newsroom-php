<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Download_Controller extends CIL_Controller {

	protected function file($token)
	{
		$session_name = "download_token_{$token}";
		$file = Data_Cache::read($session_name);
		Data_Cache::delete($session_name);
		return $file;
	}
	
	public function report($token)
	{
		if (!($file = $this->file($token))) return;
		$report = Report::from_file($file);
		$report->deliver();
	}

}

?>