<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_Request_Base extends CIL_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$no_cache = "Cache-Control: no-store, no-cache, must-revalidate";
		$this->output->set_header($no_cache);
		
		if (!$this->is_common_host) exit(-1);
		if (!($newsroom_name = $this->input->get('newsroom'))) exit(-1);
		$this->newsroom = Model_Newsroom::find_name($newsroom_name);
		if (!$this->newsroom) show_404($this->uri->uri_string());
		
		if (!Auth::is_user_online()) exit(-1);	
		$newsroom_user_id = (int) $this->newsroom->user_id;
		$auth_user_id = (int) Auth::user()->id;
		if ($auth_user_id !== $newsroom_user_id) 
			exit(-1);
	}
	
}

?>