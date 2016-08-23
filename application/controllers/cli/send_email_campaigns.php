<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Send_Email_Campaigns_Controller extends CLI_Base {
	
	public function index()
	{
		$bundled_email_credits = (int) Model_Setting::value('bundled_email_credits');
		
		while (true)
		{
			set_time_limit(300);
			$sql = "SELECT ca.* FROM nr_campaign ca 
				LEFT JOIN nr_content co ON ca.content_id = co.id
				WHERE ca.is_sent = 0 AND ca.is_draft = 0 
				AND ca.date_send <= UTC_TIMESTAMP() 
				AND ca.is_send_active = 0
				AND (co.is_published IS NULL OR co.is_published = 1)
				ORDER BY ca.id DESC LIMIT 1";
			
			$result = $this->db->query($sql);
			if (!$result->num_rows()) break;
			
			$campaign = Model_Campaign::from_db($result);
			if (!$campaign) break;
			
			$sql = "UPDATE nr_campaign 
				SET is_send_active = 1 
				WHERE id = ?";
				
			$this->db->query($sql, array($campaign->id));			
			$credits_required = $campaign->credits_required();
			$user = Model_User::find_company_id($campaign->company_id);
			$credits_available = $user->email_credits();	
			
			if ($credits_available < $credits_required)
			{
				$en = new Email_Notification();
				$en->set_content_view('not_enough_email_credits');
				$en->set_data('m_campaign', $campaign);
				$en->set_data('credits_required', $credits_required);
				$en->set_data('credits_available', $credits_available);
				$en->send($user);
				
				$this->remove_from_scheduled($campaign);
			}
			else if ($campaign->content_id && 
				($content = Model_Content::find($campaign->content_id)) && 
				($newsroom = Model_Newsroom::find($campaign->company_id)) && 
			   !$newsroom->is_active && $content->type != Model_Content::TYPE_PR)
			{
				$en = new Email_Notification();
				$en->set_content_view('email_content_is_inactive');
				$en->set_data('m_campaign', $campaign);
				$en->send($user);
				
				$this->remove_from_scheduled($campaign);
			}
			else if ($user->is_free_user() && Model_Campaign_Content_Consumed::find($campaign->content_id))
			{
				$en = new Email_Notification();
				$en->set_content_view('campaign_content_consumed');
				$en->set_data('m_campaign', $campaign);
				$en->send($user);
				
				$this->remove_from_scheduled($campaign);
			}
			else if ($user->is_free_user() && $credits_required > $bundled_email_credits)
			{
				$en = new Email_Notification();
				$en->set_content_view('bundled_email_credits');
				$en->set_data('m_campaign', $campaign);
				$en->set_data('credits_required', $credits_required);
				$en->set_data('bundled_email_credits', $bundled_email_credits);
				$en->send($user);
				
				$this->remove_from_scheduled($campaign);
			}
			else
			{
				$campaign->send_all();
				$sql = "UPDATE nr_campaign 
					SET is_sent = 1, is_send_active = 0
					WHERE id = ?";
							
				$this->db->query($sql, array($campaign->id));
				$user->consume_email_credits($credits_required);
				
				// record credit usage for the company
				Model_Company_Email_Count::create($campaign->company_id)
					->record($credits_required);
				
				if ($user->is_free_user() && $campaign->content_id)
				{
					// record the content consumed to prevent re-use
					$sql = "INSERT INTO nr_campaign_content_consumed
						(content_id, campaign_id) VALUES (?, ?)";
					$this->db->query($sql, array($campaign->content_id, $campaign->id));
				}
			}
		}
	}
	
	protected function remove_from_scheduled($campaign)
	{
		$sql = "UPDATE nr_campaign 
			SET is_sent = 0, is_draft = 1,
			is_send_active = 0 
			WHERE id = ?";
		
		$this->db->query($sql, array($campaign->id));
	}
	
}

?>