<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class FC_Sites_Controller extends Admin_Base {

	const LISTING_CHUNK_SIZE = 50;
	public $title = 'FC Sites';

	public function index($chunk = 1)
	{
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring('admin/settings/fc_sites/-chunk-');
		$chunkination->set_url_format($url_format);
		$results = $this->fetch_results($chunkination);
		
		// out of bounds so redirect to first
		if ($chunkination->is_out_of_bounds()) 
		{		
			$url = 'admin/settings/fc_sites';
			$this->redirect(gstring($url));
		}
		
		$this->render_list($chunkination, $results);
	}
	
	public function upload()
	{
		$hash = $this->input->post('hash');
		$service = Model_Fin_Service::find($hash);
		if (!$service) return;
		
		$sim = Stored_Image::from_uploaded_file('file');
		if (!$sim->is_valid_image()) return;
			
		$m_im = new Model_Image();
		$m_im->save();
		
		$v_sizes = $this->conf('v_sizes');
		$sim_thumb = $sim->from_this_resized($v_sizes['dist-thumb']);
		$sim_finger = $sim->from_this_resized($v_sizes['dist-finger']);
		$m_im->add_variant($sim_thumb->save_to_db(), 'dist-thumb');
		$m_im->add_variant($sim_finger->save_to_db(), 'dist-finger');
		$service->logo_image_id = $m_im->id;
		$service->save();
	}
	
	protected function fetch_results($chunkination)
	{
		$filter = 1;
		$limit_str = $chunkination->limit_str();		
		$this->vd->filters = array();	
		
		if ($filter_search = $this->input->get('filter_search'))
		{
			$this->create_filter_search($filter_search);
			$filter_fields = array('fs.name', 'fs.url');
			$filter = sql_search_terms($filter_fields, $filter_search);
		}
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM 
			nr_fin_service fs WHERE {$filter} ORDER BY 
			fs.name ASC {$limit_str}";
			
		$query = $this->db->query($sql);
		$results = Model_Fin_Service::from_db_all($query);
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
		$this->load->view('admin/settings/fc_sites');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
}

?>