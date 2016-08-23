<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');
load_shared_fnc('shared/account/details');

class Details_Controller extends Manage_Base {
	
	public $title = 'Account Details';
	
	public function index()
	{
		Account_Details_Shared::save();
		
		$this->load->view('manage/header');
		$this->load->view('manage/account/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('shared/account/details');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
}

?>