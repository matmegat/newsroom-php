<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('reseller/base');

class Main_Controller extends Reseller_Base {

	public function index()
	{
		$this->redirect('reseller/dashboard');
	}

}

?>