<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('reseller/base');

class Dashboard_Controller extends Reseller_Base {

	public function index()
	{
		$this->load->view('reseller/header');
		$this->load->view('reseller/dashboard/menu');
		$this->load->view('reseller/pre-content');
		$this->load->view('reseller/dashboard/index');
		$this->load->view('reseller/post-content');
		$this->load->view('reseller/footer');
	}

}

?>