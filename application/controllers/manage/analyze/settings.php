<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Settings_Controller extends Manage_Base {
	
	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iAnalyze';
		$this->vd->title[] = 'Settings';	
	}
	
	public function index()
	{
		$company_id = $this->newsroom->company_id;
		$settings = Model_Report_Settings::find($company_id);
		$this->vd->settings = $settings;
		
		$this->load->view('manage/header');
		$this->load->view('manage/analyze/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/analyze/settings');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function save()
	{
		$post = $this->input->post();
		foreach ($post as &$data)
			$data = value_or_null($data);
		
		$company_id = $this->newsroom->company_id;
		$settings = Model_Report_Settings::find($company_id);
		if (!$settings) $settings = new Model_Report_Settings();
		$settings->values($post);
		$settings->company_id = $company_id;
		$settings->save();
		
		if ($this->input->post('test'))
		{
			$url = 'assets/other/blank.html';
			$url = $this->newsroom->url($url);
			$report = new Report($url);
			$file = $report->generate();
			
			$overall_email = new Report_Email();
			$overall_email->set_context($this->newsroom->company_name);
			$overall_email->set_type(REPORT_EMAIL::TYPE_OVERALL);
			$overall_email->set_addresses($settings->overall_email);
			$overall_email->send($file);
			
			$url = 'assets/other/blank.html';
			$url = $this->newsroom->url($url);
			$report = new Report($url);
			$file = $report->generate();
			
			$pr_email = new Report_Email();
			$pr_email->set_context('Test Content');
			$pr_email->set_type(REPORT_EMAIL::TYPE_PR);
			$pr_email->set_addresses($settings->pr_email);
			$pr_email->send($file);
			
			unlink($file);
		}
		
		// load feedback message for the user
		$feedback_view = 'manage/analyze/partials/settings_save_feedback';
		$feedback = $this->load->view($feedback_view, array('campaign' => $campaign), true);
		$this->add_feedback($feedback);
		
		// redirect back to the settings
		$redirect_url = 'manage/analyze/settings';
		$this->redirect($redirect_url);
	}
	
}

?>