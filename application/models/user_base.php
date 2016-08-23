<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_User_Base extends Model {
	
	protected static $__table = 'nr_user_base';
	
	public static function create_user()
	{
		$base = new static();
		$base->id = Model_ID_Store::next('user');
		$base->date_created = Date::$now->format(Date::FORMAT_MYSQL);
		$base->save();
		
		$user = Model_User::find($base->id);
		return $user;
	}
	
}

?>