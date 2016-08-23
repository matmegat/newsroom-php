<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/contact/listing');

class List_Controller extends Listing_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iContact';
		$this->vd->title[] = 'Lists';
	}
	
	public function index($chunk = 1)
	{
		$this->load->view('manage/header');
		$this->load->view('manage/contact/menu');
		$this->load->view('manage/pre-content');
		
		$company_id = $this->newsroom->company_id;
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS cl.*, 
			ct.count_contacts, 
			ca.name AS last_campaign_name
			FROM nr_contact_list cl
			LEFT JOIN nr_campaign ca 
			ON cl.last_campaign_id = ca.id
			LEFT JOIN (
				SELECT COUNT(*) as count_contacts, x.contact_list_id 
				FROM nr_contact_list_x_contact x
				GROUP BY contact_list_id
			) ct ON cl.id = ct.contact_list_id
			WHERE cl.company_id = ?
			ORDER BY cl.id ASC
			{$limit_str}";
		
		$query = $this->db->query($sql, 
			array($company_id));
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format   = 'manage/contact/list/-chunk-';
		$listing_view = 'manage/contact/list';
		
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$this->load->view($listing_view);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit($contact_list_id, $chunk = 1)
	{
		$list = Model_Contact_List::find($contact_list_id);
		if (!$list) $this->redirect('manage/contact/list');
		$contact_list_id = (int) $contact_list_id;
		$company_id = $this->newsroom->company_id;
		$this->vd->list = $list;
		$this->title = $list->name;
		
		if ($list)
		{
			if ($list->company_id != $company_id)
				$this->denied();
			
			if ($this->process_selected($list)) return;
			
			$chunkination = new Chunkination($chunk);
			$limit_str = $chunkination->limit_str();
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS c.*, 
				b1.name AS beat_1_name, 
				b2.name AS beat_2_name, 
				b3.name AS beat_3_name
				FROM nr_contact c 
				INNER JOIN nr_contact_list_x_contact x
				ON c.id = x.contact_id AND x.contact_list_id = ?
				LEFT JOIN nr_beat b1 ON c.beat_1_id = b1.id
				LEFT JOIN nr_beat b2 ON c.beat_2_id = b2.id
				LEFT JOIN nr_beat b3 ON c.beat_3_id = b3.id
				ORDER BY c.first_name ASC, 
				c.last_name ASC
				{$limit_str}";
			
			$query = $this->db->query($sql, 
				array($contact_list_id));
			
			$results = array();
			foreach ($query->result() as $result)
				$results[] = $result;
			
			$total_results = $this->db
				->query("SELECT FOUND_ROWS() AS count")
				->row()->count;
			
			$url_format = "manage/contact/list/edit/{$contact_list_id}/-chunk-";						
			$chunkination->set_url_format($url_format);
			$chunkination->set_total($total_results);
			$this->vd->chunkination = $chunkination;
			$this->vd->results = $results;
			
			$lists = Model_Contact_List::find_all(array('company_id', 
				$this->newsroom->company_id), array('name', 'asc'));
			$this->vd->lists = $lists;
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/contact/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/contact/list-edit');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function rename($contact_list_id)
	{
		if (!$contact_list_id) return;
		$list = Model_Contact_List::find($contact_list_id);
		$company_id = $this->newsroom->company_id;
		if (!$list || $list->company_id != $company_id)
			$this->denied();
		
		$list->name = $this->input->post('name');
		$list->save();
		$this->json(true);
	}
	
	public function edit_save()
	{
		$post = $this->input->post();
		$company_id = $this->newsroom->company_id;
		$contact_list_id = value_or_null($post['contact_list_id']);
		
		foreach ($post as &$data)
			$data = value_or_null($data);
		
		$list = Model_Contact_List::find($contact_list_id);
		if ($list && $list->company_id != $company_id)
			$this->denied();
		
		if (!$list && !$this->input->post('name'))
			$this->redirect('manage/contact/list');
		
		if (!$list) $list = new Model_Contact_List();
		$list->values($post);		
		$list->date_created = Date::$now->format(DATE::FORMAT_MYSQL);
		$list->company_id = $company_id;
		$list->save();
		
		// load feedback message for the user
		$feedback_view = 'manage/contact/partials/list_save_feedback';
		$feedback = $this->load->view($feedback_view, array('contact' => $contact), true);
		$this->add_feedback($feedback);
		
		// redirect back to the list of lists
		$redirect_url = "manage/contact/list/edit/{$list->id}";
		$this->set_redirect($redirect_url);
	}
	
	public function delete($contact_list_id)
	{
		if (!$contact_list_id) return;
		$list = Model_Contact_List::find($contact_list_id);
		$company_id = $this->newsroom->company_id;
		
		if ($list && $list->company_id != $company_id)
			$this->denied();
		
		if ($this->input->post('confirm'))
		{
			$list->delete();
			
			// load feedback message 
			$feedback_view = 'manage/contact/partials/list_delete_after_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
			
			// redirect back to type specific listing
			$redirect_url = 'manage/contact/list/';
			$this->set_redirect($redirect_url);
		}
		else
		{
			// load confirmation feedback 
			$this->vd->contact_list_id = $contact_list_id;
			$this->vd->compact_list = true;
			$feedback_view = 'manage/contact/partials/list_delete_before_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			$this->edit($contact_list_id);
		}
	}
	
	public function download($contact_list_id = null)
	{
		$list = Model_Contact_List::find($contact_list_id);
		$company_id = $this->newsroom->company_id;
		if (!$list || $list->company_id != $company_id)
			$this->denied();
			
		$csv = new CSV_Writer('php://memory');		
		$sql = "SELECT co.email, co.first_name, co.last_name, 
			co.company_name, co.title, co.twitter 
			FROM nr_contact_list_x_contact x 
			INNER JOIN nr_contact co ON x.contact_id = co.id
			WHERE x.contact_list_id = ?";
				
		$query = $this->db->query($sql, array($list->id));
		foreach ($query->result_array() as $row)
			$csv->write($row);
		
		$handle = $csv->handle();
		rewind($handle);
		
		$this->load->helper('download');
		force_download('contacts.csv', stream_get_contents($handle));
		return;
	}
	
}

?>