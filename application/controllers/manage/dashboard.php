<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Dashboard_Controller extends Manage_Base {
	
	public function __construct()
	{
		parent::__construct();	
		$name = $this->newsroom->company_name;
		$this->vd->title[] = "{$name} Dashboard";
	}
	
	public function index()
	{
		$sql = "SELECT c.title, c.is_under_review, c.is_draft, 
			c.is_published, c.id, c.slug FROM nr_content c 
			WHERE c.company_id = ? AND c.type = ?
			ORDER BY c.id DESC LIMIT 5";
		
		$query = $this->db->query($sql, 
			array($this->newsroom->company_id, Model_Content::TYPE_PR));
		$this->vd->prs = Model_Content::from_db_all($query);		
		foreach ($this->vd->prs as $pr)
			$pr->mock_nr = $this->newsroom;
		
		$sql = "SELECT c.name, c.is_sent, c.is_draft, 
			c.is_send_active, c.id FROM nr_campaign c 
			WHERE c.company_id = ? ORDER BY c.id DESC LIMIT 5";
		
		$query = $this->db->query($sql, array($this->newsroom->company_id));
		$this->vd->emails = Model_Campaign::from_db_all($query);
		foreach ($this->vd->emails as $email)
			$email->mock_nr = $this->newsroom;
		
		$stats = new Statistics();
		$stats->set_newsroom($this->newsroom->name);		
		$stats->set_dt_start(Date::days(-7));
		$this->vd->pr_hits_week = $stats->
			hits_for_content_type(Model_Content::TYPE_PR);			
		$stats->set_dt_start(Date::days(-30));
		$this->vd->pr_hits_month = $stats->
			hits_for_content_type(Model_Content::TYPE_PR);
		
		$video_modal = new Modal();
		$video_modal->set_title('Control Panel Overview');
		$this->add_eob($video_modal->render(853, 480));
		$this->vd->video_modal_id = $video_modal->id;
		$this->vd->external_video_id = Model_Setting::value('overview_video');
		
		$this->vd->pr_credits_basic = Auth::user()->pr_credits_basic_stat();
		$this->vd->pr_credits_premium = Auth::user()->pr_credits_premium_stat();
		$this->vd->email_credits = Auth::user()->email_credits_stat();
	
		$this->vd->bar = new Model_Bar('dashboard', $this->newsroom);
		
		// add resources specific to overview
		$view = 'manage/overview/partials/eob';
		$eob = $this->load->view($view, null, true);
		$this->add_eob($eob);
			
		$this->load->view('manage/header');
		$this->load->view('manage/dashboard/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/dashboard/index');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function chart()
	{
		$stats = new Statistics();
		$stats->set_newsroom($this->newsroom->name);
		$stats->set_dt_start(Date::days(-30));
		$stats->set_dt_end(Date::days(-1));
		$data = $stats->hits_over_period();
		$chart = new Line_Chart($data, 460, 100);
		$chart->colors->font = array(60, 60, 60, 0);
		$chart->colors->line = array(19, 87, 168, 30);
		$chart->colors->fill = array(19, 87, 168, 90);
		$chart->point_size = 0;
		$chart->render();
	}

}

?>