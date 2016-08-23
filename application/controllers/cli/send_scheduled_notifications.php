<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Send_Scheduled_Notifications extends CLI_Base {
	
	public function index()
	{
		while (true)
		{
			set_time_limit(60);
			$sql = "SELECT * FROM nr_scheduled_notification sn 
				ORDER BY sn.id ASC LIMIT 1";
			$result = $this->db->query($sql);
			if (!$result->num_rows()) break;
			
			$sch_n = Model_Scheduled_Notification::from_db($result);
			if (!$sch_n) break;
			$sch_n->delete();
			
			$m_user = Model_User::find($sch_n->user_id);
			
			if ($sch_n->class == Model_Scheduled_Notification::CLASS_CONTENT_SCHEDULED)
			{
				if (!($m_content = Model_Content::find($sch_n->related_id))) continue;				
				$m_newsroom = Model_Newsroom::find($m_content->company_id);
				
				$en = new Email_Notification();
				$en->set_content_view('content_scheduled');
				$en->set_data('content', $m_content);
				$en->set_data('timezone', $m_newsroom->timezone);
				$en->send($m_user);
				continue;	
			}
			
			if ($sch_n->class == Model_Scheduled_Notification::CLASS_CONTENT_UNDER_REVIEW)
			{
				if (!($m_content = Model_Content::find($sch_n->related_id))) continue;					
				$m_newsroom = Model_Newsroom::find($m_content->company_id);
				
				$en = new Email_Notification();
				$en->set_content_view('content_under_review');
				$en->set_data('content', $m_content);
				$en->set_data('timezone', $m_newsroom->timezone);
				$en->send($m_user);
				continue;	
			}
			
			if ($sch_n->class == Model_Scheduled_Notification::CLASS_CONTENT_APPROVED)
			{
				if (!($m_content = Model_Content::find($sch_n->related_id))) continue;		
				
				$en = new Email_Notification();
				$en->set_content_view('content_approved');
				$en->set_data('content', $m_content);
				$en->send($m_user);
				continue;	
			}
			
			if ($sch_n->class == Model_Scheduled_Notification::CLASS_CONTENT_REJECTED)
			{
				if (!($m_content = Model_Content::find($sch_n->related_id))) continue;		
				
				$en = new Email_Notification();
				$en->set_content_view('content_rejected');
				$en->set_data('content', $m_content);
				$en->set_data('feedback', unserialize($sch_n->data));
				$en->send($m_user);
				continue;	
			}
		}
	}
	
}

?>