<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_ID_Store extends Model {
	
	protected static $__table = 'nr_id_store';
	protected static $__primary = 'name';
	
	public static function next($name)
	{
		if (!($ob = static::find($name)))
			throw new Exception();
		$next = $ob->next++;
		$ob->save();
		return $next;
	}
	
}

?>