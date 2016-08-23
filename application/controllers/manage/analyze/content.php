<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/listing');
load_controller('manage/analyze/stats');

class Content_Controller extends Listing_Base {

	protected $listing_section = 'analyze';
	protected $listing_sub_section = 'content';
	protected $listing_chunk_size = 5;

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iAnalyze';
		$this->vd->title[] = 'Content Stats';
		$this->listing_type = $this->uri->segment(4);		
	}
	
	protected function process_results($results)
	{
		if (!count($results))
			return $results;
		
		$stats = new Statistics();
		$stats->hits_for_content_set($results);
		return $results;
	}

	public function index($type = null, $status = null, $chunk = 1)
	{
		if ($type === null) $type = Model_Content::TYPE_PR;
		if ($status !== 'published')
			$this->redirect("manage/analyze/content/{$type}/published");
		
		if (!Model_Content::is_allowed_type($type))
			show_404();
		
		$this->vd->is_search = false;
		$this->listing($chunk, $status, $type);
	}
	
	public function view($id)
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_content = Model_Content::find($id);
		$this->vd->m_content = $m_content;
		
		if (!$m_content) show_404();
		if ((int) $m_content->company_id !== $company_id)
			$this->denied();
		
		$this->title = $m_content->title;		
		if ($m_content->is_premium && $m_content->is_published)
		{
			$this->vd->dist_count = Model_Fin_Distribution_Service::count(array(
				array('content_id', $m_content->id),
				array('date_discovered <= UTC_TIMESTAMP()')));			
				
			$dist_modal = new Modal();
			$dist_modal->set_title('Content Distribution');
			$this->add_eob($dist_modal->render(400, 400));
			$this->vd->dist_modal_id = $dist_modal->id;
		}
		
		$_base = new Stats_Base($this);
		$_base->set_content($m_content->id);
		$_base->index('manage/analyze/content/view');
	}

	public function twitter_shares($id) 
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_content = Model_Content::find($id);
		$this->vd->m_content = $m_content;
		
		if (!$m_content) show_404();
		if ((int) $m_content->company_id !== $company_id)
			$this->denied();

		$m_content->load_content_data();
		$share_url = $this->website_url($m_content->url());
		$this->vd->twitter_shares = Social_Twitter_Shares::get($share_url);		
		
		$this->load->view('manage/analyze/partials/twitter-shares');
	}

	public function facebook_shares($id)
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_content = Model_Content::find($id);
		$this->vd->m_content = $m_content;
		
		if (!$m_content) show_404();
		if ((int) $m_content->company_id !== $company_id)
			$this->denied();
	
		$m_content->load_content_data();
		$share_url = $this->website_url($m_content->url());
		$this->vd->facebook_shares = Social_Facebook_Shares::get($share_url);		
		$this->load->view('manage/analyze/partials/facebook-shares');
	}
	
	public function google_results($id)
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_content = Model_Content::find($id);
		$this->vd->m_content = $m_content;
		
		if (!$m_content) show_404();
		if ((int) $m_content->company_id !== $company_id)
			$this->denied();
			
		$this->load->view('manage/analyze/partials/google-results');
	}
	
	public function chart($id)
	{		
		$m_content = Model_Content::find($id);
		$company_id = (int) $this->newsroom->company_id;
		if (!$m_content || (int) $m_content->company_id !== $company_id)
			return;
		
		$_base = new Stats_Base($this);
		$_base->set_content($m_content->id);
		$_base->chart();
	}
	
	public function geolocation($id) 
	{		
		$m_content = Model_Content::find($id);
		$company_id = (int) $this->newsroom->company_id;
		if (!$m_content || (int) $m_content->company_id !== $company_id)
			return;
		
		$_base = new Stats_Base($this);
		$_base->set_content($m_content->id);
		$_base->geolocation();
	}
	
	public function report($id)
	{
		$generate_url = "manage/analyze/content/report_generate/{$id}";
		$generate_url = gstring($generate_url);
		$this->vd->generate_url = $generate_url;
		
		$return_url = "manage/analyze/content/view/{$id}";
		$return_url = gstring($return_url);
		$this->vd->return_url = $return_url;
		
		$this->load->view('manage/header');
		$this->load->view('manage/analyze/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/analyze/report-generate');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function dist($id)
	{
		$generate_url = "manage/analyze/content/dist_generate/{$id}";
		$generate_url = gstring($generate_url);
		$this->vd->generate_url = $generate_url;
		
		$return_url = "manage/analyze/content/view/{$id}";
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
		$url = "manage/analyze/content/report_index/{$id}";
		$url = $this->newsroom->url($url);
		$url = gstring($url);
		$report = new Report($url);
		$report->generate();
		
		if ($this->input->post('indirect'))
			  $report->indirect();
		else $report->deliver();
	}
	
	public function dist_generate($id)
	{
		$url = "manage/analyze/content/dist_index/{$id}";
		$url = $this->newsroom->url($url);
		$url = gstring($url);
		$report = new Report($url);
		$report->generate();
		
		if ($this->input->post('indirect'))
			  $report->indirect();
		else $report->deliver();
	}
	
	public function report_index($id)
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_content = Model_Content::find($id);
		$this->vd->m_content = $m_content;
		
		if (!$m_content) show_404();
		if ((int) $m_content->company_id !== $company_id)
			$this->denied();
		
		if ($m_content->is_premium && $m_content->is_published)
		{
			$this->vd->dist_count = Model_Fin_Distribution_Service::
				count(array('content_id', $m_content->id));
			$this->vd->google_results_count = Google_Search_Result_Count::
				count($m_content->title);
		}
		
		$_base = new Stats_Base($this);
		$_base->set_content($m_content->id);
		$_base->index('manage/analyze/report/content');
	}
	
	public function dist_index($id)
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_content = Model_Content::find($id);
		$this->vd->m_content = $m_content;
		
		if (!$m_content) show_404();
		if ((int) $m_content->company_id !== $company_id)
			$this->denied();
		
		$this->dist_load_services($m_content->id);
		$this->dist_load_docsites($m_content->id);
		
		$this->load->view('manage/header');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/analyze/report/dist');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function dist_modal($id)
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_content = Model_Content::find($id);
		$this->vd->m_content = $m_content;
		
		if (!$m_content) show_404();
		if ((int) $m_content->company_id !== $company_id)
			$this->denied();
		
		$this->dist_load_services($m_content->id);				
		$this->load->view('manage/analyze/partials/dist_modal');
	}
	
	protected function dist_load_services($content_id)
	{
		$sql = "SELECT fs.*, fds.url as content_url 
			FROM nr_fin_distribution_service fds 
			INNER JOIN nr_fin_service fs ON fds.content_id = ?
			AND fds.date_discovered <= UTC_TIMESTAMP() AND
			fds.fs_hash = fs.hash";
			
		$db_result = $this->db->query($sql, array($content_id));
		$this->vd->services = Model_Fin_Service::from_db_all($db_result);
	}
	
	protected function dist_load_docsites($content_id)
	{
		$cd = Model_Content_DocSite::find($content_id);
		$this->vd->docs = $cd;
	}
	
	public function report_chart($id)
	{		
		$m_content = Model_Content::find($id);
		$company_id = (int) $this->newsroom->company_id;
		if (!$m_content || (int) $m_content->company_id !== $company_id)
			return;
		
		$_base = new Stats_Base($this);
		$_base->set_content($m_content->id);
		$_base->chart(880, 350);
	}
	
	public function report_geolocation($id) 
	{		
		$m_content = Model_Content::find($id);
		$company_id = (int) $this->newsroom->company_id;
		if (!$m_content || (int) $m_content->company_id !== $company_id)
			return;
		
		$view = 'manage/analyze/report/partials/geolocation';
		$_base = new Stats_Base($this);
		$_base->set_content($m_content->id);
		return $_base->geolocation($view, 4, 4);
	}
	
	public function search($status = null, $chunk = 1)
	{
		if ($status !== 'published') 
		{
			$url = 'manage/analyze/content/search/published';
			$this->redirect(gstring($url));
			return;
		}
		
		$type = 'search';
		$this->vd->is_search = true;
		$terms = $this->input->get('terms');
		$terms_sql = sql_search_terms(array('c.title', 't.tags'), $terms);
		$this->listing($chunk, $status, $type, $terms_sql);
	}

}

?>