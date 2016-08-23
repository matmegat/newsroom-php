<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Iella_Event extends Model {
	
	// event_name          --   idx => value
	// --------------------------------------
	// content_approved    --   id => (int)
	// content_published   --   id => (int)
	// content_rejected    --   id => (int)
	
	protected static $__table = 'nr_iella_event';
	
	public static function find($name)
	{
		$criteria = array('name', $name);
		return static::find_all($criteria);
	}
	
}

?>