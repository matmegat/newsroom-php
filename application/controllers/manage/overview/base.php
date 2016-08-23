<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Overview_Base extends Manage_Base {

	protected $use_overview_css = true;
	
	public function __construct()
	{
		parent::__construct();
		
		if (!$this->is_common_host)
		{
			$url = $this->uri->uri_string();
			$url = $this->common()->url($url);
			$this->redirect($url, false);
		}
		
		$this->check_auth();
		
		if ($this->use_overview_css)
		{
			// add resources specific to overview
			$view = 'manage/overview/partials/eob';
			$eob = $this->load->view($view, null, true);
			$this->add_eob($eob);
		}
	}
	
	public function check_auth()
	{
		// platinum account has all access
		if (Auth::user()->has_platinum_access()) return;
		// dashboard is allowed for other users
		if ($this->uri->segment(3) === 'dashboard') return;
		
		// redirect to non-overview version
		$url = $this->uri->uri_string();
		$url = preg_replace('#^manage/overview#', 'manage', $url);
		$this->redirect(gstring($url));
	}

}

?>