<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Limit_Newsroom_Held extends Model_Limit_Held_Collection {
	
	protected static $__table = 'nr_limit_newsroom_held';	
	public function consume($context) {}	
	
	public static function find_collection($user)
	{
		if ($user instanceof Model_User)
			$user = $user->id;
		
		$criteria = array();
		$criteria[] = array('user_id', $user);
		$criteria[] = array('date_expires', 
			static::CMP_GREATER_THAN_OR_EQUAL, 
			Date::$now->format(Date::FORMAT_MYSQL));
		
		$collection = static::find_all($criteria);
		$virtual = new static();
		$virtual->collection = $collection;
		$virtual->is_collection = true;
		
		return $virtual;
	}
	
	public static function find_user($user)
	{
		return static::find_collection($user);
	}
	
}

?>