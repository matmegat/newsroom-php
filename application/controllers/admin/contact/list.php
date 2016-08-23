<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class List_Controller extends Admin_Base {

	const LISTING_CHUNK_SIZE = 20;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->vd->title[] = 'iContact';
		$this->vd->title[] = 'Lists';
	}

	public function index($chunk = 1)
	{
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring('admin/contact/list/-chunk-');
		$chunkination->set_url_format($url_format);
		$results = $this->fetch_results($chunkination);
		
		if ($chunkination->is_out_of_bounds()) 
		{
			// out of bounds so redirect to first
			$url = 'admin/contact/list';
			$this->redirect(gstring($url));
		}
		
		$this->render_list($chunkination, $results);
	}
	
	public function edit($list_id)
	{
		$list = Model_Contact_List::find($list_id);
		if (!$list) $this->redirect('admin/contact/list');
		$url = "manage/contact/list/edit/{$list_id}";
		$this->admin_mode_from_company($list->company_id, $url);
	}
	
	public function delete($list_id)
	{
		$list = Model_Contact_List::find($list_id);
		if (!$list) $this->redirect('admin/contact/list');
		$url = "manage/contact/list/delete/{$list_id}";
		$this->admin_mode_from_company($list->company_id, $url);
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
			$search_fields = array('c.name');
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
		
		if ($filter_company = (int) $this->input->get('filter_company'))
		{
			$this->create_filter_company($filter_company);	
			// restrict search results to this user
			$filter = "{$filter} AND cm.id = {$filter_company}";
			$use_additional_tables = true;
		}
		
		// add sql for connecting in additional tables
		if ($use_additional_tables) $additional_tables = 
			"INNER JOIN nr_company cm ON c.company_id = cm.id
			 INNER JOIN nr_user u ON cm.user_id = u.id";
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS c.id FROM 
			nr_contact_list c {$additional_tables}
			WHERE {$filter} ORDER BY c.id 
			DESC {$limit_str}";
			
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
			
		$sql = "SELECT c.*, clc.count,
 			cm.name AS o_company_name,
			cm.id AS o_company_id,
			u.first_name AS o_user_first_name,
			u.last_name AS o_user_last_name,
			u.email AS o_user_email,
			u.id AS o_user_id
			FROM nr_contact_list c
			LEFT JOIN nr_company cm
			ON c.company_id = cm.id
			LEFT JOIN nr_user u 
			ON cm.user_id = u.id
			LEFT JOIN (
				SELECT x.contact_list_id, count(*) AS count 
				FROM nr_contact_list_x_contact x 
				GROUP BY x.contact_list_id
			) AS clc ON c.id = clc.contact_list_id
			WHERE c.id IN ({$id_str})
			ORDER BY c.id DESC";
			
		$query = $this->db->query($sql);
		$results = Model_Contact_List::from_db_all($query);
		
		return $results;
	}
	
	protected function render_list($chunkination, $results)
	{
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('admin/header');
		$this->load->view('admin/contact/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/contact/list/list');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}

}

?>