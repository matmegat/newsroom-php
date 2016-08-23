<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class Companies_Controller extends Admin_Base {

	const LISTING_CHUNK_SIZE = 20;	
	protected $use_archived = false;
	public $title = 'Companies';

	public function index($status = null, $chunk = 1)
	{
		if ($status === 'all') $filter = 1;
		else if ($status === 'basic')
			$filter = 'n.is_active = 0 && (n.is_archived = 0 || n.is_legacy = 1)';
		else if ($status === 'newsroom')
			$filter = 'n.is_active = 1';
		else if ($status === 'archived')
			$filter = 'n.is_archived = 1 && n.is_legacy = 0';
		else $this->redirect(gstring('admin/companies/all'));
		
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring("admin/companies/{$status}/-chunk-");
		$chunkination->set_url_format($url_format);
		$results = $this->fetch_results($chunkination, $filter);
		
		// out of bounds so redirect to first
		if ($chunkination->is_out_of_bounds()) 
		{		
			$url = "admin/companies/{$status}";
			$this->redirect(gstring($url));
		}
		
		$this->vd->status = $status;
		$this->render_list($chunkination, $results);
	}
	
	public function view($company_id)
	{
		$newsroom = Model_Newsroom::find($company_id);
		if (!$newsroom) $this->redirect('admin/companies');
		$this->admin_mode_from_company($company_id, 'manage');
	}
	
	protected function fetch_results($chunkination, $filter = null)
	{
		if (!$filter) $filter = 1;
		$limit_str = $chunkination->limit_str();
		$use_additional_tables = false;
		$additional_tables = null;
		$this->vd->filters = array();	
		
		if ($filter_search = $this->input->get('filter_search'))
		{
			$this->create_filter_search($filter_search);
			// restrict search results to these terms
			$search_fields = array('n.company_name', 'n.name');
			$terms_filter = sql_search_terms($search_fields, $filter_search);
			$filter = "{$filter} AND {$terms_filter}";
		}
		
		if ($filter_user = (int) $this->input->get('filter_user'))
		{
			$this->create_filter_user($filter_user);	
			// restrict search results to this user
			$filter = "{$filter} AND u.id = {$filter_user}";
			$use_additional_tables = true;
		}
		
		// add sql for connecting in additional tables
		if ($use_additional_tables) $additional_tables = 
			"INNER JOIN nr_user u ON n.user_id = u.id";
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
			n.company_id AS id FROM 
			nr_newsroom n {$additional_tables}
			WHERE {$filter} ORDER BY 
			n.company_id DESC {$limit_str}";
			
		$query = $this->db->query($sql);
		$id_list = array();
		foreach ($query->result() as $row)
			$id_list[] = (int) $row->id;
		
		// no results found so exit
		if (!$id_list) return array();
				
		$id_str = sql_in_list($id_list);
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
			
		$chunkination->set_total($total_results);
		if ($chunkination->is_out_of_bounds())
			return array();
			
		$sql = "SELECT n.*,
			n.company_id AS id,
			u.first_name AS o_user_first_name,
			u.last_name AS o_user_last_name,
			u.email AS o_user_email,
			u.id AS o_user_id
			FROM nr_newsroom n
			LEFT JOIN nr_user u 
			ON n.user_id = u.id
			WHERE n.company_id IN ({$id_str})
			ORDER BY n.company_id DESC";
			
		$query = $this->db->query($sql);
		$results = Model_Newsroom::from_db_all($query);		
		
		return $results;
	}
	
	protected function render_list($chunkination, $results)
	{
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('admin/header');
		$this->load->view('admin/companies/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/companies/list');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
}

?>