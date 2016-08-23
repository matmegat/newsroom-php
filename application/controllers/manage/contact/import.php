<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Import_Controller extends Manage_Base {
	
	const CSV_PREVIEW_COUNT = 5;
	
	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iContact';
		$this->vd->title[] = 'Import Contacts';
	}
	
	public function index()
	{
		$company_id = $this->newsroom->company_id;
		
		$vd = array();
		$vd['lists_allow_create'] = true;
		$vd['lists'] = Model_Contact_List::find_all(
			array('company_id', $company_id), 
			array('name', 'asc'));
		
		$recent_tags = Model_Contact::recent_tags($company_id, 5);
		$this->vd->recent_tags = $recent_tags;
		
		$this->load->view('manage/header');
		$this->load->view('manage/contact/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/contact/import', $vd);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function store_csv()
	{
		$company_id = $this->newsroom->company_id;
		$file = Stored_File::from_uploaded_file('csv');
		if (!$file->exists()) $this->redirect('manage/contact/import');
		$file->move();
		
		$stored_file_id = $file->save_to_db();		
		$csv = new CSV_Reader($file->destination);
		$limit = static::CSV_PREVIEW_COUNT;
		$contacts = array();
		
		while ($row = $csv->read())
		{
			$contact = Model_Contact::from_csv_row($company_id, $row);
			if (!$contact) continue;
			$contacts[] = $contact;
			if (count($contacts) === $limit)
				break;
		}
		
		$csv->close();
		
		$this->vd->results = $contacts;
		$view = 'manage/contact/import-preview';
		$preview = $this->load->view($view, null, true);
		
		$response = array(
			'filename' => $file->filename,
			'stored_file_id' => $stored_file_id,
			'preview' => $preview,
		);
		
		return $this->json($response);
	}
	
	public function progress()
	{
		$count = $this->session->read('import_csv_count');
		return $this->json($count);
	}
	
	public function save()
	{
		$post = $this->input->post();
		$company_id = $this->newsroom->company_id;
		$stored_file_id = $this->input->post('stored_file_id');
		if (!$stored_file_id) $this->redirect('manage/contact/import');
		
		$file = Stored_File::from_db($stored_file_id);
		if (!$file) $this->redirect('manage/contact/import');
		if ($file->filename != $this->input->post('filename'))
			$this->denied();
		
		$tags = explode(',', $post['tags']);
		$lists = array();
		
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
			$list->date_created = Date::$now->format(DATE::FORMAT_MYSQL);
			$list->company_id = $company_id;
			$list->name = $name;
			$list->save();			
			$lists[] = $list->id;
		}
		
		$this->session->write('import_csv_count', 0);
		$this->session->commit();
		$csv = new CSV_Reader($file->source);
		$count = 0;
		
		while ($row = $csv->read())
		{
			$contact = Model_Contact::from_csv_row($company_id, $row);
			if (!$contact) continue;
			$contact->save();
			$count++;
			
			if ($contact->id) 
			{
				$contact->add_lists($lists);
				$contact->add_tags($tags);
			}
			else
			{
				$contact->set_lists($lists);
				$contact->set_tags($tags);	
			}
			
			if ($count % 100 == 0)
			{
				$this->session->write('import_csv_count', $count);
				$this->session->commit();
			}
		}
		
		$csv->close();
		
		// load feedback message for the user
		$feedback_view = 'manage/contact/partials/contact_import_feedback';
		$feedback = $this->load->view($feedback_view, array('count' => $count), true);
		$this->add_feedback($feedback);
		
		// redirect back to the contacts list
		$redirect_url = 'manage/contact/contact';
		$this->set_redirect($redirect_url);
	}
	
}

?>