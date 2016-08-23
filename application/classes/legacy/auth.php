<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class LEGACY_Auth {
	
	// perform a login or logout of the user
	// $user => user record to login
	public static function save($user = false)
	{
		if ($user) static::login($user);
		else static::logout();
	}
	
	public function login($user)
	{
		$ci =& get_instance();
		$session =& $ci->session->reference();
	
		// find user in old database
		$ldb = LEGACY::database();
		$result = $ldb->select('*')->from('users')
			->where('email', $user->email)->get();
		$user_record = $result->row();
		
		// save the user details out to session
		$session['userID'] = $user_record->id;
		$session['userEmail'] = $user_record->email;
		$session['userFname'] = $user_record->fname;
		$session['userLname'] = $user_record->lname;
		$session['admin'] = $user_record->admin;
		$session['user_package_deal_id'] = $user_record->user_package_deal_id;
		$session['prreview'] = $user_record->prreview;
		$session['resellerinterventionifadmineditor']
			= $user_record->resellerinterventionifadmineditor;
		$session['user_is_migrated'] = $user_record->is_migrated;
	}
	
	public function logout()
	{
		$ci =& get_instance();
		$session =& $ci->session->reference();
	
		// clear all user session
		unset($session['userID']);
		unset($session['userEmail']);
		unset($session['userFname']);
		unset($session['userLname']);
		unset($session['admin']);
		unset($session['user_package_deal_id']);
		unset($session['prreview']);
		unset($session['resellerinterventionifadmineditor']);	
		unset($session['user_is_migrated']);
	}
	
}

?>