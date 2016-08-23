<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Social_Controller extends Manage_Base {

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iNewsroom';
		$this->vd->title[] = 'Social Settings';
	}
	
	public function index()
	{
		$facebook_auth = Social_Facebook_Auth::find($this->newsroom->company_id);
		$this->vd->facebook_auth = $facebook_auth;
		if ($facebook_auth) $facebook_auth->test();
		
		$twitter_auth = Social_Twitter_Auth::find($this->newsroom->company_id);
		$this->vd->twitter_auth = $twitter_auth;
		if ($twitter_auth) $twitter_auth->test();
		
		$this->vd->facebook_name = Social_Facebook_Profile::name($facebook_auth);
		$this->vd->twitter_name = @$twitter_auth->username;
		$this->vd->facebook_pages = array();
		
		if ($facebook_auth && $facebook_auth->is_valid())
		{
			$pages = $facebook_auth->page_list();
			$this->vd->facebook_pages = $pages;
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/newsroom/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/newsroom/social');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function facebook_page()
	{
		$page = $this->input->post('page');
		$facebook_auth = Social_Facebook_Auth::find($this->newsroom->company_id);
		if ($facebook_auth) $facebook_auth->set_page($page);
		$this->redirect('manage/newsroom/social');
	}
	
	public function facebook_delete()
	{
		$facebook_auth = Social_Facebook_Auth::find($this->newsroom->company_id);
		if ($facebook_auth) $facebook_auth->delete();
		$this->redirect('manage/newsroom/social');
	}
	
	public function twitter_delete()
	{
		$twitter_auth = Social_Twitter_Auth::find($this->newsroom->company_id);
		if ($twitter_auth) $twitter_auth->delete();
		$this->redirect('manage/newsroom/social');
	}
	
	public function facebook_start()
	{
		$common = $this->conf('common_host');
		$params = array('newsroom' => $this->newsroom->name);
		$params = http_build_query($params);
		$prefix = "http://{$common}/common/";
		$url = "{$prefix}facebook_auth_request?{$params}";
		$this->redirect($url, false);
	}
	
	public function twitter_start()
	{
		$common = $this->conf('common_host');
		$params = array('newsroom' => $this->newsroom->name);
		$params = http_build_query($params);
		$prefix = "http://{$common}/common/";
		$url = "{$prefix}twitter_auth_request?{$params}";
		$this->redirect($url, false);
	}
	
}

?>