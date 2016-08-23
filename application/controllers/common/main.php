<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('shared/login');

class Main_Controller extends Login {
	
	public function index()
	{
		$this->do_login();
		
		$this->load->view('common/header');
		$this->load->view('common/login');
		$this->load->view('common/footer');
	}
	
}

?>