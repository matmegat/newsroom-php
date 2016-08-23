<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Detached_Content extends Model_Content {
	
	public $__detached_tags = array();
	public $__detached_images = array();
	public $__detached_related = array();
	
	public function save() {}
	public function reload() {}
	public function values() {}
	public function delete() {}
	
	public function set_tags($values)
	{
		foreach ($values as $k => &$value)
			if (!($value = trim($value))) unset($values[$k]);
		$this->__detached_tags = $values;
	}
	
	public function set_images($values)
	{
		foreach ($values as &$value)
			if ($value instanceof Model_Image) 
				$value = $value->id;
		$this->__detached_images = $values;
	}
	
	public function set_related($values)
	{		
		foreach ($values as &$value)
			if ($value instanceof Model_Content) 
				$value = $value->id;
		$this->__detached_related = $values;
	}
	
	public function get_tags()
	{
		return $this->__detached_tags;
	}
	
	public function get_images()
	{
		$image_ids = $this->__detached_images;
		if (!count($image_ids)) return array();
		
		$image_ids_str = implode(',', $image_ids);
		$query = $this->db->query("SELECT i.* FROM nr_image i 
			WHERE i.id IN ({$image_ids_str})");
		
		$images = Model_Image::from_db_all($query);
		return $images;
	}
	
	public function get_related()
	{		
		$related_ids = $this->__detached_related;
		if (!count($related_ids)) return array();
		
		$related_ids_str = implode(',', $related_ids);
		$query = $this->db->query("SELECT c.* FROM nr_content c 
			WHERE c.id IN ({$related_ids_str})");
		
		$related_set = Model_Content::from_db_all($query);
		return $related_set;
	}
	
	public function load_content_data() {}
	public function load_local_data() {}
	public function load_data() {}
	
}

?>