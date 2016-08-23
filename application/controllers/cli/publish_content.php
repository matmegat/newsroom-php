<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Publish_Content_Controller extends CLI_Base {
	
	public function index()
	{
		set_time_limit(300);
		
		$approved_types = array(
			Model_Content::TYPE_PR,
		);
		
		foreach ($approved_types as $k => $type)
			$approved_types[$k] = $this->db->escape($type);
		$approved_types_str = implode(',', $approved_types);
		
		// publish content that is approved
		// or does not require approval
		$sql = "UPDATE nr_content c SET 
			c.is_published = 1, 
			c.is_under_review = 0,
			c.date_publish = UTC_TIMESTAMP()
			WHERE	c.is_published = 0 AND
			c.is_draft = 0 AND
			(c.type NOT IN ({$approved_types_str})
			 OR c.is_approved = 1) AND
			c.date_publish < UTC_TIMESTAMP()";
			
		$this->db->query($sql);
		
		$review_period = Model_Setting::value('review_period');
		$review_dt_cut = Date::days(1)->format(Date::FORMAT_MYSQL);
		
		while (true)
		{
			// set content up for review or return 
			// the content to draft state if no credits
			
			$sql = "SELECT * FROM nr_content c WHERE
				c.is_published = 0 AND
				c.is_draft = 0 AND
				c.is_under_review = 0 AND
				c.is_approved = 0 AND
				c.type IN ({$approved_types_str}) AND
				c.date_publish < '{$review_dt_cut}'
				LIMIT 1";
				
			$dbr = $this->db->query($sql);
			$m_content = Model_Content::from_db($dbr);
			if (!$m_content) break;
			
			$m_newsroom = Model_Newsroom::find($m_content->company_id);
			if ($m_newsroom->is_archived)
			{
				$m_content->is_draft = 1;
				$m_content->save();
				continue;
			}
			
			$m_user = Model_User::find_company_id($m_content->company_id);
			$amount_available = $m_content->is_premium ?
				$m_user->pr_credits_premium() :
				$m_user->pr_credits_basic();
				
			if ($amount_available > 0)
			{
				if ($m_content->is_premium)
					  $m_user->consume_pr_credit_premium($m_content);
				else $m_user->consume_pr_credit_basic($m_content);
				
				$sch_n = new Model_Scheduled_Notification();
				$sch_n->related_id = $m_content->id;
				$sch_n->class = Model_Scheduled_Notification::CLASS_CONTENT_UNDER_REVIEW;
				$sch_n->user_id = $m_user->id;
				$sch_n->save();
				
				$m_content->is_approved = 0;
				$m_content->is_rejected = 0;
				$m_content->is_under_review = 1;
				$m_content->save();
			}
			else
			{
				$en = new Email_Notification();
				$en->set_content_view('not_enough_pr_credits');
				$en->set_data('m_content', $m_content);
				$en->send($m_user);
				
				// schedule the press release to try again in 24 hours
				$m_content->date_publish = Date::hours(24)->format(Date::FORMAT_MYSQL);
				$m_content->save();
			}
		}
	}
	
}

?>