<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Blocked extends Model {
	
	protected static $__table = 'nr_blocked';
	protected static $__primary = 'addr';
	
	public function save()
	{
		$date_now = Date::$now->format(Date::FORMAT_MYSQL);
		$this->date_blocked = $date_now;
		
		if ($this->__is_new() && $blocked = Model_Blocked::find($this->addr))
		{
			$blocked->date_blocked = $date_now;
			$blocked->save();
			return;
		}
		
		parent::save();
	}
	
}

?>