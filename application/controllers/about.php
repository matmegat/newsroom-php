<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('browse/base');

class About_Controller extends Browse_Base {

	public function index()
	{
		$this->load->view('browse/header');
		$this->load->view('browse/about');
		$this->load->view('browse/footer');
	}

}

?>