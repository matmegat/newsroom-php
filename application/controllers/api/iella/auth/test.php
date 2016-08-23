<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('api/iella/base');

class Test_Controller extends Iella_Base {
	
	public function index()
	{
		$email = $this->iella_in->email;
		$password = $this->iella_in->password;		
		$user = Model_User::authenticate($email, $password);
		$this->iella_out->user = $user;
	}
	
}

?>