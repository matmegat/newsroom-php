<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Listing_Base extends Manage_Base { 
	
	protected function process_selected($context_list = null)
	{
		$selected = $this->input->post('selected');
		if (!is_array($selected)) return false;
		$this->vd->selected = $selected;
		
		if ($this->input->post('add_to_list'))
			if ($this->add_to_list_selected(array_keys($selected)))
				return true;
		
		if ($this->input->post('remove_from_list') && $context_list)
			if ($this->remove_from_list_selected($context_list, array_keys($selected)))
				return true;
		
		if ($this->input->post('delete'))
			if ($this->delete_selected(array_keys($selected)))
				return true;
		
		return false;
	}
	
	protected function remove_from_list_selected($list, $selected)
	{
		$company_id = $this->newsroom->company_id;
		
		if (!$list) return;
		if ($list->company_id != $company_id)
			$this->denied();
		
		$this->vd->act_list = $list;
		foreach ($selected as $contact_id)
		{
			$contact = Model_Contact::find($contact_id);
			if (!$contact) continue;
			if ($contact->company_id != $company_id)
				continue;
			
			$list->remove_contact($contact);
		}
		
		// load feedback message 
		$feedback_view = 'manage/contact/partials/multi_remove_from_list_feedback';
		$feedback = $this->load->view($feedback_view, null, true);
		$this->use_feedback($feedback);
	}
	
	protected function add_to_list_selected($selected)
	{
		$contact_list_id = $this->input->post('contact_list_id');
		$list = Model_Contact_List::find($contact_list_id);
		$company_id = $this->newsroom->company_id;
		
		if (!$list) return;
		if ($list->company_id != $company_id)
			$this->denied();
		
		$this->vd->act_list = $list;		
		foreach ($selected as $contact_id)
		{
			$contact = Model_Contact::find($contact_id);
			if (!$contact) continue;
			if ($contact->company_id != $company_id)
				continue;
			
			$list->add_contact($contact);
		}
		
		// load feedback message 
		$feedback_view = 'manage/contact/partials/multi_add_to_list_feedback';
		$feedback = $this->load->view($feedback_view, null, true);
		$this->use_feedback($feedback);
	}
	
	protected function delete_selected($selected)
	{		
		if ($this->input->post('confirm'))
		{
			$company_id = $this->newsroom->company_id;
			foreach ($selected as $contact_id)
			{
				$contact = Model_Contact::find($contact_id);
				if (!$contact) continue;
				if ($contact->company_id != $company_id)
					continue;
				
				$contact->delete();
			}
			
			// load feedback message 
			$feedback_view = 'manage/contact/partials/contact_multi_delete_after_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
		}
		else
		{
			// load confirmation feedback 
			$feedback_view = 'manage/contact/partials/contact_multi_delete_before_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			
			$this->load->view('manage/header');
			$this->load->view('manage/contact/menu');
			$this->load->view('manage/pre-content');
		
			$company_id = $this->newsroom->company_id;
			
			foreach ($selected as &$contact_id)
				$contact_id = (int) $contact_id;
			$selected_str = implode(',', $selected);
		
			$sql = "SELECT SQL_CALC_FOUND_ROWS c.* 
				FROM nr_contact c 
				WHERE c.company_id = ?
				AND c.id IN ({$selected_str})
				ORDER BY c.first_name ASC, 
				c.last_name ASC";
		
			$query = $this->db->query($sql, 
				array($company_id));
			
			$results = array();
			foreach ($query->result() as $result)
				$results[] = $result;
		
			$chunkination = new Chunkination(1);
			$chunkination->set_total(count($results));
			$chunkination->set_chunk_size(count($results));
			$this->vd->chunkination = $chunkination;
			$this->vd->results = $results;
			$this->vd->compact_list = true;
		
			$this->load->view('manage/contact/partials/contact_listing');
			$this->load->view('manage/post-content');
			$this->load->view('manage/footer');
			return true;
		}
	}
	
}

?>