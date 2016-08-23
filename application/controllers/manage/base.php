<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage_Base extends CIL_Controller {
	
	protected $inherited_access = false;
	
	protected static $requires_common_host = array(
	
		'#^manage/account#i', 				/* account */
		'#^manage/companies#i', 			/* companies */
		'#^manage/overview#i', 				/* overview */
		'#^manage/upgrade#i', 				/* upgrade account */
		
	);
	
	protected static $allowed_as_free_user = array(
	   
		'#^manage/?$#i', 								/* base */
		'#^manage/account#i', 						/* account settings */
		'#^manage/analyze/?$#i', 					/* analyze base */
		'#^manage/analyze/content#i', 			/* analyze content */
		'#^manage/analyze/email#i', 				/* analyze email */
		'#^manage/companies#i', 					/* companies */
		'#^manage/contact#i',	 					/* contact all */
		'#^manage/dashboard#i', 					/* dashboard */
		'#^manage/image#i', 							/* image upload */
		'#^manage/newsroom/?$#i',					/* newsroom base */
		'#^manage/newsroom/company#i',			/* newsroom company details */
		'#^manage/newsroom/contact#i',			/* newsroom contacts */
		'#^manage/newsroom/customize#i',			/* newsroom customization */
		'#^manage/publish/?$#i', 					/* ipublish base */
		'#^manage/publish/pr#i', 					/* ipublish press releases */
		'#^manage/publish/news#i', 				/* ipublish news releases */
		'#^manage/publish/event#i', 				/* ipublish events */
		'#^manage/publish/audio#i', 				/* ipublish audio */
		'#^manage/publish/image#i', 				/* ipublish images */
		'#^manage/publish/video#i', 				/* ipublish videos */
		'#^manage/publish/search#i', 				/* ipublish search */
		'#^manage/video_guide_record#i', 		/* record video guide checkbox */
		'#^manage/upgrade#i', 						/* upgrade account */
		
	);
	
	public function __construct()
	{
		parent::__construct();
		
		$is_common_host = $this->is_common_host;		
		if (!$this->uri->segment(2))
			  $use_common_host = $is_common_host;
		else $use_common_host = $this->requires_common_host();
		
		if (!$is_common_host && $use_common_host)
		{
			if (Auth::is_admin_controlled())
			{
				$url = $this->uri->uri_string();
				$url = Admo::url($url);
				$url = gstring($url);
				$this->redirect($url, false);
			}
			else
			{
				$url = $this->uri->uri_string();
				$url = $this->common()->url($url);
				$url = gstring($url);
				$this->redirect($url, false);
			}
		}
		
		if ($is_common_host && !$use_common_host)
		{
			// find a newsroom => create newsroom on fail?
			$newsroom = Auth::user()->default_newsroom();
			$this->redirect(gstring($newsroom->url($this->uri->uri_string())), false);
		}
		
		// if on common host then we should use website host
		// but don't redirect if there is post data or if 
		// we are viewing a detached host
		if ($is_common_host && !$this->is_website_host && 
		    !$this->is_detached_host && !$this->is_admo_host &&
		    !$this->input->post())
		{
			$url = $this->website_url($this->uri->uri_string());
			$this->redirect(gstring($url), false);
		}
		
		if (Auth::user()->is_free_user() && !$this->inherited_access)
		{
			if (!$this->is_allowed_as_free_user())
				$this->redirect('manage/upgrade/premium');
		}
		
		// reseller should not be here unless 
		// from secret or inherited access
		if (Auth::user()->is_reseller && 
		    !Auth::user()->is_from_secret &&
		    !$this->inherited_access)
			$this->redirect('default');
		
		if ($this->newsroom->is_archived && 
		    !Auth::is_admin_online())
		{
			$url = $this->common()->url('manage/dashboard');
			$this->redirect(gstring($url), false);
		}
		
		$ar = $this->db->select('*')
			->from('nr_newsroom')
			->where('user_id', Auth::user()->id)
			->where('is_archived', 0)
			->order_by('company_name', 'asc');		
		$this->vd->user_newsrooms = 
			Model_Newsroom::from_db_all($ar->get());
			
		// use overview mode if on common host and platinum
		$this->use_overview = $this->is_common_host
		 && Auth::user()->has_platinum_access();
	}
	
	protected function is_allowed_as_free_user()
	{
		$uri = $this->uri->uri_string();
		foreach (static::$allowed_as_free_user as $pattern)
			if (preg_match($pattern, $uri)) return true;
		return false;
	}
	
	protected function requires_common_host()
	{
		$uri = $this->uri->uri_string();
		foreach (static::$requires_common_host as $pattern)
			if (preg_match($pattern, $uri)) return true;
		return false;
	}
	
}

?>