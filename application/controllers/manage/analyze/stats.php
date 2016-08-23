<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stats_Base {

	protected $content = null;
	protected $campaign = null;
	protected $_this;
	
	public function __construct($_this)
	{
		$this->_this = $_this;
	}
	
	public function set_content($value)
	{
		$this->content = $value;
	}
	
	public function set_campaign($value)
	{
		$this->campaign = $value;
	}

	public function index($view)
	{
		$_this = $this->_this;
		$stats = new Statistics();
		$stats->set_newsroom($_this->newsroom->name);
		$stats->set_content($this->content);
		$stats->set_campaign($this->campaign);
		
		// $stats->set_dt_start(Date::days(-29));
		// $stats->set_dt_end(Date::$now);
		
		// if (($date_start = $_this->input->get_post('date_start')))
		// 	$stats->set_dt_start(new DateTime($date_start));
		// if (($date_end = $_this->input->get_post('date_end')))
		// 	$stats->set_dt_end(new DateTime($date_end));
		
		// if ($stats->dt_start > $stats->dt_end)
		// 	$stats->dt_end = $stats->dt_start;
		
		$_this->vd->world_map = $stats->visits_world_map(580);
		$_this->vd->summary = $stats->summary();
		
		// $_this->vd->dt_start = $stats->dt_start;
		// $_this->vd->dt_end = $stats->dt_end;
		
		$_this->vd->dt_start = Date::days(-29);
		$_this->vd->dt_end = Date::$now;
		if (($date_start = $_this->input->get_post('date_start')))
		 	$_this->vd->dt_start = new DateTime($date_start);
		if (($date_end = $_this->input->get_post('date_end')))
		 	$_this->vd->dt_end = new DateTime($date_end);
		
		$_this->load->view('manage/header');
		$_this->load->view('manage/analyze/menu');
		$_this->load->view('manage/pre-content');
		$_this->load->view($view);
		$_this->load->view('manage/post-content');
		$_this->load->view('manage/footer');
	}
	
	public function chart($width = 880, $height = 280)
	{
		$_this = $this->_this;
		$stats = new Statistics();
		$stats->set_newsroom($_this->newsroom->name);	
		$stats->set_content($this->content);
		$stats->set_campaign($this->campaign);
			
		if (($date_start = $_this->input->get('date_start')))
			$stats->set_dt_start(new DateTime($date_start));
		if (($date_end = $_this->input->get('date_end')))
			$stats->set_dt_end(new DateTime($date_end));
		
		$data = null;
		try { $data = $stats->hits_over_period(); }
		catch (Exception $e) {}
		
		if ($data === null)
		{
			$chart = new Blank_Chart($width, $height);
			$chart->render();
		}		
		else
		{
			$chart = new Line_Chart($data, $width, $height);
			$chart->point_size = 0;
			$chart->render();
		}
	}
	
	public function geolocation($view = null, $countries = 3, $regions = 3) 
	{
		$_this = $this->_this;
		$stats = new Statistics();
		$stats->set_newsroom($_this->newsroom->name);
		$stats->set_content($this->content);
		$stats->set_campaign($this->campaign);
		
		// if (($date_start = $_this->input->get('date_start')))
		// 	$stats->set_dt_start(new DateTime($date_start));
		// if (($date_end = $_this->input->get('date_end')))
		// 	$stats->set_dt_end(new DateTime($date_end));
		
		if ($view === null)
			$view = 'manage/analyze/partials/geolocation';
		
		$_this->vd->locations = $stats->visits_locations($countries, $regions);
		$_this->expires(7200);
		
		return $_this->load->view($view);		
	}
	
}

?>