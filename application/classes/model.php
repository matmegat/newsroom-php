<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model extends Model_Base implements Serializable {

	protected $__original = null;
	protected $db;
	
	protected static $__table = null;
	protected static $__primary = 'id';
	protected static $__fields = null;
	protected static $__auto_fields = array();
	
	const CMP_EQUAL                    = '=';
	const CMP_LESS_THAN                = '<';
	const CMP_GREATER_THAN             = '>';
	const CMP_GREATER_THAN_OR_EQUAL    = '>=';
	const CMP_LESS_THAN_OR_EQUAL       = '<=';
	const CMP_NOT_EQUAL                = '!=';
	const CMP_STRING_LIKE              = 'LIKE';
	const CMP_STRING_REGEX             = 'REGEXP';
		
	public function __construct()
	{
		$ci =& get_instance();
		$this->db =& $ci->db;		
		if (!is_array(static::$__primary))
			$this->{static::$__primary} = null;
	}
	
	public function values($values = null)
	{
		if ($values === null)
		{
			// return this as object
			$values = new stdClass();
			foreach (static::__fields() as $field)
				$values->{$field} = $this->{$field};
			return $values;
		}
		else
		{
			// store values into this
			foreach ($values as $k => $v)
				if ($k !== static::$__primary) 
					$this->$k = $v;
		}
	}
	
	public function serialize()
	{
		return serialize($this->values());
	}

	public function unserialize($str)
	{
		$values = unserialize($str);
		$this->values($values);
	}
	
	protected function __is_new()
	{
		return !$this->__original;
	}
	
	protected function __fields()
	{
		if (static::$__fields) 
			return static::$__fields;
		
		$table = static::$__table;
		if (isset(static::$__auto_fields[$table])) 
			return static::$__auto_fields[$table];
		
		$sql = "SHOW COLUMNS FROM {$table}";
		$query = $this->db->query($sql);
		static::$__auto_fields[$table] = array();
		foreach ($query->result() as $field)
			static::$__auto_fields[$table][] = $field->Field;
		
		return static::$__auto_fields[$table];
	}
	
	public function delete()
	{
		if (!$this->{static::$__primary}) return;		
		$this->db->delete(static::$__table, 
			array(static::$__primary => 
			$this->{static::$__primary}));
	}
	
	public function save()
	{
		if (!static::$__primary && 
		    !is_array(static::$__primary))
			throw new Exception();
		
		$save_data = array();
		
		if ($this->__original)
		{
			foreach ($this->__fields() as $k) 
				if (@$this->__original->$k != $this->$k)
					$save_data[$k] = $this->$k;
			
			if (is_array(static::$__primary))
			{
				$condition = array();
				foreach (static::$__primary as $k) 
					$condition[$k] = $this->__original->$k;
			}
			else
			{
				$condition = array(static::$__primary => 
					$this->{static::$__primary});
			}
				
			if (!count($save_data)) return;
			$this->db->update(static::$__table, 
				$save_data, $condition);
		}
		else
		{
			if (!is_array(static::$__primary))
				$save_data[static::$__primary] = null;
			foreach ($this->__fields() as $k)
				if (isset($this->$k)) 
					$save_data[$k] = $this->$k;
				
			if (!count($save_data)) return;
			$this->__original = new stdClass();
			$this->db->insert(static::$__table, $save_data);
			if (!is_array(static::$__primary) && !$this->{static::$__primary})
				$this->{static::$__primary} = $this->db->insert_id();
		}
		
		foreach ($this->__fields() as $k) 
			$this->__original->$k = $this->$k;
	}
	
	public function reload()
	{
		if (!static::$__primary)
			throw new Exception();
		
		if (is_array(static::$__primary))
		{
			$criteria = array();
			foreach (static::$__primary as $k)
				$criteria[] = array($k, $this->$k);
			$loaded = static::find($criteria);
		}
		else
		{
			$loaded = static::find_id($this->{static::$__primary});
		}
		
		foreach ($loaded->__original as $k => $v)
			$this->__original->$k = $this->$k = $v;
	}
	
	public static function find_id($id)
	{
		if (!$id) return false;
		
		$ci =& get_instance();
		$dbi = $ci->db->select('*')
			->from(static::$__table);
		
		if (is_array(static::$__primary))
		{
			foreach (static::$__primary as $i => $k)
				$dbi = $dbi->where($k, $id[$i]);
		}
		else
		{
			$dbi = $dbi->where(static::$__primary, $id);
		}
			
		$result = $dbi->get();
		return static::from_db($result);
	}
	
	public static function find($name, $value = null)
	{
		// find for criteria
		if (is_array($name))
		{
			$found = static::find_all($name);
			if (!$found) return false;
			return $found[0];
		}
		
		// find for id
		if ($value === null)
			return static::find_id($name);
					
		$ci =& get_instance();
		$dbi = $ci->db->select('*')
			->from(static::$__table)
			->where($name, $value);
			
		$result = $dbi->get();
		return static::from_db($result);
	}
	
	// criteria should be an array of the form:
	// [[field,op,value],[field,value]]
	// order should be an array of the form:
	// [field,asc|desc]
	public static function find_all($criteria = array(), $order = null, $limit = false, $count = false)
	{
		$ci =& get_instance();
		$dbi = $ci->db->select(value_if_test($count, '1', '*'))
			->from(static::$__table);
			
		if ($criteria === null)
			$criteria = array();
		
		// just one criteria (so not wrapped in array)
		if (isset($criteria[0]) && !is_array($criteria[0]))
			$criteria = array($criteria);
		
		foreach ($criteria as $criterion)
		{
			if (count($criterion) === 0) continue;
			if (count($criterion) === 1)
			{
				$left = $criterion[0];
				$right = null;
			}
			else if (count($criterion) === 2)
			{
				$left = $criterion[0];
				$right = $criterion[1];
			}
			else
			{
				$left = "{$criterion[0]} {$criterion[1]}";
				$right = $criterion[2];				
			}
			
			// left, right, escape column names
			$dbi->where($left, $right, $right !== null);
		}
		
		// count results instead of fetch
		if ($count) return $dbi->count_all_results();
		
		if ($order !== null)
			// order by the given field
			$dbi->order_by($order[0], $order[1]);
		
		if ($limit !== false)
			// limit no results
			$dbi->limit($limit);
		
		$result = $dbi->get();		
		return static::from_db_all($result);
	}
	
	// criteria should be an array of the form:
	// [[field,op,value],[field,value]]
	public static function count($criteria = array())
	{
		return static::find_all($criteria, null, false, true);
	}
	
	public static function count_all($criteria = array())
	{
		return static::count($criteria);
	}

}

?>