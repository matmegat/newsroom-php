<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reseller_Base extends CIL_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!Auth::is_user_online() || 
		    !Auth::user()->is_reseller)
			$this->denied();
	}

}

?>