<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');
load_shared_fnc('shared/account/billing');

class Billing_Controller extends Manage_Base {

	public $title = 'Billing';

	public function index($option = null)
	{		
		if ($option === 'autoOrder' || $option === 'dbOrder')
		     $view_data = Billing_Shared::view_order($option);
		else $view_data = Billing_Shared::view_list($option);
		
		$this->load->view('manage/header');
		$this->load->view('manage/account/menu');
		$this->load->view('manage/pre-content');
		
		if ($option === 'autoOrder' || $option === 'dbOrder')
		     $view_data = $this->load->view('shared/account/billing/view', $view_data);
		else $view_data = $this->load->view('shared/account/billing/index', $view_data);
		
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}

}

?>