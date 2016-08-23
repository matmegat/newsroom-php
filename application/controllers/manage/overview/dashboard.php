<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/overview/base');

class Dashboard_Controller extends Overview_Base {
	
	public $title = 'Overview Dashboard';
	public $use_level_colors = true;
	
	public function index()
	{
		$sql = "SELECT c.title, c.is_under_review, c.is_draft, 
			c.is_published, c.id, c.slug, cm.newsroom FROM nr_content c 
			INNER JOIN nr_company cm ON c.company_id = cm.id
			WHERE cm.user_id = ? AND c.type = ?
			ORDER BY c.id DESC LIMIT 5";
		
		$query = $this->db->query($sql, 
			array(Auth::user()->id, Model_Content::TYPE_PR));
		$this->vd->prs = Model_Content::from_db_all($query);
		
		foreach ($this->vd->prs as $pr)
		{
			$pr->mock_nr = new Model_Newsroom();
			$pr->mock_nr->name = $pr->newsroom;
		}
		
		$sql = "SELECT c.name, c.is_sent, c.is_draft, 
			c.is_send_active, c.id, cm.newsroom FROM nr_campaign c 
			INNER JOIN nr_company cm ON c.company_id = cm.id
			WHERE cm.user_id = ? ORDER BY c.id DESC LIMIT 5";
		
		$query = $this->db->query($sql, array(Auth::user()->id));
		$this->vd->emails = Model_Campaign::from_db_all($query);
		
		foreach ($this->vd->emails as $email)
		{
			$email->mock_nr = new Model_Newsroom();
			$email->mock_nr->name = $email->newsroom;
		}
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS n.*, nc.logo_image_id
			FROM nr_newsroom n LEFT JOIN nr_newsroom_custom nc
			ON n.company_id = nc.company_id WHERE 
			n.user_id = ? AND n.is_archived = 0
			ORDER BY n.order_default DESC, 
			n.company_name ASC LIMIT 5";
		
		$dbr = $this->db->query($sql, array(Auth::user()->id));
		$this->vd->companies = Model_Newsroom::from_db_all($dbr);
		
		$stats = new Statistics();
		$stats->set_user(Auth::user()->id);		
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
		
		$new_company_modal = new Modal();
		$new_company_modal->set_title('Add Company');
		$modal_view = 'manage/overview/dashboard/partials/new_company_modal';
		$modal_content = $this->load->view($modal_view, null, true);
		$new_company_modal->set_content($modal_content);
		$this->add_eob($new_company_modal->render(400, 44));
		$this->vd->new_company_modal_id = $new_company_modal->id;
		
		$this->vd->pr_credits_basic = Auth::user()->pr_credits_basic_stat();
		$this->vd->pr_credits_premium = Auth::user()->pr_credits_premium_stat();
		$this->vd->email_credits = Auth::user()->email_credits_stat();
	
		$newsrooms = Auth::user()->newsrooms();
		$this->vd->bars = array();
		foreach ($newsrooms as $newsroom)
		{
			$bar = new Model_Bar('dashboard', $newsroom);
			$bar->company_name = $newsroom->company_name;
			if (!$bar->is_done()) $this->vd->bars[] = $bar;
			if (count($this->vd->bars) >= 10) break;
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/overview/dashboard/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/overview/dashboard/index');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function chart()
	{
		$stats = new Statistics();
		$stats->set_user(Auth::user()->id);
		$stats->set_dt_start(Date::days(-30));
		$stats->set_dt_end(Date::days(-1));
		$data = $stats->hits_over_period();
		$chart = new Line_Chart($data, 460, 100);
		$chart->colors->font = array(90, 90, 90, 0);
		$chart->colors->line = array(95, 147, 199, 0);
		$chart->colors->fill = array(95, 147, 199, 90);
		$chart->point_size = 0;
		$chart->render();
	}

}

?>