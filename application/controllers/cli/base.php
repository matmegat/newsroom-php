<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CLI_Base extends CIL_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if (!$this->input->is_cli_request()) 
			exit(-1);
	}
	
	public function console($text)
	{
		echo $text;
		echo PHP_EOL;
		ob_flush();
		flush();
	}
	
}

?>