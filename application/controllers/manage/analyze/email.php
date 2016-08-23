<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Email_Controller extends Manage_Base {
	
	protected $show_all_contacts = false;
	
	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iAnalyze';
		$this->vd->title[] = 'Email Stats';
	}
	
	public function index($chunk = 1)
	{
		$terms_sql = 1;
		$company_id = $this->newsroom->company_id;
		$terms = $this->input->get('terms');
		$terms_sql = sql_search_terms(array('ca.name', 
			'ca.subject', 'co.title'), $terms);
				
		$this->load->view('manage/header');
		$this->load->view('manage/analyze/menu');
		$this->load->view('manage/pre-content');
		
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		
		$order = ($terms ? "ca.name ASC" : "ca.id DESC");		
		$sql = "SELECT SQL_CALC_FOUND_ROWS ca.*,
			co.type AS content_type
			FROM nr_campaign ca 
			LEFT JOIN nr_content co ON ca.content_id = co.id
			WHERE ca.company_id = ? AND ca.is_sent = 1
			AND {$terms_sql} ORDER BY {$order} {$limit_str}";
		
		$query = $this->db->query($sql, 
			array($company_id));
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format = gstring("manage/analyze/email/-chunk-");
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$stats = new Statistics();
		$stats->set_newsroom($this->newsroom->name);
		$opens = $stats->visits_for_campaign_set($results);
		$this->vd->opens = $opens;
		
		$this->load->view('manage/analyze/email');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function view($campaign_id, $chunk = 1)
	{
		$company_id = $this->newsroom->company_id;
		$campaign = Model_Campaign::find($campaign_id);
		$this->vd->campaign = $campaign;
		if (!$campaign) return;
		
		$this->title = $campaign->name;		
		if ($campaign->company_id != $company_id)
			$this->denied();
		
		$stats = new Statistics();
		$stats->set_newsroom($this->newsroom->name);
		$stats->set_campaign($campaign_id);
		
		$this->vd->clicks = $stats->visits_email_click_pixel();
		$this->vd->views = $stats->visits_email_view_pixel();
		
		$campaign->load_content_data();
		$data_contacts = @unserialize($campaign->contacts);		
		if ($data_contacts === false)
			$data_contacts = array();
		
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		
		foreach ($data_contacts as &$contact_id)
			$contact_id = (int) $contact_id;
		$data_contacts_str = implode(',', $data_contacts);
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
			c.id, c.first_name, c.last_name, 
			c.email, c.company_name
			FROM nr_contact c WHERE c.id 
			IN ({$data_contacts_str})
			ORDER BY c.first_name ASC, 
			c.last_name ASC
			{$limit_str}";	
			
		$results = array();
		$query = $this->db->query($sql);		
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
			
		$this->check_pixels($stats, $results);
				
		$url_format = gstring("manage/analyze/email/view/{$campaign_id}/-chunk-");
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('manage/header');
		$this->load->view('manage/analyze/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/analyze/email-view');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function report($id)
	{
		$generate_url = "manage/analyze/email/report_generate/{$id}";
		$generate_url = gstring($generate_url);
		$this->vd->generate_url = $generate_url;
		
		$return_url = "manage/analyze/email/view/{$id}";
		$return_url = gstring($return_url);
		$this->vd->return_url = $return_url;
		
		$this->load->view('manage/header');
		$this->load->view('manage/analyze/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/analyze/report-generate');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function report_generate($id)
	{
		$url = "manage/analyze/email/report_index/{$id}";
		$url = $this->newsroom->url($url);
		$report = new Report($url);
		$report->generate();
		
		if ($this->input->post('indirect'))
			  $report->indirect();
		else $report->deliver();
	}
	
	public function report_index($campaign_id)
	{
		$company_id = $this->newsroom->company_id;
		$campaign = Model_Campaign::find($campaign_id);
		$this->vd->campaign = $campaign;
		if (!$campaign) return;
		
		if ($campaign->company_id != $company_id)
			$this->denied();
		
		$stats = new Statistics();
		$stats->set_newsroom($this->newsroom->name);
		$stats->set_campaign($campaign_id);
		
		$this->vd->clicks = $stats->visits_email_click_pixel();
		$this->vd->views = $stats->visits_email_view_pixel();
		
		$campaign->load_content_data();
		$data_contacts = @unserialize($campaign->contacts);		
		if ($data_contacts === false)
			$data_contacts = array();
		
		foreach ($data_contacts as &$contact_id)
			$contact_id = (int) $contact_id;
		$data_contacts_str = implode(',', $data_contacts);
		
		$sql = "SELECT  
			c.id, c.first_name, c.last_name, 
			c.email, c.company_name
			FROM nr_contact c WHERE c.id 
			IN ({$data_contacts_str})
			ORDER BY c.first_name ASC, 
			c.last_name ASC";	
			
		$results = array();
		$query = $this->db->query($sql);
		$total_results = $query->num_rows();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$this->check_pixels($stats, $results);
		$this->vd->results = $results;
		
		$this->load->view('manage/header');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/analyze/report/email');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	protected function check_pixels($stats, &$results)
	{
		$email_addresses = array();		
		foreach ($results as $result)
			$email_addresses[] = $result->email;
		
		$view_tracker_url = $stats->email_view_tracker_url();
		$click_tracker_url = $stats->email_click_tracker_url();
		
		$viewed = $stats->check_email_pixel_for_addresses(
			$view_tracker_url, $email_addresses);
		$clicked = $stats->check_email_pixel_for_addresses(
			$click_tracker_url, $email_addresses);
		
		foreach ($results as $k => $result)
		{
			$result->viewed = $viewed[$k];
			$result->clicked = $clicked[$k];
		}
	}
	
}

?>