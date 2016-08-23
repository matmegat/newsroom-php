<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');
load_controller('manage/analyze/stats');

class Overall_Controller extends Manage_Base {

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iAnalyze';
		$this->vd->title[] = 'Newsroom Stats';
	}
	
	public function report()
	{
		if (!$this->newsroom->is_active)
			$this->redirect('manage/upgrade/newsroom');
		
		$generate_url = 'manage/analyze/overall/report_generate';
		$generate_url = gstring($generate_url);
		$this->vd->generate_url = $generate_url;
		
		$return_url = 'manage/analyze/overall';
		$return_url = gstring($return_url);
		$this->vd->return_url = $return_url;
		
		$this->load->view('manage/header');
		$this->load->view('manage/analyze/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/analyze/report-generate');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function report_generate()
	{
		$url = 'manage/analyze/overall/report_index';
		$url = $this->newsroom->url($url);
		$url = gstring($url);
		$report = new Report($url);
		$report->generate();
		
		if ($this->input->post('indirect'))
			  $report->indirect();
		else $report->deliver();
	}
	
	public function report_index()
	{
		$company_id = (int) $this->newsroom->company_id;
		$sql = "SELECT * FROM nr_content c 
			WHERE c.company_id = ? 
			AND c.date_publish < UTC_TIMESTAMP()
			AND c.is_published = 1
			ORDER BY c.date_publish DESC
			LIMIT 10";
			
		$query = $this->db->query($sql, 
			array($company_id));
			
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$stats = new Statistics();
		$stats->hits_for_content_set($results);
		$this->vd->recent_content = $results;
		
		$_base = new Stats_Base($this);
		$_base->index('manage/analyze/report/overall');
	}
	
	public function report_chart()
	{
		$_base = new Stats_Base($this);
		$_base->chart(880, 350);
	}
	
	public function report_geolocation() 
	{
		$view = 'manage/analyze/report/partials/geolocation';
		$_base = new Stats_Base($this);		
		return $_base->geolocation($view, 4, 4);
	}

	public function index()
	{
		if (!$this->newsroom->is_active)
		{
			$this->session->set('upgrade-redirect', 'manage/analyze/overall');
			$this->redirect('manage/upgrade/newsroom');
		}
		
		$_base = new Stats_Base($this);
		$_base->index('manage/analyze/overall');
	}
	
	public function chart()
	{
		$_base = new Stats_Base($this);
		$_base->chart();
	}
	
	public function geolocation() 
	{
		$_base = new Stats_Base($this);
		$_base->geolocation();
	}

}

?>