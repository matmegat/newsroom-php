<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Website_Base extends CIL_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		if (!$this->is_website_host)
		{
			$url = $this->website_url($this->uri->uri_string());
			$this->redirect(gstring($url), false);
		}
	}

}

?>