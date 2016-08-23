<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Contact_Controller extends Manage_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iNewsroom';
		$this->vd->title[] = 'Company Contacts';
	}
	
	public function main() 
	{
		$this->edit($this->newsroom->company_contact_id);
	}
	
	protected function process_results($results)
	{
		$mapping = array();
		$image_ids = array();
		
		foreach ($results as $result)
		{
			if (($image_id = (int) $result->image_id))
				$image_ids[] = $image_id;
		}
		
		if (!count($image_ids))
			return $results;
		
		$in_str = implode(',', $image_ids);
		$sql = "SELECT iv.image_id, si.filename
			FROM nr_image_variant iv INNER JOIN nr_stored_image si 
			ON iv.stored_image_id = si.id WHERE iv.image_id 
			IN ({$in_str}) AND iv.name = 'finger'";			
				
		$query = $this->db->query($sql);
		foreach ($query->result() as $result)
			$mapping[(int) $result->image_id] = $result;
		
		foreach ($results as $result)
		{
			if (!($image_id = (int) $result->image_id)) continue;
			$result->image_url = Stored_Image::url_from_filename(	
				$mapping[$image_id]->filename);
		}
		
		return $results;
	}

	public function index($chunk = 1)
	{
		$this->load->view('manage/header');
		$this->load->view('manage/newsroom/menu');
		$this->load->view('manage/pre-content');
		
		$company_id = $this->newsroom->company_id;
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		$main_contact_id = (int) $this->newsroom->company_contact_id;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * 
			FROM nr_company_contact cc
			WHERE cc.company_id = ?
			ORDER BY cc.id = {$main_contact_id} DESC, 
			cc.name ASC {$limit_str}";
		
		$query = $this->db->query($sql, 
			array($company_id));
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format   = 'manage/newsroom/contact/-chunk-';
		$listing_view = 'manage/newsroom/contact';
		
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $this->process_results($results);
		
		$this->load->view($listing_view);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit($contact_id = null)
	{
		$company_id = $this->newsroom->company_id;
		
		$contact = Model_Company_Contact::find($contact_id);	
		if ($contact && $contact->company_id != $company_id) 
			$this->denied();
		$this->vd->contact = $contact;
		
		if ($contact && $contact->name)
			  $this->title = $contact->name;
		else if ($contact) $this->title = 'Edit Contact';
		else $this->title = 'Add Contact';
		
		if ($contact)
		{	
			if ($image = Model_Image::find($contact->image_id))
				$this->vd->image = $image;	
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/newsroom/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/newsroom/contact-edit');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_save()
	{
		$post = $this->input->post();
		$company_id = $this->newsroom->company_id;
		$contact_id = value_or_null($post['contact_id']);
		$post['email'] = strtolower($post['email']);
		$post['description'] = $this->vd->pure($post['description']);
		
		foreach ($post as &$data)
			$data = value_or_null($data);
		
		$contact = Model_Company_Contact::find($contact_id);
		if ($contact && $contact->company_id != $company_id)
			$this->denied();
		
		if (!$contact) $contact = new Model_Company_Contact();
		$contact->values($post);
		
		if ($contact->linkedin)
		{
			$parsed = Social_Linkedin_Profile::parse_id($contact->linkedin);
			if ($parsed) $contact->linkedin = $parsed;
		}
		
		if ($contact->twitter)
		{
			$parsed = Social_Twitter_Profile::parse_id($contact->twitter);
			if ($parsed) $contact->twitter = $parsed;
		}
		
		if ($contact->facebook)
		{
			$parsed = Social_Facebook_Profile::parse_id($contact->facebook);
			if ($parsed) $contact->facebook = $parsed;
		}
		
		$contact->company_id = $company_id;
		
		if ($post['is_main_contact'])
		{
			// update the dashboard progress bar 
			Model_Bar::done('dashboard', 'add-press-contact');
			
			if (!$contact->id) $contact->save();
			$this->newsroom->company_contact_id = $contact->id;
		}
		else if ($contact->id == $this->newsroom->company_contact_id)
		{
			$this->newsroom->company_contact_id = null;
		}
		
		if (($image = Model_Image::find($contact->image_id)))
			if ($image->company_id != $company_id) $this->denied();
		
		if ($this->input->post('is_preview'))
		{
			Detached::reset();
			if ($post['is_main_contact'])
				Detached::write('nr_contact', $contact);
			Detached::write('m_contact', $contact);
			Detached::write('newsroom', $this->newsroom);
			
			$preview_url = "view/contact/{$contact->id}";
			$preview_url = Detached::url($preview_url);
			$this->redirect($preview_url, false);
		}
		else
		{
			// load feedback message for the user
			$feedback_view = 'manage/newsroom/partials/contact_save_feedback';
			$feedback = $this->load->view($feedback_view, array('contact' => $contact), true);
			$this->add_feedback($feedback);
		
			$this->newsroom->save();
			$contact->save();
		}
		
		// redirect back to the company details
		$redirect_url = 'manage/newsroom/contact';
		$this->set_redirect($redirect_url);
	}
	
	public function search($chunk = 1)
	{
		$terms = $this->input->get('terms');
		$terms_sql = sql_search_terms(array('cc.name', 
			'cc.title', 'cc.email'), $terms);
		
		$this->load->view('manage/header');
		$this->load->view('manage/newsroom/menu');
		$this->load->view('manage/pre-content');
		
		$company_id = $this->newsroom->company_id;
		$chunkination = new Chunkination($chunk);
		$limit_str = $chunkination->limit_str();
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * 
			FROM nr_company_contact cc
			WHERE cc.company_id = ? AND {$terms_sql}
			ORDER BY cc.name ASC 
			{$limit_str}";
		
		$query = $this->db->query($sql, 
			array($company_id));
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format   = 'manage/newsroom/contact/search/-chunk-';
		$listing_view = 'manage/newsroom/search';
		$url_format   = gstring($url_format);
		
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $this->process_results($results);
		
		$this->load->view($listing_view);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function delete($contact_id)
	{
		if (!$contact_id) return;
		$contact = Model_Company_Contact::find($contact_id);
		$company_id = $this->newsroom->company_id;
		
		if ($contact && $contact->company_id != $company_id)
			$this->denied();
		
		if ($this->input->post('confirm'))
		{
			if ($this->newsroom->company_contact_id == $contact->id)
			{
				$this->newsroom->company_contact_id = null;
				$this->newsroom->save();
			}
			
			$contact->delete();
			
			// load feedback message 
			$feedback_view = 'manage/newsroom/partials/contact_delete_after_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
			
			// redirect back to type specific listing
			$redirect_url = 'manage/newsroom/contact/';
			$this->set_redirect($redirect_url);
		}
		else
		{
			// load confirmation feedback 
			$this->vd->contact_id = $contact_id;
			$feedback_view = 'manage/newsroom/partials/contact_delete_before_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			$this->edit($contact_id);
		}
	}
	
}

?>