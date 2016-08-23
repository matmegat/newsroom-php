<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Company_Controller extends Manage_Base {

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iNewsroom';
		$this->vd->title[] = 'Company Profile';
	}
	
	public function index()
	{
		$vd = array();		
		$order = array('name', 'asc');
		$criteria = array('is_common', 1);
		$vd['common_countries'] = Model_Country::find_all($criteria, $order);
		$vd['countries'] = Model_Country::find_all(null, $order);
		
		$company_id = $this->newsroom->company_id;
		$beats = Model_Beat::list_all_beats_by_group();
		$this->config->load('timezones', false);
		$this->vd->common_timezones = $this->config->item('common_timezones');
		$this->vd->timezones = DateTimeZone::listIdentifiers();
		$this->vd->beats = $beats;
		
		$this->vd->name = $this->newsroom->company_name;
		$profile = Model_Company_Profile::find($company_id);
		$this->vd->profile = $profile;
		
		$this->load->view('manage/header');
		$this->load->view('manage/newsroom/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/newsroom/company', $vd);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function save()
	{
		$post = $this->input->post();
		$company_id = $this->newsroom->company_id;
		$post['email'] = strtolower($post['email']);
		$post['description'] = $this->vd->pure($post['description']);
		
		foreach ($post as &$data)
			$data = value_or_null($data);
		
		$this->newsroom->company_name = $post['company_name'];
		$timezone = $post['timezone'];
		$timezones = DateTimeZone::listIdentifiers();
		if (in_array($timezone, $timezones))
			$this->newsroom->timezone = $timezone;
		
		$profile = Model_Company_Profile::find($company_id);
		if (!$profile) $profile = new Model_Company_Profile();
		$profile->company_id = $company_id;
		$profile->values($post);
		
		if ($profile->soc_twitter)
		{
			$parsed = Social_Twitter_Profile::parse_id($profile->soc_twitter);
			if ($parsed) $profile->soc_twitter = $parsed;
		}
		
		if ($profile->soc_facebook)
		{
			$parsed = Social_Facebook_Profile::parse_id($profile->soc_facebook);
			if ($parsed) $profile->soc_facebook = $parsed;
		}
		
		if ($profile->soc_gplus)
		{
			$parsed = Social_GPlus_Profile::parse_id($profile->soc_gplus);
			if ($parsed) $profile->soc_gplus = $parsed;
		}
		
		if ($profile->soc_youtube)
		{
			$parsed = Social_Youtube_Profile::parse_id($profile->soc_youtube);
			if ($parsed) $profile->soc_youtube = $parsed;
		}
		
		if ($this->input->post('is_preview'))
		{
			Detached::reset();
			Detached::write('nr_profile', $profile);
			Detached::write('newsroom', $this->newsroom);
			$preview_url = Detached::url();
			$this->redirect($preview_url, false);
		}
		else
		{
			// update the dashboard progress bar 
			Model_Bar::done('dashboard', 'company-details');
			
			// load feedback message for the user
			$feedback_view = 'manage/newsroom/partials/save_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
		
			$this->newsroom->save();
			$profile->save();
		}
		
		// redirect back to the company details
		$redirect_url = 'manage/newsroom/company';
		$this->set_redirect($redirect_url);
	}
	
}

?>