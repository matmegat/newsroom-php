<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Limit_Email_Held extends Model_Limit_Held_Collection {
	
	protected static $__table = 'nr_limit_email_held';	
	
	public static function find_collection($user)
	{
		if ($user instanceof Model_User)
			$user = $user->id;
		
		$criteria = array();
		$criteria[] = array('user_id', $user);
		$criteria[] = array('date_expires >= UTC_TIMESTAMP()');
		
		$order = array(static::$__primary, 'asc');
		$collection = static::find_all($criteria, $order);
		$virtual = new static();
		$virtual->collection = $collection;
		$virtual->is_collection = true;
		
		return $virtual;
	}
	
	public static function find_user($user)
	{
		return static::find_collection($user);
	}
	
	public function consume($count)
	{
		if ($this->is_collection)
		{
			$initial_count = $count;
			foreach ($this->collection as $_this)
				$count -= $_this->consume($count);
			return $initial_count - $count;	
		}
		else
		{
			if (!$this->available()) return;
			$consume = min($count, $this->available());
			$this->amount_used += $consume;
			$this->save();
			return $consume;
		}
	}
	
}

?>