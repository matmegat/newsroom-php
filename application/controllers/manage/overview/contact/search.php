<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/overview/base');

class Search_Controller extends Overview_Base {

	public $title = 'iContact Search';

	protected $use_overview_css = false;
	const SEARCH_CHUNK_SIZE = 10;
	
	public function index($chunk = 1)
	{
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::SEARCH_CHUNK_SIZE);
		$url_format = gstring('manage/overview/contact/search/-chunk-');
		$chunkination->set_url_format($url_format);
		
		$terms = $this->input->get('terms');
		$results = $this->fetch_search_results($chunkination, $terms);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('manage/header');
		$this->load->view('manage/overview/contact/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/overview/contact/search');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	protected function fetch_search_results($chunkination, $terms)
	{
		$terms_filter = sql_search_terms(array('c.name', 'c.subject'), $terms);
		$limit_str = $chunkination->limit_str();
		$user_id = Auth::user()->id;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS c.*, 
			cm.name AS company_name, cm.newsroom, cm.newsroom_timezone
			FROM nr_campaign c INNER JOIN nr_company cm
			ON c.company_id = cm.id
			WHERE cm.user_id = {$user_id} AND cm.is_archived = 0
			AND {$terms_filter} ORDER BY c.id DESC {$limit_str}";
						
		$query = $this->db->query($sql);
		$results = Model_Campaign::from_db_all($query);
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
			
		foreach ($results as $result)
		{
			$result->mock_nr = new Model_Newsroom();
			$result->mock_nr->name = $result->newsroom;
			$result->mock_nr->timezone = $result->newsroom_timezone;
		}
			
		$chunkination->set_total($total_results);
		return $results;
	}
	
}

?>