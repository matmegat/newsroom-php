<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

abstract class Model_Limit_Held_Collection extends Model_Limit_Base {
	
	protected static $__table = null;
	protected $is_collection = false;
	protected $amount_used = 0;
	protected $collection;	
	
	public function collection()
	{
		return $this->collection;
	}
	
	public function collection_available()
	{
		if (!$this->is_collection)
			throw new Exception();
		
		$available = 0;
		foreach ($this->collection as $_this)
			$available += $_this->available();
		return $available;
	}
	
	public function collection_total()
	{
		if (!$this->is_collection)
			throw new Exception();
		
		$total = 0;
		foreach ($this->collection as $_this)
			$total += $_this->total();
		return $total;
	}
	
	public function collection_used()
	{
		if (!$this->is_collection)
			throw new Exception();
		
		$used = 0;
		foreach ($this->collection as $_this)
			$used += $_this->used();
		return $used;
	}
	
	public function available()
	{
		if ($this->is_collection)
			return $this->collection_available();
		return max(0, $this->total() - $this->used());
	}
	
	public function total()
	{
		if ($this->is_collection)
			return $this->collection_total();
		return $this->amount_total;
	}
	
	public function used()
	{
		if ($this->is_collection)
			return $this->collection_used();
		return $this->amount_used;
	}
	
	public function collection_size()
	{
		if ($this->is_collection)
			return count($this->collection);
		return 0;
	}
	
}

?>