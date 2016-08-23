<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/publish/content');

class Search_Controller extends Content_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iPublish';
		$this->vd->title[] = 'Search';
	}
	
	public function index()
	{
		$this->redirect('manage/publish/search/all');
	}

	public function listing($chunk, $status, $type = null, $filter = 1)
	{
		$type = 'search';
		$terms = $this->input->get('terms');
		$terms_sql = sql_search_terms(array('c.title', 't.tags'), $terms);
		$filter = "{$filter} AND {$terms_sql}";
		
		parent::listing($chunk, $status, $type, $filter);
	}
	
	public function related()
	{
		$type_sql = 1;
		$type = value_or_null($this->input->post('type'));		
		if ($type && Model_Content::is_allowed_type($type))
			$type_sql = "c.type = '{$type}'";
		
		$terms_str = $this->input->post('terms');
		$terms = trim($terms_str);		
		if (!$this->input->post('allow_empty') && !$terms)
			return $this->json(array());
			
		$terms_sql = sql_search_terms(array('c.title', 't.tags'), $terms);
		$company_id = $this->newsroom->company_id;		
		
		$exclusions = (array) $this->input->post('exclude');
		foreach ($exclusions as &$exclude)
			$exclude = (int) $exclude;
		
		$exclusions[] = 0;
		$exclusions_sql = implode(',', $exclusions);
		
		$empty_limit = (int) $this->input->post('empty_limit');
		$limit = (int) $this->input->post('limit');
		if (!$terms) $limit = $empty_limit;
		if (!$limit) $limit = 10;
		
		$sql = "SELECT c.id, c.title, c.type FROM nr_content c 
			LEFT JOIN (
				SELECT t.content_id, GROUP_CONCAT(t.value) AS tags
				FROM nr_content_tag t GROUP BY t.content_id
			) t ON t.content_id = c.id
			WHERE c.company_id = ? 
			AND {$type_sql} AND {$terms_sql} 
			AND c.id NOT IN ({$exclusions_sql})
			ORDER BY c.id DESC LIMIT {$limit}";
				
		$query = $this->db->query($sql, array($company_id));
		$results = array();
		
		foreach ($query->result() as $result)
		{
			$result->type = Model_Content::short_type($result->type);
			$result->title = $this->vd->esc($this->vd->cut($result->title, 60));
			$results[] = $result;
		}
		
		$response = new stdClass();
		$response->data = $results;
		$response->search = $terms_str;
		$response->type = $type;
		$this->json($response);
	}
	
	public function redirect($url, $relative = true, $terminate = true) 
	{
		$url = $this->add_current_qs($url);
		// call redirect with query params maintained
		parent::redirect($url, $relative, $terminate);
	}
	
	public function add_current_qs($url)
	{
		return gstring($url);
	}
	
}

?>