<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Beat extends Model {
	
	public $id;
	public $beat_group_id;
	public $name;
	
	protected static $__table = 'nr_beat';
	
	public static function list_all_beats_by_group()
	{
		$groups = array();
		$ci =& get_instance();
		$sql = "SELECT b.id, b.name, 
			b.beat_group_id as group_id, g.name AS group_name
			FROM nr_beat b INNER JOIN nr_beat_group g
			ON b.beat_group_id = g.id
			ORDER BY g.name ASC, b.name ASC";
		
		$query = $ci->db->query($sql);
		$current_group = new stdClass();
		$current_group->id = -1;
		$current_group->name = null;
		$current_group->beats = array();
		
		foreach ($query->result() as $result)
		{
			if ((int) $result->group_id !== $current_group->id)
			{
				$current_group = new stdClass();
				$current_group->id = (int) $result->group_id;
				$current_group->name = $result->group_name;
				$current_group->beats = array();
				$groups[] = $current_group;
			}
			
			$current_group->beats[] = $result;
		}
		
		return $groups;
	}
	
	public function get_group_name()
	{
		$group = $this->db
			->select('name')
			->from('nr_beat_group')
			->where('id', $this->beat_group_id)
			->get()
			->row();
			
		return $group->name;
	}
	
}

?>