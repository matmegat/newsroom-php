<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contact_Unsubscribe_Controller extends CIL_Controller {
	
	public function index()
	{
		$id = $this->input->get('id');
		$data = $this->input->get('data');
		
		if (!$id) return;
		if (!$data) return;		
		$contact = Model_Contact::find($id);
		if (!$contact) return;		
		$newsroom = Model_Newsroom::find_company_id($contact->company_id);
		if (!$newsroom) return;
		
		$this->vd->newsroom = $newsroom;
		$this->vd->contact = $contact;
		
		if ($this->input->post('confirm'))
		{
			$result = $contact->unsubscribe($data);
			$this->vd->result = $result;
			
			$this->load->view('common/header');
			$this->load->view('common/unsubscribe_status');
			$this->load->view('common/footer');
		}
		else
		{
			$this->load->view('common/header');
			$this->load->view('common/unsubscribe');
			$this->load->view('common/footer');
		}
	}
	
}

?>