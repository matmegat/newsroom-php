<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('browse/base');

class Login_Controller extends Browse_Base {

	public function index()
	{
		$this->do_login();
	
		if ($this->is_website_host)
		{
			// remove this once done
			// ---------------------
			$this->redirect(gstring('Login'));
			// ---------------------
			
			$this->load->view('website/header');
			$this->load->view('website/login');
			$this->load->view('website/footer');
		}
		else
		{
			$this->load->view('browse/header');
			$this->load->view('shared/login');
			$this->load->view('browse/footer');
		}
	}
	
	public function do_login()
	{
		$email = strtolower($this->input->post('email'));
		$password = $this->input->post('password');
		
		if (!empty($email))
		{
			$errors = array(
				Auth::ERROR_NONE 				=> null,
				Auth::ERROR_CREDENTIALS 	=> 'Invalid Credentials',
				Auth::ERROR_DISABLED 		=> 'Account Disabled',
				Auth::ERROR_NOT_VERIFIED 	=> 'Account Not Verified',
			);
			
			if ($user = Auth::authenticate($email, $password)) 
			{
				if ($hash = $this->input->get('intent'))
				{
					$intent = Data_Cache::read($hash);
					Data_Cache::delete($hash);
					$this->redirect($intent);
				}
				 
				$this->redirect('default');
			}
			
			$error_code = Auth::__error_code();
			$this->vd->error_code = $error_code;
			$this->vd->error_text = $errors[$error_code];
		}
	}

}

?>