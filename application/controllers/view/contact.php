<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('browse/base');

class Contact_Controller extends Browse_Base {

	public function index($contact_id = null)
	{
		$company_id = (int) $this->newsroom->company_id;
		$m_contact = Model_Company_Contact::find($contact_id);
		$this->vd->m_contact = $m_contact;
		
		if ($this->is_detached_host && 
		    $contact = Detached::read('m_contact'))
		{
			$m_contact = $contact;
			$this->vd->m_contact = $m_contact;
			Detached::reset();
		}
		
		if (!$m_contact) show_404();
		if ((int) $m_contact->company_id !== $company_id)
			show_404();
		
		$this->title = $m_contact->name;		
		$this->load->view('browse/header');
		$this->load->view('browse/view-contact');
		$this->load->view('browse/footer');
	}

}

?>