<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Cat extends Model {
	
	public $id;
	public $cat_group_id;
	public $name;
	public $slug;
	
	protected static $__table = 'nr_cat';
	protected static $__url_prefix = 'browse/cat/';
	
	public static function list_all_cats_by_group()
	{
		$groups = array();
		$ci =& get_instance();
		$sql = "SELECT c.id, c.name, c.slug, c.is_listed,
			c.cat_group_id as group_id, g.name AS group_name
			FROM nr_cat c INNER JOIN nr_cat_group g
			ON c.cat_group_id = g.id
			ORDER BY g.name ASC, c.name ASC";
		
		$query = $ci->db->query($sql);
		$current_group = new stdClass();
		$current_group->id = -1;
		
		foreach ($query->result() as $result)
		{
			if ((int) $result->group_id !== $current_group->id)
			{
				$current_group = new stdClass();
				$current_group->id = (int) $result->group_id;
				$current_group->name = $result->group_name;
				$current_group->is_listed = $result->is_listed;
				$current_group->cats = array();
				$groups[] = $current_group;
			}
			
			$current_group->cats[] = $result;
		}
		
		return $groups;
	}
	
	public function get_group_name()
	{
		$group = $this->db
			->select('name')
			->from('nr_cat_group')
			->where('id', $this->cat_group_id)
			->get()
			->row();
			
		return $group->name;
	}

	public function url() 
	{
		return static::$__url_prefix . $this->slug;
	}
	
}

?>