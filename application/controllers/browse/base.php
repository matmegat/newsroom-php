<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Browse_Base extends CIL_Controller {

	protected static $allowed_on_common_host = array(

		'#^$#i', 							/* browse base */
		'#^browse$#i', 					/* browse base */
		'#^browse/all$#i', 				/* browse all content */
		'#^browse/search$#i', 			/* browse search */
		'#^browse/cat#i', 				/* browse categories */
		'#^browse/rss#i', 				/* browse rss feed */
		'#^browse/tag#i', 				/* browse tags */
		'#^view/[a-z0-9\-]+$#i', 		/* view individual content */
		'#^view/id/[0-9]+$#i', 			/* view individual content (perma) */
		'#^view/pdf/[0-9]+$#i', 		/* view individual content (pdf) */
		'#^view/raw/[0-9]+$#i', 		/* view individual content (raw) */
		'#^shared/log(in|out)$#i', 	/* authentication */
		
	);
	
	public function __construct()
	{
		parent::__construct();
		
		if ($this->is_common_host)
		{
			$this->vd->wide_view = true;
			if (!$this->is_allowed_on_common_host())
				$this->redirect('manage');
			
			if (!$this->is_website_host && 
			    !$this->is_detached_host &&
			    !$this->input->post())
			{
				$url = $this->website_url($this->uri->uri_string());
				$this->redirect(gstring($url), false);
			}
		}
		else
		{
			if (!$this->newsroom->is_active)
			{
				if (Auth::is_user_online() && 
					$this->newsroom->user_id == Auth::user()->id)
				{
					// load feedback message for the user
					$feedback_view = 'browse/partials/newsroom_inactive_feedback';
					$feedback = $this->load->view($feedback_view, null, true);
					$this->use_feedback($feedback);
				}
				else
				{
					$common_newsroom = Model_Newsroom::common();
					$relative_url = $this->uri->uri_string();
					$url = $common_newsroom->url($relative_url, true);
					$this->redirect(gstring($url), false);
				}
			}
			
			$this->vd->wide_view = false;
			$this->vd->nr_custom = $this->newsroom->custom();
			$this->vd->nr_profile = $this->newsroom->profile();
			$this->vd->nr_contact = $this->newsroom->contact();
			$this->vd->nr_listed_types = $this->listed_types();
			$this->vd->nr_listed_archives = $this->listed_archives();
			
			if ($this->is_detached_host)
			{
				if ($nr_custom = Detached::read('nr_custom'))
					$this->vd->nr_custom = $nr_custom;
				if ($nr_profile = Detached::read('nr_profile'))
					$this->vd->nr_profile = $nr_profile;
				if ($nr_contact = Detached::read('nr_contact'))
					$this->vd->nr_contact = $nr_contact;
			}
		}		
	}
	
	protected function is_allowed_on_common_host()
	{
		$uri = $this->uri->uri_string();
		foreach (static::$allowed_on_common_host as $pattern)
			if (preg_match($pattern, $uri)) return true;
		return false;
	}
	
	protected function listed_types()
	{
		$listed_types = new stdClass();
		foreach (Model_Content::allowed_types() as $type)
			$listed_types->{$type} = false;
		
		$sql = "SELECT c.type FROM nr_content c WHERE 
			c.company_id = {$this->newsroom->company_id} 
			AND c.is_published = 1
			GROUP BY c.type";
		
		$results = Model_Base::from_db_all($this->db->query($sql));
		foreach ($results as $result)
			$listed_types->{$result->type} = true;
		
		$listed_types->contact = (bool) 
			(Model_Company_Contact::count_all(array('company_id', 
			$this->newsroom->company_id)) > 1);
			
		foreach ($listed_types as $listed)
			if ($listed) return $listed_types;
		return false;
	}
	
	protected function listed_archives()
	{
		$listed_archives = array();
		$dt_ranges[] = Date::in(Date::$now->format('Y-M-01 00:00:00'));
		$dt_ranges[] = Date::months(-1, end($dt_ranges));
		$dt_ranges[] = Date::months(-1, end($dt_ranges));
		$dt_ranges[] = Date::months(-1, end($dt_ranges));
		$dt_ranges[] = Date::months(-1, end($dt_ranges));
		
		for ($i = count($dt_ranges) - 1; $i > 0; $i--)
		{
			$date_start = $dt_ranges[$i]->format(Date::FORMAT_MYSQL);
			$date_end = $dt_ranges[$i-1]->format(Date::FORMAT_MYSQL);
			
			$sql = "SELECT 1 FROM nr_content c WHERE 
			 	c.company_id = {$this->newsroom->company_id} 
			 	AND c.is_published = 1 AND c.date_publish >= ? 
			 	AND c.date_publish <= ? LIMIT 1";
			 	
			$dbr = $this->db->query($sql, array($date_start, $date_end));
			if ($dbr->result()) $listed_archives[] = $dt_ranges[$i];			
		}
		
		return array_reverse($listed_archives);
	}

}

?>