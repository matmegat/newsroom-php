<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/overview/base');

class Analyze_Controller extends Overview_Base {

	public $title = 'iAnalyze Overview';

	protected $dt_start;
	protected $dt_end;

	const LISTING_CHUNK_SIZE = 5;

	public function index($chunk = 1)
	{		
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring('manage/overview/analyze/-chunk-');
		$chunkination->set_url_format($url_format);
		
		$date_start = $this->input->post('date_start');
		$date_end = $this->input->post('date_end');
		if ($date_start) $this->dt_start = 
			$this->vd->dt_start = new DateTime($date_start);
		if ($date_end) $this->dt_end = 
			$this->vd->dt_end = new DateTime($date_end);		
		if ($this->dt_end && $this->dt_end > Date::$now)
			$this->dt_end = Date::$now;
		
		$results = $this->fetch_results($chunkination);
		$this->fetch_view_data($results);
		$this->render_list($chunkination, $results);
	}
	
	protected function fetch_results($chunkination)
	{
		$limit_str = $chunkination->limit_str();
		$user_id = Auth::user()->id;	
		$fds_date_start = 1;
		$cec_date_start = 1;
		$fds_date_end = 1;
		$cec_date_end = 1;
		
		if ($this->dt_start)
		{
			$date_start = $this->dt_start->format(Date::FORMAT_MYSQL);
			// distributions discovered after this date
			$fds_date_start = "fds.date_discovered >= '{$date_start}'";
			// company email sends counted after this date
			$cec_date_start = "cec.date_sent >= '{$date_start}'";
		}
		
		if ($this->dt_end)
		{
			$date_end = $this->dt_end->format(Date::FORMAT_MYSQL);
			// distributions discovered before this date
			$fds_date_end = "fds.date_discovered <= '{$date_end}'";			
			// company email sends counted before this date
			$cec_date_end = "cec.date_sent <= '{$date_end}'";
		}
		else
		{
			// ensure no distributions before now can be shown
			$fds_date_end = "fds.date_discovered <= UTC_TIMESTAMP()";
		}
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS nc.*, n.*, 
			cec.count AS email_count, ds.total AS dist_count
			FROM nr_newsroom n LEFT JOIN (
			  SELECT n.company_id, 1 AS is_default FROM nr_newsroom n
			  WHERE n.user_id = {$user_id} ORDER BY 
			  n.order_default DESC LIMIT 1
			) AS df ON n.company_id = df.company_id 
			LEFT JOIN nr_newsroom_custom nc
			ON n.company_id = nc.company_id
			LEFT JOIN (
				SELECT SUM(count) AS count, cec.company_id 
				FROM nr_company_email_count cec
				WHERE {$cec_date_start} AND {$cec_date_end}
				GROUP BY cec.company_id
			) AS cec ON n.company_id = cec.company_id
			LEFT JOIN (
				SELECT SUM(1) AS total, 
				fds.attributed_company_id AS company_id
				FROM nr_fin_distribution_service fds WHERE 
				{$fds_date_start} AND {$fds_date_end}
				GROUP BY fds.attributed_company_id
			) ds ON n.company_id = ds.company_id
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
	
	protected function fetch_view_data($results)
	{
		$stats = new Statistics();
		$stats->set_dt_start($this->dt_start);
		$stats->set_dt_end($this->dt_end);
		$stats->hits_for_oa_newsroom_set($results);
	}
	
	protected function render_list($chunkination, $results)
	{
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('manage/header');
		$this->load->view('manage/overview/analyze/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/overview/analyze/list');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}

}

?>