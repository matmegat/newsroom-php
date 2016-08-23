<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('browse/base');

class Listing_Base extends Browse_Base {
	
	protected $limit = 10;
	protected $offset = 0;
	protected $common_filter = 0;
	protected $active_newsroom_filter = null;
	protected $rss_limit = 50;
	protected $rss_enabled = false;
	
	public function __construct()
	{
		parent::__construct();
		$this->offset = (int) 
			$this->input->get('offset');
		$this->common_filter = (int)
			$this->is_common_host;
			
		if ($this->is_common_host)
		{
			$this->active_newsroom_filter = 
				// do not list non-pr content for 
				// a newsroom that is inactive
				"INNER JOIN nr_newsroom nr ON 
				c.company_id = nr.company_id AND
				(c.type = 'pr' OR nr.is_active = 1)";
		}
	}
	
	protected function find_results($sql, $params = null)
	{
		$results = array();
		$id_filter = array();
		$query = $this->db->query($sql, $params);
		foreach ($query->result() as $result)
			$id_filter[$result->type] = $result->ids;
		
		foreach ($id_filter as $type => $ids)
		{
			$sql = "SELECT *,
				UNIX_TIMESTAMP(c.date_publish) as ts
				FROM nr_content c 
				LEFT JOIN nr_content_data cd 
				ON c.id = cd.content_id
				WHERE c.id IN ({$ids})
				ORDER BY c.date_publish DESC";
				
			$query = $this->db->query($sql);
			foreach ($query->result() as $result)
				$results[] = Model_Content::from_object($result);
		}
		
		$class = get_class($this);
		$method = 'combined_sort';
		$callable = array($class, $method);
		usort($results, $callable);
		
		return $results;
	}
	
	protected function render_list_view($results)
	{
		$this->vd->results = $results;
		
		if ($this->rss_enabled)
		{
			$this->output->set_content_type('application/rss+xml');
			return $this->load->view('browse/rss');
		}
		
		if ($this->input->get('partial')) 
		{
			if (!count($results)) return $this->json(false);
			$content = $this->load->view('browse/partial-listing', null, true);
			return $this->json(array('data' => $content));
		}
		
		$this->load->view('browse/header');		
		if ($this->is_common_host)
		     $this->load->view('browse/listing-common');
		else $this->load->view('browse/listing');		
		$this->load->view('browse/footer');
	}
	
	public static function combined_sort($a, $b)
	{
		if ($a->ts > $b->ts) return -1;
		if ($a->ts < $b->ts) return +1;
		return 0;
	}

}

?>