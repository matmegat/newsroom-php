<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Bar {
	
	protected $id;
	protected $display_name;
	protected $stages;
	protected $is_done;
	protected $newsroom;
	
	const RC_PREMIUM_PR  = 'PREMIUM_PR';
	const RC_BASIC_PR    = 'BASIC_PR';
	const RC_EMAIL       = 'EMAIL';
	const RC_NEWSROOM    = 'NEWSROOM';
	
	public function __construct($name, $newsroom = null)
	{
		$ci =& get_instance();		
		if (!$newsroom) $newsroom = $ci->newsroom;
		$company_id = $newsroom->company_id;
		$this->newsroom = $newsroom;
		
		$sql = "SELECT id, display_name FROM 
			nr_bar WHERE name = ?";
		
		$result = $ci->db
			->query($sql, array($name))
			->row();
			
		$this->id = (int) $result->id;
		$this->display_name = $result->display_name;
		
		$sql = "SELECT stage.display_name, stage.info_link, stage.requires_credit,
			IF (record.company_id IS NULL, 0, 1) AS is_done FROM 
			nr_bar_stage stage LEFT JOIN 
			nr_bar_record record ON
			record.company_id = {$company_id} AND
			stage.id = record.bar_stage_id
			WHERE stage.bar_id = {$this->id}";
		
		$result_set = $ci->db->query($sql);
		$this->is_done = true;
		
		foreach ($result_set->result() as $result)
		{
			if ($result->requires_credit === static::RC_PREMIUM_PR)
				if (!Auth::user()->premium_pr_credits()) $result->is_done = true; 
			if ($result->requires_credit === static::RC_BASIC_PR)
				if (!Auth::user()->basic_pr_credits()) $result->is_done = true;
			if ($result->requires_credit === static::RC_EMAIL)
				if (!Auth::user()->email_credits()) $result->is_done = true;
			
			if ($result->requires_credit === static::RC_NEWSROOM)
				if (!Auth::user()->newsroom_credits()) $result->is_done = true;
			if ($result->requires_credit === static::RC_NEWSROOM)
				if ($newsroom->is_active) $result->is_done = true;
			
			$stage = new stdClass();
			$stage->display_name = $result->display_name;
			$stage->info_link = $result->info_link;
			$stage->is_done = (bool) $result->is_done;
			$this->stages[] = $stage;
			
			if (!$result->is_done) 
				$this->is_done = false;
		}
	}
	
	public function render()
	{
		$ci =& get_instance();
		$view_data = array('bar' => $this);
		return $ci->load->view('manage/partials/bar', $view_data, true);
	}
	
	public function percentage()
	{
		$percentage = 100;
		$per_stage = (100 / count($this->stages));
		$stages = $this->stages();
		
		foreach ($stages as $stage)
			if (!$stage->is_done)
				$percentage -= $per_stage;
			
		return round($percentage, 1);
	}
	
	public function display_name()
	{
		return $this->display_name;
	}
	
	public function newsroom()
	{
		return $this->newsroom;
	}
	
	public function stages()
	{
		return $this->stages;
	}
	
	public function is_done()
	{
		return $this->is_done;
	}
	
	public static function done($bar_name, $stage_name, $newsroom = null)
	{	
		$ci =& get_instance();
		if (!$newsroom) $newsroom = $ci->newsroom;
		$company_id = $newsroom->company_id;
		
		$sql = "INSERT IGNORE INTO nr_bar_record 
			(bar_stage_id, company_id) 
			SELECT stage.id, {$company_id} as company_id FROM
			nr_bar bar INNER JOIN nr_bar_stage stage 
			ON bar.id = stage.bar_id WHERE 
			bar.name = ? AND stage.name = ?";
		
		$data = array($bar_name, $stage_name);
		$ci->db->query($sql, $data);
	}
	
}

?>