<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Upgrade_Controller extends Manage_Base {
	
	public function premium()
	{
		// load feedback message for the user
		$feedback_view = 'manage/upgrade/partials/premium_feedback';
		$feedback = $this->load->view($feedback_view, null, true);
		$this->use_feedback($feedback);
		$this->index();
	}
	
	public function newsroom()
	{
		if (Auth::user()->newsroom_credits_available())
		{
			// load feedback message for the user
			$this->vd->redirect = $this->session->get('upgrade-redirect');
			$this->session->delete('upgrade-redirect');
			$feedback_view = 'manage/upgrade/partials/newsroom_activation_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
			$this->redirect('manage/companies');
		}
		else
		{
			// load feedback message for the user
			$feedback_view = 'manage/upgrade/partials/newsroom_needed_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			$this->index();
		}
	}
	
	public function index()
	{
		$this->load->view('manage/header');
		$this->load->view('manage/upgrade/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/upgrade/index');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
}

?>