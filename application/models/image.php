<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Image extends Model {
	
	public $id;
	public $company_id;
	
	protected static $__table = 'nr_image';
	protected static $__primary = 'id';
	
	public function add_variant($stored_image_id, $name = null)
	{
		if (!$this->id)
			throw new Exception();
		
		$this->db->query(
			"INSERT INTO nr_image_variant (image_id, 
			 stored_image_id, name) VALUES (?, ?, ?)", 
			array($this->id, $stored_image_id, $name)
		);
	}
	
	public function variants()
	{
		$sql = "SELECT iv.name, si.id as stored_image_id, 
			si.width, si.height, si.filename FROM nr_image_variant iv
			INNER JOIN nr_stored_image si ON iv.stored_image_id = 
			si.id WHERE iv.image_id = ?";
			
		$query = $this->db->query($sql, array($this->id));
		$variants = array();
		
		foreach ($query->result() as $row)
			$variants[] = $row;
		
		return $variants;
	}
	
	public function variant($name)
	{
		$sql = "SELECT iv.name, si.id as stored_image_id, 
			si.width, si.height, si.filename FROM nr_image_variant iv
			INNER JOIN nr_stored_image si ON iv.stored_image_id = 
			si.id WHERE iv.image_id = ? AND iv.name = ?";
			
		$query = $this->db->query($sql, array($this->id, $name));
		return $query->row();
	}
	
	public function remove()
	{
		// we don't remove the stored image as no guarantee
		// that it is not used elsewhere (filename based on content)
		$this->db->delete('nr_image', array('id' => $this->id));
		$this->db->delete('nr_image_variant', array('image_id' => $this->id));
	}
		
}

?>