<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('website/base');

class Index_Controller extends Website_Base {
	
	public function index()
	{
		echo 'Main_Controller extends Website_Base => index';
	}

}

?>