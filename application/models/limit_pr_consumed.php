<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Limit_PR_Consumed extends Model {
	
	protected static $__table = 'nr_limit_pr_consumed';
	protected static $__primary = 'content_id';
	
	const FROM_ROLLOVER  = 'ROLLOVER';
	const FROM_HELD      = 'HELD';
	
	public function restore($user = null)
	{
		if (!$user) $user = Model_Content::find($this->content_id)->owner();
		if (!$user) return;
		
		if ($this->from === static::FROM_ROLLOVER)
		{
			$limit_criteria = array();
			$limit_criteria[] = array('limit_id', $this->limit_id);
			$limit_criteria[] = array('type', $this->type);			
			if ($limit = Model_Limit_PR::find($limit_criteria))
				$limit->restore();
		}
		else // static::FROM_HELD
		{
			// will find the best limit to restore back to
			if ($held = Model_Limit_PR_Held::find($this->limit_id));
				$held->restore();
		}
		
		$this->delete();
	}
	
}

?>