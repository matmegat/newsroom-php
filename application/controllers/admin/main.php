<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class Main_Controller extends Admin_Base {

	public function index()
	{
		$this->redirect('admin/publish/pr/under_review');
	}

}

?>