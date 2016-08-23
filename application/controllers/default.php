<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Default_Controller extends CIL_Controller {

	public function index()
	{
		if (!Auth::is_user_online()) 
			$this->redirect(null);
		
		$user = Auth::user();
		$common = $this->common();
		if ($user->is_reseller) $this->redirect($common->url('reseller'), false);
		if ($user->is_admin) $this->redirect($common->url('admin'), false);
		$this->redirect($common->url('manage'), false);
	}

}

?>