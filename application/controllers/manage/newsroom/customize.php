<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Customize_Controller extends Manage_Base {

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iNewsroom';
		$this->vd->title[] = 'Customize';
	}
	
	public function index()
	{
		$company_id = $this->newsroom->company_id;		
		$custom = Model_Newsroom_Custom::find($company_id);
		$this->vd->custom = $custom;
		
		$this->load->view('manage/header');
		$this->load->view('manage/newsroom/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/newsroom/customize');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function save()
	{
		if (!($post = $this->input->post())) return;
		foreach ($post as &$data)
			$data = value_or_null($data);
		
		// checkbox so won't post on unchecked
		if (empty($post['use_white_header']))
			$post['use_white_header'] = 0;
		
		$color_fields = array(
			'back_color',			
			'link_color', 
			'link_hover_color', 
			'logo_back_color',
			'text_color', 	
			'header_color',
		);
		
		$pattern = '#^(transparent|\#[a-f0-9]{6})$#s';
		foreach ($color_fields as $field) 
		{
			if (!empty($post[$field]))
			{
				$post[$field] = strtolower($post[$field]);
				if (!preg_match($pattern, $post[$field]))
					$post[$field] = null;
			}
		}
					
		$company_id = $this->newsroom->company_id;		
		$custom = Model_Newsroom_Custom::find($company_id);
		if (!$custom) $custom = new Model_Newsroom_Custom();
		$custom->company_id = $company_id;
		$custom->values($post);			
		
		if ($timezone = @$post['timezone'])
		{
			$timezones = DateTimeZone::listIdentifiers();
			if (in_array($timezone, $timezones))
				$this->newsroom->timezone = $timezone;
		}
		
		if ($this->input->post('is_preview'))
		{
			Detached::reset();
			Detached::write('nr_custom', $custom);
			Detached::write('newsroom', $this->newsroom);
			$preview_url = Detached::url();
			$this->redirect($preview_url, false);
		}
		else
		{
			if (Auth::is_admin_controlled())
			{
				$domain = $this->input->post('newsroom_domain');
				if (!$domain) $this->newsroom->domain = null;				
				else if ($domain != $this->newsroom->domain)
				if (!$this->newsroom->set_domain($domain))
				{
					// load feedback message for the user
					$feedback_view = 'manage/newsroom/partials/cannot_set_domain';
					$feedback = $this->load->view($feedback_view, null, true);
					$this->add_feedback($feedback);
				}
			}
			
			$this->newsroom->save();
			$custom->save();
			
			// redirect back to this page after
			$redirect_url = 'manage/newsroom/customize';
			
			// load feedback message for the user
			$feedback_view = 'manage/newsroom/partials/save_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
		
			// update the dashboard progress bar 
			Model_Bar::done('dashboard', 'customize');
			
			// change the newsroom name and redirect
			$name = Model_Newsroom::normalize_name($post['name']);
			if ($name && $name != $this->newsroom->name && 
			    Model_Newsroom::name_available($name))
			{
				$this->newsroom->name = $name;
				$this->newsroom->save();
				$new_url = $this->newsroom->url($redirect_url);
				$this->redirect($new_url, false);
			}
		
			// redirect back to the company details
			$this->set_redirect($redirect_url);
		}
	}
	
	public function name_test()
	{
		$res = new stdClass();
		$name = $this->input->post('name');
		$name = Model_Newsroom::normalize_name($name);
		$res->value = $name;
		
		if ($name == $this->newsroom->name)
		{
			$res->available = true;
			return $this->json($res);
		}
		
		$res->available = Model_Newsroom::name_available($name);
		return $this->json($res);
	}
	
	public function defaults()
	{
		// update the dashboard progress bar 
		Model_Bar::done('dashboard', 'customize');
		
		// delete the newsroom customization
		$company_id = $this->newsroom->company_id;
		$custom = Model_Newsroom_Custom::find($company_id);
		if ($custom) $custom->delete();
		$custom = new Model_Newsroom_Custom();
		$custom->company_id = $company_id;
		$custom->save();
		
		// load feedback message for the user
		$feedback_view = 'manage/newsroom/partials/save_feedback';
		$feedback = $this->load->view($feedback_view, null, true);
		$this->add_feedback($feedback);			
			
		// redirect back to this page after
		$url = 'manage/newsroom/customize';
		$this->redirect($url);
	}
	
}

?>