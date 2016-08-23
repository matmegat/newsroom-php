<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('browse/base');

class View_Controller extends Browse_Base {

	public function index($slug = null)
	{
		if ($slug === null)
			$this->redirect('browse');
		
		$m_content = null;
		if ($this->is_detached_host)
		{
			$m_content = Detached::read('m_content');
			if ($m_content) Detached::reset();
		}
		
		if (!$m_content) $m_content = Model_Content::find('slug', $slug);
		if (!$m_content) show_404();
		
		if ($this->is_common_host)
		{
			$real_newsroom = Model_Newsroom::find($m_content->company_id);
			if (!$real_newsroom) show_404();
			
			if (Auth::is_admin_online() 
			    && Auth::user()->id != $real_newsroom->user_id)
				Auth::admo($real_newsroom->user_id);
				
			if ($real_newsroom->is_active) 
			{
				$relative_url = $this->uri->uri_string();
				$url = $real_newsroom->url($relative_url, true);
				$url = gstring($url);
				$this->redirect($url, false);
			}
			
			if (Auth::is_user_online()
			    && $real_newsroom->user_id == Auth::user()->id
			    && $m_content->type !== Model_Content::TYPE_PR)
			{
				$relative_url = $this->uri->uri_string();
				$url = $real_newsroom->url($relative_url, true);
				$url = gstring($url);
				$this->redirect($url, false);
			}
			
			if ($m_content->type !== Model_Content::TYPE_PR)
				show_404();
			
			$this->newsroom = $real_newsroom;
			$this->vd->nr_custom = $this->newsroom->custom();
			$this->vd->nr_profile = $this->newsroom->profile();
			$this->vd->nr_contact = $this->newsroom->contact();
			$company_id = $m_content->company_id;
		}
		
		$company_id = (int) $this->newsroom->company_id;
		if ((int) $m_content->company_id !== $company_id)
			show_404();
		
		$this->vd->m_content = $m_content;
		$this->title = $m_content->title;
		
		$m_content->load_content_data();
		$m_content->load_local_data();
		
		if (!$m_content->is_published)
		{
			if (!Auth::is_user_online() &&
			     // admo above => never false for admin
			     Auth::user()->id != $this->newsroom->user_id)
				$this->denied();
			
			// load feedback message for the user
			$feedback_view = 'browse/view/partials/not_published_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
		}
		
		// load additional data (if function exists)
		$view_method = "pre_render_{$m_content->type}";
		if (method_exists($this, $view_method))
			call_user_func(array($this, $view_method));
		
		$this->load->view('browse/header');
		$this->load->view('browse/view');
		$this->load->view('browse/footer');	
	}
	
	public function id($id)
	{
		$m_content = Model_Content::find($id);
		if (!$m_content) show_404();
		$this->redirect(gstring($m_content->url()));
	}
	
	public function raw($id)
	{
		if (!$this->is_common_host)
		{
			$url = $this->uri->uri_string;
			$url = $this->common()->url($url);
			$this->redirect($url, false);
		}
			
		$m_content = Model_Content::find($id);
		if (!$m_content) show_404();
		if (!$m_content->is_published ||
		    $m_content->type !== Model_Content::TYPE_PR)
			show_404();
		
		$real_newsroom = Model_Newsroom::find($m_content->company_id);
		if (!$real_newsroom) show_404();
		
		$this->newsroom = $real_newsroom;
		$this->vd->nr_custom = $this->newsroom->custom();
		$this->vd->nr_profile = $this->newsroom->profile();
		$this->vd->nr_contact = $this->newsroom->contact();
		$this->vd->m_content = $m_content;
		$this->title = $m_content->title;
		$m_content->load_content_data();
		$m_content->load_local_data();
		
		$this->load->view('browse/raw');
	}
	
	public function pdf($id)
	{
		$id = (int) $id;
		$name = 'content.pdf';
		$url = $this->common()->url("view/raw/{$id}");
		$report = new Report($url);
		$report->generate();
		$report->deliver($name);
	}
	
	protected function pre_render_pr()
	{
		$this->vd->wide_view = true;
	}

}

?>