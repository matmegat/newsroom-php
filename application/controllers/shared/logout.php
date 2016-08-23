<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout_Controller extends CIL_Controller {

	public function index()
	{
		$this->set_redirect('shared/login');
		$this->session->delete('nr_feedback');
		$this->session->delete('video_guide_record');
		Auth::logout();
	}

}

?>