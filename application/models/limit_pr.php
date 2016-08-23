<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Limit_PR extends Model_Limit_Base {
	
	const DURATION_MONTHLY  = 'MONTHLY';
	const DURATION_WEEKLY   = 'WEEKLY';
	const DURATION_DAILY    = 'DAILY';
	
	protected static $__table   = 'nr_limit_pr';
	protected static $__primary = array('limit_id', 'type');
	
	protected $calculated_used = NR_DEFAULT;
	
	public static function find_premium($user)
	{
		if ($user instanceof Model_User)
			$user = $user->id;
		
		$criteria = array();
		$criteria[] = array('user_id', $user);
		$criteria[] = array('type', Model_Content::PREMIUM);		
		return static::find($criteria);
	}
	
	public static function find_basic($user)
	{
		if ($user instanceof Model_User)
			$user = $user->id;
		
		$criteria = array();
		$criteria[] = array('user_id', $user);
		$criteria[] = array('type', Model_Content::BASIC);
		return static::find($criteria);
	}
	
	protected function calculate_used()
	{
		if ($this->calculated_used === NR_DEFAULT)
		{
			if ($this->duration == static::DURATION_WEEKLY)
				  $dt_cut = Date::days(-7);
			else if ($this->duration == static::DURATION_DAILY)
				  $dt_cut = Date::days(-1);
				
			// safe fall back for bad data
			else return $this->amount_total;
			
			$is_premium = (int) ($this->type == Model_Content::PREMIUM);
			$dt_cut_str = $dt_cut->format(Date::FORMAT_MYSQL);	
			
			$sql = "SELECT 1 FROM nr_company cm
				INNER JOIN nr_content ct ON 
				cm.user_id = {$this->user_id} AND
				cm.id = ct.company_id AND
				ct.type = 'pr' AND
				ct.is_premium = {$is_premium} AND
				(ct.is_published = 1 OR 
				 ct.is_under_review = 1 OR 
				 ct.is_approved = 1) AND
				ct.date_publish > '{$dt_cut_str}'";
				
			$dbr = $this->db->query($sql);
			$used = $dbr->num_rows();			
			$this->calculated_used = $used;
		}
		
		return max(0, $this->calculated_used);
	}
	
	public function consume($content)
	{
		$this->calculated_used = NR_DEFAULT;
		if ($this->uses_calculated) return;
		
		if ($this->available())
			$this->amount_used++;
		$this->save();
		
		if ($content instanceof Model_Content)
			$content = $content->id;		
		$consumed = new Model_Limit_PR_Consumed();
		$consumed->from = Model_Limit_PR_Consumed::FROM_ROLLOVER;
		$consumed->limit_id = $this->limit_id;
		$consumed->content_id = $content;
		$consumed->type = $this->type;
		$consumed->save();
	}
		
	public function restore()
	{
		$this->calculated_used = NR_DEFAULT;
		if ($this->uses_calculated) return;
		if ($this->amount_used > 0)
			$this->amount_used--;
		$this->save();
	}
	
	public function days()
	{
		// ** be aware that monthly could actually be any 
		// period because of the nature of the legacy system
		if ($this->duration == static::DURATION_MONTHLY) return 30;
		if ($this->duration == static::DURATION_WEEKLY) return 7;
		if ($this->duration == static::DURATION_DAILY) return 1;
		return 0;
	}
	
	public function available()
	{
		return max(0, ($this->total() - $this->used()));
	}
	
	public function used()
	{
		if ($this->uses_calculated)
			return $this->calculate_used();
		return $this->amount_used;
	}
	
	public function total()
	{
		return $this->amount_total;
	}
	
}

?>