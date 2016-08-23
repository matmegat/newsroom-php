<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Main_Controller extends Manage_Base {

	public function index()
	{
		$this->redirect('manage/analyze/content');
	}
	
}

?>