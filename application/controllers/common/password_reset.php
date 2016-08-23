<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Password_Reset_Controller extends CIL_Controller {
	
	public function index($user_id, $nonce, $hash)
	{
		if (Auth::is_user_online()) $this->redirect('default');
		if (!($user = Model_User::find($user_id))) return;
		$actual_hash = md5("{$nonce}{$user->password}");
		if ($actual_hash !== $hash) return;
		
		$password = Model_User::generate_password();
		$user->set_password($password);
		Auth::login($user);
		$user->save();
		
		// load feedback message for the user
		$this->vd->password = $password;
		$feedback_view = 'shared/account/partials/password_reset_feedback';
		$feedback = $this->load->view($feedback_view, null, true);
		$this->add_feedback($feedback);
		
		// allows the user to set a new password
		// without knowing the old one
		$this->session->set('assume_account_owner', 1);
		$this->redirect('manage/account/details');
	}
	
}

?>