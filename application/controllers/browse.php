<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('browse/listing');

class Browse_Controller extends Listing_Base {
	
	public function __construct()
	{
		parent::__construct();
		
		// if on common host => we are viewing news center
		$this->vd->is_news_center = $this->is_common_host;
	}
	
	public function _remap($method, $params = array())
	{
		// detect rss request with params
		// and enable rss mode for normal request
		if ($method == 'rss' && count($params))
		{			
			$method = $params[0];
			$params = array_slice($params, 1);
			$this->limit = $this->rss_limit;
			$this->rss_enabled = true;			
		}
				
		return parent::_remap($method, $params);
	}	
	
	public function all()
	{
		$this->limit *= 2;
		$this->basic();
	}
	
	public function index($type = null)
	{
		// redirect browse to root of host
		if ($type === null && $this->uri->segment(1)) 
			$this->redirect(null);
		
		// common doesn't filter on type
		if ($this->is_common_host)
			$this->redirect('browse/all');
		
		if ($type) 
		{
			$types = array($type);
			$type_str = Model_Content::full_type_plural($type);
			$type_str = $this->vd->esc($type_str);
			$this->vd->ln_header_html = "<span class=\"muted\">
				Latest</span> {$type_str}";
		}
		else
		{
			$types = array(Model_Content::TYPE_PR, 
				Model_Content::TYPE_NEWS, 
				Model_Content::TYPE_EVENT);
		}
		
		$this->basic($types);
	}
	
	public function search()
	{
		$this->title = 'Search Results';
		$terms = $this->input->get('terms');
		$fields = array('c.title', 't.tags');
		$terms_sql = sql_search_terms($fields, $terms);
				
		$terms_str = $this->vd->esc($terms);
		$this->vd->ln_header_html = "<span class=\"muted\">
			Search:</span> {$terms}";
				
		$sql = "SELECT c.type, GROUP_CONCAT(c.id) AS ids FROM (
			  SELECT c.type, c.id FROM nr_content c 
			  LEFT JOIN (
			    SELECT t.content_id, GROUP_CONCAT(t.value) AS tags
			    FROM nr_content_tag t GROUP BY t.content_id
			  ) t ON t.content_id = c.id
			  {$this->active_newsroom_filter}
			  WHERE ({$this->common_filter} 
			    OR c.company_id = {$this->newsroom->company_id})
			  AND c.is_published = 1
			  AND {$terms_sql}
			  ORDER BY c.date_publish DESC
			  LIMIT {$this->offset}, {$this->limit}
			) AS c GROUP BY c.type";

		$results = $this->find_results($sql);
		$this->render_list_view($results);
	}
	
	public function month($year, $month)
	{
		$year = (int) $year;
		$month = (int) $month;
		
		$ln_date = new DateTime("{$year}-{$month}-01");
		$ln_date = $ln_date->format('F Y');
		$this->vd->ln_header_html = "{$ln_date} 
			<span class=\"muted\">Archive</span>";
		
		$dt_0 = Date::in("{$year}-{$month}-01 00:00:00");
		$dt_1 = Date::months(1, $dt_0);
		
		$dt_0_str = $dt_0->format(Date::FORMAT_MYSQL);
		$dt_1_str = $dt_1->format(Date::FORMAT_MYSQL);
		$basic_filter = "c.date_publish >= '{$dt_0_str}'
			AND c.date_publish < '{$dt_1_str}'";
		
		$this->title = 'Archive';
		$this->basic(null, $basic_filter);
	}
	
	public function cat($slug, $option = null)
	{
		$criteria = array('slug', $slug);
		$cat = Model_Cat::find($criteria);
		if (!$cat) $this->redirect('browse');
		
		$this->title = $cat->name;
		$cat_name = $this->vd->esc($cat->name);
		$this->vd->ln_header_html = "<span class=\"muted\">
			Category:</span> {$cat_name}";
		
		$sql = "SELECT c.type, GROUP_CONCAT(c.id) AS ids FROM (
			  SELECT c.id, c.type FROM (
			    /* ------------------ */
			    SELECT c.id, c.type, c.date_publish FROM nr_content c
			    INNER JOIN nr_pb_pr tl
			    ON ({$this->common_filter} 
			      OR c.company_id = {$this->newsroom->company_id})
			    AND c.type = 'pr'
			    AND c.is_published = 1 
			    AND c.id = tl.content_id
			    INNER JOIN nr_cat ca ON 
			    (tl.cat_1_id = ca.id OR
			     tl.cat_2_id = ca.id OR
			     tl.cat_3_id = ca.id)
			    WHERE ca.id = {$cat->id} 
			    OR ca.cat_group_id = {$cat->id}
			    /* ------------------ */
			    UNION ALL
			    /* ------------------ */
			    SELECT c.id, c.type, c.date_publish FROM nr_content c
			    INNER JOIN nr_pb_news tl
			    ON ({$this->common_filter} 
			      OR c.company_id = {$this->newsroom->company_id})
			    AND c.type = 'news'
			    AND c.is_published = 1 
			    AND c.id = tl.content_id
			    {$this->active_newsroom_filter}
			    INNER JOIN nr_cat ca ON 
			    (tl.cat_1_id = ca.id OR
			     tl.cat_2_id = ca.id OR
			     tl.cat_3_id = ca.id)
			    WHERE ca.id = {$cat->id} 
			    OR ca.cat_group_id = {$cat->id}
			    /* ------------------ */
			  ) AS c ORDER BY c.date_publish DESC 
			  LIMIT {$this->offset}, {$this->limit}
			) AS c GROUP BY c.type";
		
		$results = $this->find_results($sql);
		$this->render_list_view($results);
	}
	
	public function tag($slug)
	{
		$this->title = $slug;
		$slug = $this->vd->esc($slug);
		$this->vd->ln_header_html = "<span class=\"muted\">
			Tagged:</span> {$slug}";
		
		$sql = "SELECT c.type, GROUP_CONCAT(c.id) AS ids FROM (
			  SELECT c.id, c.type FROM nr_content c 
			  INNER JOIN nr_content_tag ct ON 
			  c.id = ct.content_id
			  {$this->active_newsroom_filter}
			  WHERE ({$this->common_filter} 
			    OR c.company_id = {$this->newsroom->company_id})
			  AND c.is_published = 1 AND ct.uniform = ?
			  LIMIT {$this->offset}, {$this->limit}
			) AS c GROUP BY c.type";
		
		$results = $this->find_results($sql, array($slug));
		$this->render_list_view($results);
	}

	protected function basic($types = null, $basic_filter = 1)
	{
		if (!$types) $types = Model_Content::allowed_types();
		
		$types_quoted = array();
		foreach ($types as $type)
		{
			if (!Model_Content::is_allowed_type($type)) continue;
			$types_quoted[] = $this->db->escape($type);
		}
		
		if (count($types) === 1)
			$this->title = Model_Content::full_type_plural($types[0]);
			
		$types_str = implode(',', $types_quoted);
		if (!$types_str) return show_404();
		
		$sql = "SELECT c.type, GROUP_CONCAT(c.id) AS ids FROM (
			  SELECT c.type, c.id FROM nr_content c 
			  {$this->active_newsroom_filter}
			  WHERE ({$this->common_filter} 
			    OR c.company_id = {$this->newsroom->company_id})
			  AND c.type IN ({$types_str}) 
			  AND c.is_published = 1
			  AND {$basic_filter}
			  ORDER BY c.date_publish DESC
			  LIMIT {$this->offset}, {$this->limit}
			) AS c GROUP BY c.type";
		
		$results = $this->find_results($sql);
		$this->render_list_view($results);
	}
	
	public function rss()
	{
		$sql = "SELECT c.type, GROUP_CONCAT(c.id) AS ids FROM (
			  SELECT c.type, c.id FROM nr_content c 
			  {$this->active_newsroom_filter}
			  WHERE ({$this->common_filter} 
			    OR c.company_id = {$this->newsroom->company_id})
			  AND c.is_published = 1
			  ORDER BY c.date_publish DESC
			  LIMIT 0, {$this->rss_limit}
			) AS c GROUP BY c.type";

		$results = $this->find_results($sql);
		$this->vd->results = $results;
		
		$this->output->set_content_type('application/rss+xml');
		$this->load->view('browse/rss');
	}

}

?>