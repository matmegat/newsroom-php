<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Base extends CIL_Controller {

	public function __construct()
	{
		parent::__construct();		
		if (!Auth::user() || !Auth::user()->is_admin) 
			$this->denied();
	}
	
	protected function admin_mode_from_content($content_id, $url)
	{
		$content = Model_Content::find($content_id);
		$newsroom = Model_Newsroom::find($content->company_id);
		$this->redirect($newsroom->url($url), false);
	}
	
	protected function admin_mode_from_company($company_id, $url)
	{
		$newsroom = Model_Newsroom::find($company_id);
		$this->redirect($newsroom->url($url), false);
	}
	
	protected function admin_mode_from_user($user_id, $url)
	{
		$url = Admo::url($url, $user_id);
		$this->redirect($url, false);
	}
	
	protected function create_filter_search($filter_search)
	{
		$list_filter = new stdClass();
		$list_filter->name = 'search';
		$list_filter->value = $filter_search;
		$gstring = array('filter_search' => $filter_search);
		$list_filter->gstring = http_build_query($gstring);
		array_push($this->vd->filters, $list_filter);
	}
	
	protected function create_filter_user($filter_user)
	{
		$user = Model_User::find($filter_user);
		$list_filter = new stdClass();
		$list_filter->name = 'user';
		$list_filter->value = $user->email;
		if (!$list_filter->value)
			$list_filter->value = $user->id;
		$gstring = array('filter_user' => $filter_user);
		$list_filter->gstring = http_build_query($gstring);
		array_push($this->vd->filters, $list_filter);
	}
	
	protected function create_filter_company($filter_company)
	{
		$company = Model_Company::find($filter_company);
		$list_filter = new stdClass();
		$list_filter->name = 'company';
		$list_filter->value = $company->name;
		if (!$list_filter->value)
			$list_filter->value = $company->id;
		$gstring = array('filter_company' => $filter_company);
		$list_filter->gstring = http_build_query($gstring);
		array_push($this->vd->filters, $list_filter);
	}

}

?>