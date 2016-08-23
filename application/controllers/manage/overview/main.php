<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/overview/base');

class Main_Controller extends Overview_Base {

	public function index()
	{
		$this->redirect('manage/overview/dashboard');
	}
	
	public function newsroom()
	{
		$this->redirect('manage/companies');
	}

}

?>