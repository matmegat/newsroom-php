<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class IP_Block_Controller extends Admin_Base {

	const LISTING_CHUNK_SIZE = 50;	
	public $title = 'IP Block';

	public function index($chunk = 1)
	{
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring('admin/settings/ip_block/-chunk-');
		$chunkination->set_url_format($url_format);
		$results = $this->fetch_results($chunkination);
		
		// out of bounds so redirect to first
		if ($chunkination->is_out_of_bounds()) 
		{		
			$url = 'admin/settings/ip_block';
			$this->redirect(gstring($url));
		}
		
		$this->render_list($chunkination, $results);
	}
	
	public function delete()
	{
		$addr = $this->input->post('addr');
		if ($blocked = Model_Blocked::find($addr))
			$blocked->delete();
		$this->redirect('admin/settings/ip_block');
	}
	
	public function add()
	{
		$this->set_redirect('admin/settings/ip_block');
		$addr = $this->input->post('addr');
		
		if (!$addr && $user = $this->input->get('user'))
		{
			$user = Model_User::find($user);
			$addr = $user->remote_addr;
		}
		
		if (!$addr)	return;
		$blocked = new Model_Blocked();
		$blocked->addr = $addr;
		$blocked->save();
	}
	
	protected function fetch_results($chunkination)
	{
		$filter = 1;
		$limit_str = $chunkination->limit_str();		
		$this->vd->filters = array();	
		
		if ($filter_search = $this->input->get('filter_search'))
		{
			$this->create_filter_search($filter_search);
			$filter = sql_search_terms(array('b.addr'), $filter_search);
		}
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM 
			nr_blocked b WHERE {$filter} ORDER BY 
			b.date_blocked DESC {$limit_str}";
			
		$query = $this->db->query($sql);
		$results = Model_Blocked::from_db_all($query);			
		$chunkination->set_total($this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count);
					
		return $results;
	}
	
	protected function render_list($chunkination, $results)
	{
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view('admin/header');
		$this->load->view('admin/settings/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/settings/ip_block');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
}

?>