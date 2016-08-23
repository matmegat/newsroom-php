<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class Guides_Controller extends Admin_Base {

	public $title = 'Video Guides';

	public function index()
	{
		$this->vd->results = $guides = 
			Model_Video_Guide::find_all();
		
		$this->load->view('admin/header');
		$this->load->view('admin/settings/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/settings/guides');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
	public function edit($id = null)
	{
		if (!($guide = Model_Video_Guide::find($id)))
			$guide = new Model_Video_Guide();
		$this->vd->guide = $guide;
		
		if ($this->input->post('save'))
		{
			$this->set_redirect('admin/settings/guides');
			$guide->values($this->input->post());
			$guide->date_updated = Date::$now->format(Date::FORMAT_MYSQL);
			$guide->save();
			
			if ($guide->external_video_id)
			{
				$video = Video::get_instance(
					$guide->external_video_provider,
					$guide->external_video_id);
				
				$image_file = $video->save_image();
				if ($image_file !== null)
				{
					$v_size = $this->conf('v_sizes', 'video-guide');
					$im_original = Stored_Image::from_file($image_file);
					$im_thumb = $im_original->from_this_resized($v_size);
					$stored_image_id = $im_thumb->save_to_db();
					$guide->stored_image_id = $stored_image_id;
					$guide->save();
				}
			}
			
			// load feedback message for the user
			$feedback_view = 'admin/settings/partials/guide_save_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/settings/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/settings/guide-edit');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
	public function delete($id)
	{
		if (!($guide = Model_Video_Guide::find($id)))
			$this->redirect('admin/settings/guides');
		
		$this->vd->is_delete = true;
		$this->vd->guide = $guide;
		
		if ($this->input->post('confirm'))
		{
			$this->set_redirect('admin/settings/guides');
			$guide->delete();
			
			// load feedback message for the user
			$feedback_view = 'admin/settings/partials/guide_delete_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/settings/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/settings/guide-edit');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}

}

?>