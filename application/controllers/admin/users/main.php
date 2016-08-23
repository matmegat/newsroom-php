<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class Main_Controller extends Admin_Base {

	const LISTING_CHUNK_SIZE = 20;	
	public $title = 'Users';

	public function index($status = null, $chunk = 1)
	{			
		if ($status === 'all') $filter = 1;
		else if ($status === 'reseller')
			$filter = 'u.is_reseller = 1';
		else if ($status === 'admin')
			$filter = 'u.is_admin = 1';
		else $this->redirect(gstring('admin/users/all'));
		
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring("admin/companies/{$status}/-chunk-");
		$chunkination->set_url_format($url_format);
		$results = $this->fetch_results($chunkination, $filter);
		
		// out of bounds so redirect to first
		if ($chunkination->is_out_of_bounds()) 
		{		
			$url = "admin/users/{$status}";
			$this->redirect(gstring($url));
		}
		
		$this->vd->status = $status;
		$this->render_list($chunkination, $results);
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
			$search_fields = array('u.first_name', 'u.last_name', 'u.email');
			$terms_filter = sql_search_terms($search_fields, $filter_search);
			$filter = "{$filter} AND {$terms_filter}";
		}
		
		// add sql for connecting in additional tables
		// if ($use_additional_tables) $additional_tables = ;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS u.id FROM 
			nr_user u {$additional_tables}
			WHERE {$filter} ORDER BY 
			u.id DESC {$limit_str}";
			
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
			
		$sql = "SELECT u.*
			FROM nr_user u
			WHERE u.id IN ({$id_str})
			ORDER BY u.id DESC";
			
		$query = $this->db->query($sql);
		$results = Model_User::from_db_all($query);
		
		return $results;
	}
	
	protected function render_list($chunkination, $results)
	{
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('admin/header');
		$this->load->view('admin/users/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/users/list');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
}

?>	