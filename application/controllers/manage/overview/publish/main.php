<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/overview/base');

class Main_Controller extends Overview_Base {

	public $title = 'iPublish Overview';

	const LISTING_CHUNK_SIZE = 3;
	const CONTENT_PER_COMPANY = 3;

	public function index($chunk = 1)
	{		
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring('manage/overview/publish/-chunk-');
		$chunkination->set_url_format($url_format);
		
		$results = $this->fetch_results($chunkination);
		foreach ($results as $result)
			$result->content = $this->fetch_result_content($result);
		$this->fetch_content_views($results);
		$this->render_list($chunkination, $results);
	}
	
	protected function fetch_results($chunkination)
	{
		$limit_str = $chunkination->limit_str();
		$user_id = Auth::user()->id;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS nc.*, n.*
			FROM nr_newsroom n LEFT JOIN (
			  SELECT n.company_id, 1 AS is_default FROM nr_newsroom n
			  WHERE n.user_id = {$user_id} ORDER BY 
			  n.order_default DESC LIMIT 1
			) AS df ON n.company_id = df.company_id 
			LEFT JOIN nr_newsroom_custom nc
			ON n.company_id = nc.company_id
			WHERE n.user_id = {$user_id} AND	n.is_archived = 0 
			ORDER BY (df.is_default IS NULL) ASC, 
			n.company_name ASC {$limit_str}";
						
		$query = $this->db->query($sql);
		$results = Model_Newsroom::from_db_all($query);
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
			
		$chunkination->set_total($total_results);
		return $results;
	}
	
	protected function fetch_result_content($result)
	{
		$sql = "SELECT c.* FROM nr_content c 
			WHERE c.company_id = {$result->company_id}
			AND c.type = ?	ORDER BY c.id DESC LIMIT ?";
						
		$query = $this->db->query($sql, 
			array(Model_Content::TYPE_PR, static::CONTENT_PER_COMPANY));
		return Model_Content::from_db_all($query);
	}
	
	protected function fetch_content_views($results)
	{
		$content_set = array();
		foreach ($results as $result)
			foreach ($result->content as $content)
				if ($content->is_published)
					$content_set[] = $content;
		
		$stats = new Statistics();
		$stats->hits_for_content_set($content_set);
	}
	
	protected function render_list($chunkination, $results)
	{
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('manage/header');
		$this->load->view('manage/overview/publish/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/overview/publish/list');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}

}

?>