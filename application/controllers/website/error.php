<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Error_Controller extends CIL_Controller {
		
	public function status_404()
	{
		set_status_header(404);
		
		$this->title = 'File Not Found';		
		$this->load->view('website/header');
		$this->load->view('website/error/404');
		$this->load->view('website/footer');
	}

}

?>