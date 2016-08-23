<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_Controller extends CIL_Controller {
	
	public function index()
	{
		if ($this->input->post())
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			// find user in old database
			$this->ldb = LEGACY::database();
			$result = $this->ldb->select('*')->from('users')
				->where('email', $email)
				->where('password', $password)
				->get();
				
			if ($user_record = $result->row())
			{
				if ($user_record->active && !$user_record->verify_code)
				{
					LEGACY_Auth::login($user_record);
					$this->redirect('common/migration');
				}
			
				$this->vd->error = 'Account is not active';
			}
			else
			{
				$this->vd->error = 'Invalid credentials';
			}
		}
		
		// redirect back to here
		$hash = md5(microtime(true));		
		Data_Cache::write($hash, 'common/migration');
		$this->vd->intent = $hash;
		$this->load->view('common/migration/login/index');
	}
	
}

?>