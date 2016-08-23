<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_Details_Shared {
	
	public static function save()
	{
		$ci =& get_instance();
		$user = $ci->vd->user = Auth::user();
		if (!$ci->input->post('save')) return;	
		
		$password = $ci->input->post('password');
		$assume_account_owner = $ci->session->get('assume_account_owner');
		if ($assume_account_owner || Model_User::authenticate($user->email, $password))
		{
			$ci->session->delete('assume_account_owner');
			$user->values(array(
				'first_name' => $ci->input->post('first_name'),
				'last_name' => $ci->input->post('last_name'),
				'email' => $ci->input->post('email'),
			));
			
			$save_success = true;
			$new_password = $ci->input->post('new_password');
			$new_password_confirm = $ci->input->post('new_password_confirm');
			
			if ($new_password)
			{
				if (strlen($new_password) < 6)
				{
					// load feedback message for the user
					$feedback_view = 'shared/account/partials/save_bad_length_feedback';
					$feedback = $ci->load->view($feedback_view, null, true);
					$ci->use_feedback($feedback);
					$save_success = false;
				}
				else if ($new_password !== $new_password_confirm)
				{
					// load feedback message for the user
					$feedback_view = 'shared/account/partials/save_bad_match_feedback';
					$feedback = $ci->load->view($feedback_view, null, true);
					$ci->use_feedback($feedback);
					$save_success = false;
				}
				else
				{
					$user->set_password($new_password);
				}
			}
			
			$user->save();
			
			if ($save_success)
			{
				// load feedback message for the user
				$feedback_view = 'shared/account/partials/save_feedback';
				$feedback = $ci->load->view($feedback_view, null, true);
				$ci->use_feedback($feedback);
			}
		}
		else
		{
			// load feedback message for the user
			$feedback_view = 'shared/account/partials/save_bad_auth_feedback';
			$feedback = $ci->load->view($feedback_view, null, true);
			$ci->use_feedback($feedback);
		}
	}

}

?>