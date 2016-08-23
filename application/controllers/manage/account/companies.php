<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Companies_Controller extends Manage_Base {
	
	public function index()
	{
		$this->redirect('manage/companies');
	}
	
}

?>