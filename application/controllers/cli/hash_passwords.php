<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Hash_Passwords_Controller extends CLI_Base {
	
	public function index()
	{
		$users = Model_User::find_all();
		foreach ($users as $user)
		{
			if (!$user->is_migrated) continue;
			if (preg_match('#^\$2a\$\d{2}\$#', $user->password)) continue;
			echo $user->email;
			$user->set_password($user->password);
			$user->save();
		}
	}
	
}

?>