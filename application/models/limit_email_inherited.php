<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Limit_Email_Inherited extends Model_Limit_Base {
	
	protected static $__table   = 'nr_limit_email_inherited';
	protected static $__primary = 'limit_id';
	
	protected $m_limit_pr_premium = NR_DEFAULT;
	
	protected static function __create($limit_id)
	{
		$_this = new static();
		$_this->limit_id = $limit_id;
		$_this->amount_used = 0;
		$_this->save();
		return $_this;
	}
	
	public static function find($limit_pr)
	{
		// no PR limit available => no email limit
		if (!$limit_pr || $limit_pr === NR_DEFAULT) 
			return false;		
		
		if ($limit_pr instanceof Model_Limit_PR)
		{
			$_this = parent::find($limit_pr->limit_id);
			if (!$_this) $_this = static::__create($limit_pr->limit_id);
			$_this->m_limit_pr_premium = $limit_pr;
			return $_this;
		}
		
		$_this = parent::find($limit_pr);
		if (!$_this) $_this = static::__create($limit_pr);
				
		$criteria = array();
		$criteria[] = array('limit_id', $_this->limit_id);
		$criteria[] = array('type', Model_Content::PREMIUM);
		$_this->m_limit_pr_premium = Model_Limit_PR::find($criteria);
		return $_this;
	}
	
	public function consume($count)
	{
		$consumed = min($count, $this->available());
		$this->amount_used += $consumed;
		$this->save();
		return $consumed;
	}
	
	public function available()
	{
		return max(0, ($this->total() - $this->used()));
	}
	
	public function used()
	{
		return $this->amount_used;
	}
	
	public function total()
	{
		$package = $this->m_limit_pr_premium->package;		
		if ($package == Model_User::PACKAGE_SILVER)
		     $extra_name = 'extra_email_credits_silver';
		else if ($package == Model_User::PACKAGE_GOLD)
		     $extra_name = 'extra_email_credits_gold';
		else if ($package == Model_User::PACKAGE_PLATINUM)
		     $extra_name = 'extra_email_credits_platinum';
		else $extra_name = null;
		
		return ((int) Model_Setting::value($extra_name)) + 
			($this->m_limit_pr_premium->amount_total *
				Model_Setting::value('bundled_email_credits'));
	}
	
}

?>