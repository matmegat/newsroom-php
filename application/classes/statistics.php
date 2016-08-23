<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Statistics {
	
	const CV_NEWSROOM = 1;
	const CV_CONTENT  = 2;
	const CV_CAMPAIGN = 3;
	const CV_CONTENT_TYPE = 4;
	const CV_OWNER = 5;
	
	const BATCH_SOFT_LIMIT = 30;
	const BATCH_HARD_LIMIT = 150;
	
	protected $newsroom;
	protected $content;
	protected $campaign;
	protected $user;
	
	public $params;
	public $segment_params;
	public $site_id;
	protected $auth_token;
	protected $admin_auth_token;
	public $use_admin_token;
	protected $batch;
	public $dt_start;
	public $dt_end;
	
	public function __construct()
	{
		$ci =& get_instance();
		$this->auth_token = $ci->conf('piwik_auth_token');
		$this->admin_auth_token = $ci->conf('piwik_admin_auth_token');
		$this->site_id = $ci->conf('piwik_site_id');
		$this->base_url = $ci->conf('piwik_base_url');
		$this->use_admin_token = false;
		$this->clear_params();
	}
	
	public function batch_start()
	{
		$this->batch = array();
	}
	
	public function batch_add()
	{
		// this is provided as part of batch
		if (isset($this->params['module']))
			unset($this->params['module']);
		
		// this is provided as part of batch
		if (isset($this->params['format']))
			unset($this->params['format']);
		
		// this is provided as part of batch
		if (isset($this->params['token_auth']))
			unset($this->params['token_auth']);
		
		$url_params = http_build_query($this->params);
		$this->batch[] = urlencode($url_params);
	}
	
	public function batch_execute($admin = false)
	{
		$combined_results = array();
		
		if (count($this->batch) > static::BATCH_HARD_LIMIT)
			throw new Exception();
		
		for ($i = 0; $i < count($this->batch); $i += static::BATCH_SOFT_LIMIT)
		{
			$this->clear_params();
			$this->build_base_params($admin);
			$this->params['method'] = 'API.getBulkRequest';		
			$this->params['urls'] = array_slice($this->batch, $i, static::BATCH_SOFT_LIMIT);
			$combined_results = array_merge($combined_results, $this->execute());
		}
		
		return $combined_results;
	}
	
	public function clear_segment_params()
	{
		$this->segment_params = array();
	}
	
	public function clear_params()
	{
		$this->clear_segment_params();
		$this->params = array();		
	}
	
	public function build_base_params()
	{
		$this->params['module'] = 'API';
		$this->params['format'] = 'json';
		$this->params['token_auth'] = $this->auth_token;
		if ($this->use_admin_token) $this->params['token_auth'] 
			= $this->admin_auth_token;
	}
	
	public function build_params($enable_cvars = true)
	{	
		$this->build_base_params();
		$this->params['idSite'] = $this->site_id;
		
		if ($enable_cvars)
		{
			if ($this->campaign)
			{
				$ca_value = array();
				$ca_value[] = 'customVariablePageValue3==';
				$ca_value[] = $this->campaign;
				$this->segment_params[] = $ca_value;
			}
			
			if ($this->user)
			{
				$ca_value = array();
				$ca_value[] = 'customVariablePageValue5==';
				$ca_value[] = $this->user;
				$this->segment_params[] = $ca_value;
			}
			
			if ($this->content)
			{
				$ca_value = array();
				$ca_value[] = 'customVariablePageValue2==';
				$ca_value[] = $this->content;
				$this->segment_params[] = $ca_value;
			}
			else if ($this->newsroom)
			{
				$ca_value = array();
				$ca_value[] = 'customVariablePageValue1==';
				$ca_value[] = $this->newsroom;
				$this->segment_params[] = $ca_value;
			}
		}
		
		$this->build_segment_params();
	}
	
	public function build_segment_params()
	{
		foreach ($this->segment_params as $k => $v)
		{
			if (!is_array($v)) continue;
			$this->segment_params[$k] = implode('', $v);
		}
		
		$this->params['segment'] = implode(';', $this->segment_params);
	}
	
	public function url()
	{
		return $this->private_url();
	}
	
	public function private_url()
	{
		$params = http_build_query($this->params);
		$url = "{$this->base_url}/?{$params}";
		return $url;
	}
	
	public function public_url()
	{
		$params = http_build_query($this->params);
		$url = "{$this->base_url}/piwik.php?{$params}";
		return $url;
	}
	
	public function execute()
	{
		$url = $this->url();
		$result = @file_get_contents($url);
		if (!$result) return false;
		return json_decode($result);
	}
	
	public function params_date_range_set()
	{
		if (!$this->dt_start) $this->dt_start = new DateTime('2000-01-01');
		if (!$this->dt_end) $this->dt_end = Date::$now;
		$date_start = $this->dt_start->format('Y-m-d');
		$date_end = $this->dt_end->format('Y-m-d');		
		$this->params['date'] = "{$date_start},{$date_end}";
		$this->params['period'] = 'range';
	}
	
	public function set_newsroom($value = null)
	{
		$this->newsroom = $value;
	}
	
	public function set_content($value = null)
	{
		$this->content = $value;
	}
	
	public function set_user($value = null)
	{
		$this->user = $value;
	}
	
	public function set_campaign($value = null)
	{
		$this->campaign = $value;
	}
	
	public function set_dt_start($value = null)
	{
		$this->dt_start = $value;
	}
	
	public function set_dt_end($value = null)
	{
		$this->dt_end = $value;
	}
	
	public function summary()
	{
		$this->batch_start();
		
		$this->clear_params();
		$this->build_params();
		$this->params_date_range_set();
		$this->params['method'] = 'VisitsSummary.get'; 
		$this->batch_add();
		
		$this->clear_params();
		$this->build_params();
		$this->params_date_range_set();
		$this->params['method'] = 'Actions.get'; 
		$this->batch_add();
		
		$raw_results = $this->batch_execute();
		
		$result = new stdClass();
		$result->visits = $raw_results[0]->nb_visits;
		$result->hits = $raw_results[1]->nb_pageviews;
		
		return $result;
	}
	
	public function hits_for_content_set($content_set)
	{
		$this->batch_start();
		
		foreach ($content_set as $content)
		{
			if (isset($content->id))
				$content = $content->id;
			
			$this->clear_params();
			$this->set_content($content);
			$this->build_params();
			$this->params_date_range_set();
			$this->params['method'] = 'Actions.get';
			$this->batch_add();
		}
		
		$raw_results = $this->batch_execute();
		$results = array();
		
		foreach ($content_set as $k => $content)
		{
			if (isset($content->id))
				// store hits within original object
				$content_set[$k]->hits = $raw_results[$k]->nb_pageviews;
			$results[] = $raw_results[$k]->nb_pageviews;
		}
		
		return $results;
	}
	
	public function hits_for_content_type($type)
	{
		$this->clear_params();
		$this->build_params();
		
		$ca_value = array();
		$ca_value[] = 'customVariablePageValue4==';
		$ca_value[] = $type;
		$this->segment_params[] = $ca_value;
		$this->build_segment_params();
		$this->params_date_range_set();
		$this->params['method'] = 'Actions.get';
		$result = $this->execute();
		if (!isset($result->nb_pageviews)) return 0;
		return $result->nb_pageviews;
	}
	
	public function hits_over_period()
	{
		$this->batch_start();
		$this->clear_params();
		$this->build_params();
		$this->params['method'] = 'Actions.get';
		$this->params['period'] = 'day';
			
		$period_data = array();
		
		$start = clone $this->dt_start;	
		$end = clone $this->dt_end;
		
		for ($i = $start; $i <= $end; $i = Date::days(1, $i))
		{
			$this->params['date'] = $i->format('Y-m-d');
			$this->batch_add();
			
			$data_item = new stdClass();
			$data_item->label = $i->format('M j');
			$data_item->datetime = $i;
			$period_data[] = $data_item;
		}
		
		$results = $this->batch_execute();
		
		for ($i = 0; $i < count($period_data); $i++)
			$period_data[$i]->value = $results[$i]->nb_pageviews;
		
		return $period_data;
	}
	
	public function visits_locations($countries_limit, $regions_limit)
	{
		$results = array();
		$this->clear_params();
		$this->build_params();
		$this->params_date_range_set();
		$this->params['method'] = 'UserCountry.getCountry';
		$this->params['filter_limit'] = $countries_limit;
		$raw_countries = $this->execute();
		
		$this->batch_start();
		
		foreach ($raw_countries as $k => $raw_country)
		{
			$this->clear_params();
			$ca_value = array();
			$ca_value[] = 'countryCode==';
			$ca_value[] = $raw_country->code;
			$this->segment_params[] = $ca_value;
			
			$this->build_params();
			$this->params_date_range_set();
			$this->params['method'] = 'UserCountry.getRegion';
			$this->params['filter_limit'] = $regions_limit;
			$this->batch_add();
			
			$result = new stdClass();
			$result->country = new stdClass();
			$result->country->label = $raw_country->label;
			$result->country->visits = $raw_country->nb_visits;
			$result->country->flag = "{$this->base_url}/{$raw_country->logo}";
			$result->regions = array();
			$results[] = $result;
		}
		
		$raw_regions_batch = $this->batch_execute();
		
		foreach ($results as $k => $result)
		{		
			foreach ($raw_regions_batch[$k] as $raw_region)
			{
				$region = new stdClass();
				$region->label = $raw_region->region_name;
				$region->visits = $raw_region->nb_visits;
				$result->regions[] = $region;
			}			
		}
		
		return $results;
	}
	
	public function visits_world_map($width = 400, $height = null)
	{
		$this->clear_params();
		$this->build_params();
		
		$this->params['widget'] = 1;
		$this->params['module'] = 'Widgetize';
		$this->params['action'] = 'iframe';
		$this->params['moduleToWidgetize'] = 'UserCountryMap';
		$this->params['actionToWidgetize'] = 'visitorMap';
		$this->params['disableLink'] = 1;
		$this->params_date_range_set();
		
		if ($height === null)
			// estimate correct height
			$height = ceil(($width / 400) * 283);
		
		$ci =& get_instance();
		$view_data = array();
		$view_data['src'] = $this->url();
		$view_data['width'] = $width;
		$view_data['height'] = $height;
		
		return $ci->load->view('manage/partials/world-map', $view_data, true);
	}
	
	public function visits_for_campaign_set($campaign_set, $newsroom_set = null)
	{
		$this->batch_start();
		$this->clear_params();
		$this->build_params(false);
		$this->params_date_range_set();
		$this->params['method'] = 'Actions.getPageUrl';

		foreach ($campaign_set as $k => $campaign)
		{
			if (isset($newsroom_set[$k]))
				$this->set_newsroom($newsroom_set[$k]);
				
			if (isset($campaign->id))
				$campaign = $campaign->id;
			
			$this->set_campaign($campaign);
			$this->params['pageUrl'] = $this->email_view_tracker_url();
			$this->batch_add();
		}
		
		$raw_results = $this->batch_execute();
		$results = array();
		
		foreach ($campaign_set as $k => $campaign)
		{
			if (isset($campaign->id))
				// store hits within original object
				$campaign_set[$k]->visits = (int) @$raw_results[$k][0]->nb_visits;
			$results[] = (int) @$raw_results[$k][0]->nb_visits;
		}
		
		return $results;
	}
	
	public function visits_email_view_pixel()
	{
		$this->clear_params();
		$this->build_params(false);
		
		$this->params_date_range_set();
		$this->params['method'] = 'Actions.getPageUrl';
		$this->params['pageUrl'] = $this->email_view_tracker_url();
		
		$result = $this->execute();
		if (!isset($result[0])) 
			return 0;
		
		$result = $result[0];
		return $result->nb_visits;
	}
	
	public function visits_email_click_pixel()
	{
		$this->clear_params();
		$this->build_params(false);
		
		$this->params_date_range_set();
		$this->params['method'] = 'Actions.getPageUrl';
		$this->params['pageUrl'] = $this->email_click_tracker_url();
		
		$result = $this->execute();
		if (!isset($result[0])) 
			return 0;
		
		$result = $result[0];
		return $result->nb_visits;
	}
	
	// tracker url can be obtained using one of 
	// the email_*_tracker_url methods
	// * email_view_tracker_url();
	// * email_click_tracker_url();
	public function check_email_pixel_for_addresses($tracker_url, $addresses = array())
	{
		$statuses = array();
		$this->batch_start();
		$this->clear_params();
		$this->build_params(false);
		$this->params_date_range_set();
		$this->params['method'] = 'Actions.getPageUrl';
		$this->params['pageUrl'] = $tracker_url;
		
		for ($i = 0; $i < count($addresses); $i++)
		{
			$segment = array();
			$segment[] = 'visitorId==';
			$segment[] = $this->email_pixel_user_id($addresses[$i]);
			$this->clear_segment_params();
			$this->segment_params[] = $segment;
			$this->build_segment_params();	
			$this->batch_add();			
		}
						
		foreach ($this->batch_execute() as $result)
			$statuses[] = isset($result[0]->nb_visits);
		
		return $statuses;
	}
	
	public function email_pixel_user_id($email_address)
	{
		return substr(md5($email_address), 0, 16);
	}
	
	public function email_click_pixel($piwik_user_id)
	{		
		// page url given to piwik using custom email:// protocol
		$url = $this->email_click_tracker_url();
		
		$this->clear_params();
		$this->params['idsite'] = $this->site_id;
		$this->params['_id'] = $piwik_user_id;
		$this->params['url'] = $url;
		$this->params['rec'] = 1;
		
		return $this->public_url();
	}
	
	public function hits_for_oa_newsroom_set($newsroom_set)
	{
		$this->batch_start();
		
		foreach ($newsroom_set as $newsroom)
		{
			if ($newsroom->is_active)
			{
				$this->clear_params();
				$this->set_newsroom($newsroom->name);
				$this->build_params();
				
				$this->params_date_range_set();
				$this->params['method'] = 'Actions.get';
				$this->batch_add();
			}
			
			$this->clear_params();
			$this->set_newsroom($newsroom->name);
			$this->build_params();
			
			$ca_value = array();
			$ca_value[] = 'customVariablePageValue4==';
			$ca_value[] = Model_Content::TYPE_PR;
			$this->segment_params[] = $ca_value;
			$this->build_segment_params();
			
			$this->params_date_range_set();
			$this->params['method'] = 'Actions.get';
			$this->batch_add();
		}
		
		$raw_results = $this->batch_execute();
		$results = array();
		$offset = 0;
		
		foreach ($newsroom_set as $k => $newsroom)
		{
			$result_ob = new stdClass();
			
			if ($newsroom->is_active)
			{
				// store hits within original object
				$newsroom_set[$k]->hits = $raw_results[$offset]->nb_pageviews;
				$result_ob->hits = $raw_results[$offset++]->nb_pageviews;
			}
			
			$newsroom_set[$k]->pr_hits = $raw_results[$offset]->nb_pageviews;
			$result_ob->pr_hits = $raw_results[$offset++]->nb_pageviews;			
			$results[] = $result_ob;
		}
		
		return $results;
	}
	
	public function email_view_pixel($email_address)
	{
		// generate a unique user id based on email address
		$user_id = $this->email_pixel_user_id($email_address);
		
		// page url given to piwik using custom email:// protocol
		$url = $this->email_view_tracker_url();
		
		$this->clear_params();
		$this->params['idsite'] = $this->site_id;
		$this->params['_id'] = $user_id;
		$this->params['url'] = $url;
		$this->params['rec'] = 1;
		
		return $this->public_url();
	}
	
	public function email_view_tracker_url()
	{
		// requires that newsroom and campaign be set
		if (!$this->newsroom || !$this->campaign)
			throw new Exception();
		
		// page url given to piwik using custom email:// protocol
		return "email://{$this->newsroom}/view/{$this->campaign}";
	}
	
	public function email_click_tracker_url()
	{
		// requires that newsroom and campaign be set
		if (!$this->newsroom || !$this->campaign)
			throw new Exception();
		
		// page url given to piwik using custom email:// protocol
		return "email://{$this->newsroom}/click/{$this->campaign}";
	}
	
}

?>