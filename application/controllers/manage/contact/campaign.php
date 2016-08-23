<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Campaign_Controller extends Manage_Base {
	
	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iContact';
		$this->vd->title[] = 'Email Campaigns';
	}

	public function index()
	{
		$this->redirect('manage/contact/campaign/all');
	}
	
	public function all($chunk = 1)
	{
		$this->listing($chunk, 'all');
	}
	
	public function sent($chunk = 1)
	{
		$filter = 'ca.is_sent = 1';
		$this->listing($chunk, 'sent', $filter);
	}
	
	public function scheduled($chunk = 1)
	{
		$filter = 'ca.is_draft = 0 AND ca.is_sent = 0';
		$this->listing($chunk, 'scheduled', $filter);
	}
	
	public function draft($chunk = 1)
	{
		$filter = 'ca.is_sent = 0 AND ca.is_draft = 1';
		$this->listing($chunk, 'draft', $filter);
	}
	
	protected function listing($chunk, $status, $filter = 1)
	{
		$terms_sql = 1;
		$company_id = $this->newsroom->company_id;
		$terms = $this->input->get('terms');
		$terms_sql = sql_search_terms(array('ca.name', 
			'ca.subject', 'co.title'), $terms);
				
		$this->load->view('manage/header');
		$this->load->view("manage/contact/menu");
		$this->load->view('manage/pre-content');
		
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		
		$order = ($terms ? "ca.name ASC" : "ca.id DESC");		
		$sql = "SELECT SQL_CALC_FOUND_ROWS ca.*,
			co.type AS content_type
			FROM nr_campaign ca 
			LEFT JOIN nr_content co ON ca.content_id = co.id
			WHERE ca.company_id = ? AND {$filter} AND {$terms_sql}
			ORDER BY {$order} {$limit_str}";
		
		$query = $this->db->query($sql, 
			array($company_id));
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format = gstring("manage/contact/campaign/{$status}/-chunk-");
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;		
		$this->vd->results = $results;
		
		$this->load->view('manage/contact/campaign');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_from($content_id)
	{
		$company_id = $this->newsroom->company_id;
		$content = Model_Content::find($content_id);
		$content->load_local_data();
		if ($content && $content->company_id == $company_id)
			$this->vd->from_m_content = $content;	
		
		// free user can only do premium pr				
		if (Auth::user()->is_free_user() && 
		    (Model_Campaign_Content_Consumed::find($content->id) ||
		     !$content->is_premium))
			$this->vd->from_m_content = null;				
		
		// set default content based
		// on the m_content we make from
		if ($this->vd->from_m_content)
			$this->vd->default_content = $this->load->view(
				'manage/contact/partials/campaign_default_content', 
				array('m_content' => $this->vd->from_m_content), true);
		
		$this->edit();
	}
	
	public function edit($campaign_id = null)
	{
		if ($campaign_id)
			  $this->vd->title[] = 'Edit Campaign';
		else $this->vd->title[] = 'New Campaign';
		
		$campaign = Model_Campaign::find($campaign_id);
		$company_id = $this->newsroom->company_id;
		$this->vd->campaign = $campaign;
		
		if ($campaign && $campaign->company_id != $company_id)
			$this->denied();
		
		$criteria = array('company_id', $company_id);
		if (!Model_Contact::find_all($criteria, null, 1))
		{
			// load profile warning message
			$feedback_view = 'manage/contact/partials/no_contacts_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
		}
		
		$vd = array();
		$vd['lists_all'] = true;
		$vd['markers'] = Model_Campaign::markers();
		$vd['lists'] = Model_Contact_List::find_all(
			array('company_id', $company_id), 
			array('name', 'asc'));
		
		$this->vd->related_lists = $campaign ? 
			$campaign->get_lists() : array();
			
		$this->vd->company_profile =
			Model_Company_Profile::find($company_id);
			
		if ($campaign)
		{
			$dt_date_send = Date::out($campaign->date_send, $this->local_tz());
			if ($dt_date_send->format('H:i') === '00:00')
			     $campaign->date_send_str = $dt_date_send->format('Y-m-d');
			else $campaign->date_send_str = $dt_date_send->format('Y-m-d H:i');
			$campaign->m_content = Model_Content::find($campaign->content_id);
			$campaign->load_content_data();
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/contact/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/contact/campaign-edit', $vd);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_save()
	{
		$post = $this->input->post();
		$company_id = $this->newsroom->company_id;
		$campaign_id = value_or_null($post['campaign_id']);
		
		foreach ($post as &$data)
			$data = value_or_null($data);
		
		$content = Model_Content::find(@$post['content_id']);
		if ($content && $content->company_id != $company_id)
			$post['content_id'] = null;
		
		// free user can only campaign for premium PR
		if (Auth::user()->is_free_user() && $content && 
		    ($content->type != Model_Content::TYPE_PR ||
		     !$content->is_published || 
		     !$content->is_premium))
			$post['content_id'] = null;
		
		if (!isset($post['is_draft']))
			$post['is_draft'] = 0;
		
		// the user is a free user => require content
		if (Auth::user()->is_free_user() && !$post['content_id'])
			$post['is_draft'] = 1;
		
		if (isset($post['resend']))
		{
			$post['contact_count'] = null;
			$post['is_draft'] = 0;
			$post['is_sent'] = 0;
		}
		
		$campaign = Model_Campaign::find($campaign_id);
		if ($campaign && $campaign->company_id != $company_id)
			$this->denied();
		
		if (!$campaign) $campaign = new Model_Campaign();
		$campaign->values($post);
		
		if (!$campaign->name)
			$campaign->name = substr(md5(time()), 0, 16);
		
		// set the time to 00:00:00 on the date specified unless time provided	
		$dt_date_send = new DateTime(@$post['date_send'], $this->local_tz());
		if (!preg_match('#\d:\d\d#', $post['date_send']))
			$dt_date_send->setTime(0, 0, 0);
		$dt_date_send->setTimezone(Date::$utc);
		$campaign->date_send = $dt_date_send->format(Date::FORMAT_MYSQL); 
		
		// if the send datetime is before the publish datetime
		if ($content && $dt_date_send < new DateTime($content->date_publish))
		{
			// set them to be at the same time
			$campaign->date_send = $content->date_publish;
			
			// convert to local timezone again and set to 00:00:00
			// so that we can check if its actually the same day
			$new_dt_date_send = Date::out($campaign->date_send, $this->local_tz());
			$new_dt_date_send->setTime(0, 0, 0);
			$new_dt_date_send->setTimezone(Date::$utc);
			
			// if true => not the same day
			if ($new_dt_date_send > $dt_date_send)
			{	
				// load feedback message for the user
				$feedback_view = 'manage/contact/partials/campaign_date_warning_feedback';
				$feedback = $this->load->view($feedback_view, null, true);
				$this->add_feedback($feedback);
			}
		}
		else if ($content && !$content->is_published)
		{
			// load feedback message for the user
			$feedback_view = 'manage/contact/partials/campaign_not_published_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
		}
		
		$campaign->company_id = $company_id;
		$campaign->save();
		
		$lists = array();				
		foreach ((array) @$post['lists'] as $contact_list_id)
		{
			if (!$contact_list_id) continue;
			if (!($list = Model_Contact_List::find($contact_list_id))) continue;
			if ($list->company_id != $company_id) continue;
			$lists[] = $list;
		}
		
		$campaign->set_lists($lists);		
		
		$data = Model_Campaign_Data::find($campaign->id);
		if (!$data) $data = new Model_Campaign_Data();
		$data->content = value_or_null($this->vd->pure($post['content']));
		$data->campaign_id = $campaign->id;
		$data->save();
				
		if (isset($post['test']))
		{
			$contact = new stdClass();
			$contact->email = @$post['test_email'];
			$contact->first_name = @$post['test_first_name'];
			$contact->last_name = @$post['test_last_name'];
			
			$campaign->is_draft = 1;
			$campaign->save();
			
			if ($campaign->send_test($contact))
			{
				// load feedback message for the user
				$feedback_view = 'manage/contact/partials/campaign_test_feedback';
				$feedback = $this->load->view($feedback_view, null, true);
				$this->add_feedback($feedback);
			}
			
			// redirect back to the campaign edit 
			$redirect_url = "manage/contact/campaign/edit/{$campaign->id}";	
			$this->redirect($redirect_url);
		}
		
		// load feedback message for the user
		$feedback_view = 'manage/contact/partials/campaign_save_feedback';
		$feedback = $this->load->view($feedback_view, array('campaign' => $campaign), true);
		$this->add_feedback($feedback);
		
		$credits_required = $campaign->credits_required();
		$credits_available = Auth::user()->email_credits();
		if ($credits_available < $credits_required)
		{
			// load feedback message for the user
			$feedback_view = 'manage/contact/partials/save_low_credits_warning_feedback';
			$feedback = $this->load->view($feedback_view, 
				array('credits_required' => $credits_required), true);
			$this->add_feedback($feedback);
		}
		
		$bundled_email_credits = (int) Model_Setting::value('bundled_email_credits');
		if (Auth::user()->is_free_user() && $bundled_email_credits < $credits_required)
		{
			// load feedback message for the user
			$view_data = array('credits_required' => $credits_required,
				'bundled_email_credits' => $bundled_email_credits);
			$feedback_view = 'manage/contact/partials/save_bundled_credits_warning_feedback';
			$feedback = $this->load->view($feedback_view, $view_data, true);
			$this->add_feedback($feedback);
		}
		
		// redirect back to the campaign list
		$redirect_url = 'manage/contact/campaign/all';
		$this->redirect($redirect_url);
	}
	
	public function delete($campaign_id)
	{
		if (!$campaign_id) return;
		$campaign = Model_Campaign::find($campaign_id);
		$company_id = $this->newsroom->company_id;
		
		if ($campaign && $campaign->company_id != $company_id)
			$this->denied();
		
		if ($this->input->post('confirm'))
		{
			$campaign->delete();
			
			// load feedback message 
			$feedback_view = 'manage/contact/partials/campaign_delete_after_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
			
			// redirect back to type specific listing
			$redirect_url = 'manage/contact/campaign/';
			$this->set_redirect($redirect_url);
		}
		else
		{
			// load confirmation feedback 
			$this->vd->campaign_id = $campaign_id;
			$feedback_view = 'manage/contact/partials/campaign_delete_before_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			$this->edit($campaign_id);
		}
	}
	
	public function related_content()
	{
		$company_id = $this->newsroom->company_id;
		$limit = (int) $this->input->post('limit');
		$offset = (int) $this->input->post('offset');
		
		$filter_premium = null;
		$filter_types = array(Model_Content::TYPE_PR, 
			Model_Content::TYPE_NEWS, Model_Content::TYPE_EVENT);
		
		if (!$this->newsroom->is_active)
			$filter_types = array(Model_Content::TYPE_PR);
		if (Auth::user()->is_free_user())
			$filter_premium = 'AND c.is_premium = 1';
		
		foreach ($filter_types as &$type)
			$type = sprintf('\'%s\'', $type);
		$filter_types = implode(',', $filter_types);
		
		$sql = "SELECT c.id, c.title, c.type
			FROM nr_content c 
			LEFT JOIN (
				SELECT t.content_id, GROUP_CONCAT(t.value) AS tags
				FROM nr_content_tag t GROUP BY t.content_id
			) t ON t.content_id = c.id
			LEFT JOIN nr_campaign_content_consumed ccc
			ON c.id = ccc.content_id
			WHERE c.company_id = ? 
			{$filter_premium}
			AND ccc.content_id IS NULL
			AND c.type IN ({$filter_types}) 
			ORDER BY c.id DESC LIMIT {$offset}, {$limit}";
				
		$query = $this->db->query($sql, array($company_id));
		$m_results = Model_Content::from_db_all($query);
		$results = array();
		
		foreach ($m_results as $m_content)
		{
			$m_content->load_content_data();
			$result = new stdClass();
			$result->id = $m_content->id;
			$result->type = Model_Content::short_type($m_content->type);
			$result->title = $this->vd->esc($this->vd->cut($m_content->title, 60));
			$result->subject = $m_content->title;
			$result->content = $this->load->view(
				'manage/contact/partials/campaign_default_content', 
				array('m_content' => $m_content), true);			
			$results[] = $result;
		}
		
		$response = new stdClass();
		$response->data = $results;
		$this->json($response);
	}

}

?>