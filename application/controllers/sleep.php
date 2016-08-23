<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sleep_Controller extends CIL_Controller {

	public function index($seconds)
	{
		$this->set_redirect('assets/im/trans.gif');
		sleep((int) $seconds);
	}

}

?>