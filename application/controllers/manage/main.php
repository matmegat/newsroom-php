<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Main_Controller extends Manage_Base {

	public function index()
	{
		if (Auth::user()->is_free_user())
			$this->redirect('manage/dashboard');		
		if (!$this->is_common_host)
			$this->redirect('manage/dashboard');		
		if (count($this->vd->user_newsrooms) <= 1 && !Auth::user()->has_platinum_access())
			$this->redirect('manage/dashboard');
				
		$this->redirect('manage/overview/dashboard');
	}

}

?>