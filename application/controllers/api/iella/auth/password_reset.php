<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('api/iella/base');

class Password_Reset_Controller extends Iella_Base {
	
	public function index()
	{
		$email = $this->iella_in->email;
		if (!($user = Model_User::find_email($email)))
			return $this->iella_out->status = false;
		
		// generate random nonce and a hash 
		$nonce = substr(md5(microtime()), 0, 4);
		$hash = md5("{$nonce}{$user->password}");
		
		// send email with link to user
		$en = new Email_Notification();
		$en->set_data('user', $user);
		$en->set_data('nonce', $nonce);
		$en->set_data('hash', $hash);
		$en->set_data('user', $user);
		$en->set_content_view('password_reset');
		$en->send($user, 'iNewswire Password Reset');
		
		$this->iella_out->status = true;
	}
	
}

?>