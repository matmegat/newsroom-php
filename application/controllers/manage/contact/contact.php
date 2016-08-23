<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/contact/listing');

class Contact_Controller extends Listing_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iContact';
		$this->vd->title[] = 'Contacts';
	}
	
	public function email_check()
	{
		$company_id = $this->newsroom->company_id;
		$email = $this->input->post('email');
		$email = strtolower(trim($email));
		$contact = Model_Contact::find_match($company_id, $email);
		$this->json(array('available' => (!$contact || 
			$contact->id == $this->input->post('contact_id'))));
	}
	
	public function index($chunk = 1)
	{
		if ($this->process_selected()) return;
		
		$this->load->view('manage/header');
		$this->load->view('manage/contact/menu');
		$this->load->view('manage/pre-content');
		
		$company_id = $this->newsroom->company_id;
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS c.*, 
			b1.name AS beat_1_name, 
			b2.name AS beat_2_name, 
			b3.name AS beat_3_name
			FROM nr_contact c 
			LEFT JOIN nr_beat b1 ON c.beat_1_id = b1.id
			LEFT JOIN nr_beat b2 ON c.beat_2_id = b2.id
			LEFT JOIN nr_beat b3 ON c.beat_3_id = b3.id
			WHERE c.company_id = ?
			ORDER BY c.first_name ASC, 
			c.last_name ASC
			{$limit_str}";
		
		$query = $this->db->query($sql, 
			array($company_id));
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format   = 'manage/contact/contact/-chunk-';
		$listing_view = 'manage/contact/contact';
		
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$lists = Model_Contact_List::find_all(array('company_id', 
			$this->newsroom->company_id), array('name', 'asc'));
		$this->vd->lists = $lists;
		
		$this->load->view($listing_view);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_from($list_id)
	{
		$company_id = $this->newsroom->company_id;
		$list = Model_Contact_List::find($list_id);
		if ($list && $list->company_id == $company_id)
			$this->vd->from_m_contact_list = $list;
		$this->edit();
	}
	
	public function edit($contact_id = null)
	{
		if ($contact_id)
			  $this->vd->title[] = 'Edit Contact';
		else $this->vd->title[] = 'New Contact';
		
		$contact = Model_Contact::find($contact_id);
		$company_id = $this->newsroom->company_id;
		$this->vd->contact = $contact;
		
		if ($contact && $contact->company_id != $company_id)
			$this->denied();
		
		$vd = array();
		$vd['lists_allow_create'] = true;
		$vd['beats'] = Model_Beat::list_all_beats_by_group();
		$vd['lists'] = Model_Contact_List::find_all(
			array('company_id', $company_id), 
			array('name', 'asc'));
			
		$order = array('name', 'asc');
		$criteria = array('is_common', 1);
		$vd['common_countries'] = Model_Country::find_all($criteria, $order);
		$vd['countries'] = Model_Country::find_all(null, $order);
		
		$this->vd->related_lists = $contact ? 
			$contact->get_lists() : array();
		
		$recent_tags = Model_Contact::recent_tags($company_id, 5);
		$this->vd->recent_tags = $recent_tags;
		
		$this->load->view('manage/header');
		$this->load->view('manage/contact/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/contact/contact-edit', $vd);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_save()
	{
		$post = $this->input->post();
		$company_id = $this->newsroom->company_id;
		$contact_id = value_or_null($post['contact_id']);
		$post['email'] = strtolower(trim($post['email']));
		
		foreach ($post as &$data)
			$data = value_or_null($data);
		
		$contact = Model_Contact::find($contact_id);
		if ($contact && $contact->company_id != $company_id)
			$this->denied();
		
		if (!$contact) $contact = new Model_Contact();
		$contact->values($post);		
		$contact->company_id = $company_id;
		$contact->save();
		
		$lists = array();		
		$tags = explode(',', $post['tags']);
		
		foreach ((array) @$post['lists'] as $contact_list_id)
		{
			if (!$contact_list_id) continue;
			if (!($list = Model_Contact_List::find($contact_list_id))) continue;
			if ($list->company_id != $company_id) continue;
			$lists[] = $list;
		}
		
		foreach ((array) @$post['create_lists'] as $name)
		{
			if (!($name = trim($name))) continue;
			$list = new Model_Contact_List();
			$list->company_id = $company_id;
			$list->date_created = Date::$now->format(DATE::FORMAT_MYSQL);
			$list->name = $name;
			$list->save();			
			$lists[] = $list->id;
		}
		
		$contact->set_lists($lists);
		$contact->set_tags($tags);
		
		// load feedback message for the user
		$feedback_view = 'manage/contact/partials/contact_save_feedback';
		$feedback = $this->load->view($feedback_view, array('contact' => $contact), true);
		$this->add_feedback($feedback);
		
		// redirect back to the contacts list
		$redirect_url = 'manage/contact/contact';
		$this->set_redirect($redirect_url);
	}
	
	public function search($chunk = 1)
	{
		if ($this->process_selected()) return;
		
		$terms = $this->input->get('terms');
		$terms_sql = sql_search_terms(array('c.first_name', 
			'c.last_name', 'c.company_name', 'c.email', 't.tags', 
			'b1.name', 'b2.name', 'b3.name'), $terms);
		
		$this->load->view('manage/header');
		$this->load->view('manage/contact/menu');
		$this->load->view('manage/pre-content');
		
		$company_id = $this->newsroom->company_id;
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS c.*,
			b1.name AS beat_1_name, 
			b2.name AS beat_2_name, 
			b3.name AS beat_3_name
			FROM nr_contact c 
			LEFT JOIN nr_beat b1 ON c.beat_1_id = b1.id
			LEFT JOIN nr_beat b2 ON c.beat_2_id = b2.id
			LEFT JOIN nr_beat b3 ON c.beat_3_id = b3.id
			LEFT JOIN (
				SELECT t.contact_id, GROUP_CONCAT(t.value) AS tags
				FROM nr_contact_tag t GROUP BY t.contact_id
			) t ON t.contact_id = c.id
			WHERE c.company_id = ? AND {$terms_sql}
			ORDER BY c.first_name ASC,
			c.last_name ASC
			{$limit_str}";
		
		$query = $this->db->query($sql, 
			array($company_id));
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format   = 'manage/contact/contact/search/-chunk-';
		$listing_view = 'manage/contact/contact-search';
		$url_format   = gstring($url_format);
		
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$lists = Model_Contact_List::find_all(array('company_id', 
			$this->newsroom->company_id), array('name', 'asc'));
		$this->vd->lists = $lists;
		
		$this->load->view($listing_view);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function delete($contact_id)
	{
		if (!$contact_id) return;
		$contact = Model_Contact::find($contact_id);
		$company_id = $this->newsroom->company_id;
		
		if ($contact && $contact->company_id != $company_id)
			$this->denied();
		
		if ($this->input->post('confirm'))
		{
			$contact->delete();
			
			// load feedback message 
			$feedback_view = 'manage/contact/partials/contact_delete_after_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
			
			// redirect back to type specific listing
			$redirect_url = 'manage/contact/contact/';
			$this->set_redirect($redirect_url);
		}
		else
		{
			// load confirmation feedback 
			$this->vd->contact_id = $contact_id;
			$feedback_view = 'manage/contact/partials/contact_delete_before_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			$this->edit($contact_id);
		}
	}
	
	public function download()
	{
		$csv = new CSV_Writer('php://memory');
		$sql = "SELECT co.email, co.first_name, co.last_name, 
			co.company_name, co.title, co.twitter 
			FROM nr_contact co WHERE co.company_id = ?";
				
		$query = $this->db->query($sql, array($this->newsroom->company_id));
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