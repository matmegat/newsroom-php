<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/publish/content');

class PR_Controller extends Content_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iPublish';
		$this->vd->title[] = 'Press Releases';
	}

	public function index()
	{
		$this->redirect('manage/publish/pr/all');
	}
	
	public function under_review($chunk = 1)
	{
		$filter = 'c.is_under_review = 1';
		$this->listing($chunk, 'under_review',
			Model_Content::TYPE_PR, $filter);
	}
	
	public function edit($content_id = null)
	{		
		$vars = parent::edit($content_id);
		extract($vars, EXTR_SKIP);
		
		if (!Model_Company_Profile::find($this->newsroom->company_id))
		{
			// load profile warning message
			$feedback_view = 'manage/publish/partials/feedback/profile_warning';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/publish/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/publish/pr-edit');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_save()
	{
		$vars = parent::edit_save('pr');
		extract($vars, EXTR_SKIP);
		
		$cat_1_id = value_or_null($post['category'][0]);
		$cat_2_id = value_or_null($post['category'][1]);
		$cat_3_id = value_or_null($post['category'][2]);
		
		$stored_file_id_1 = value_or_null($post['stored_file_id_1']);
		$stored_file_id_2 = value_or_null($post['stored_file_id_2']);
		$stored_file_name_1 = value_or_null($post['stored_file_name_1']);
		$stored_file_name_2 = value_or_null($post['stored_file_name_2']);
		
		if ($stored_file_name_1)
		{
			$stored_file_name_1 = str_replace('\\', '/', $stored_file_name_1);
			$stored_file_name_1 = basename($stored_file_name_1);
		}
		
		if ($stored_file_name_2)
		{
			$stored_file_name_2 = str_replace('\\', '/', $stored_file_name_2);
			$stored_file_name_2 = basename($stored_file_name_2);
		}
		
		$web_video_provider = value_or_null($post['web_video_provider']);
		$web_video_id = value_or_null($post['web_video_id']);
		$is_valid_video = false;
		
		if ($web_video_provider && $web_video_id)
		{
			$provider = Video::get_instance($web_video_provider);			
			if ($provider !== null) 
			{
				$video_id = $provider->parse_video_id($web_video_id);
				if ($video_id !== null) 
				{
					$web_video_id = $video_id;
					$is_valid_video = true;
				}
			}
		}
		
		if (!$is_valid_video)
		{
			$web_video_provider = null;
			$web_video_id = null;
		}

		if ($is_preview)
		{
			$m_content = Detached::read('m_content');
			$m_content->cat_1_id = $cat_1_id;
			$m_content->cat_2_id = $cat_2_id;
			$m_content->cat_3_id = $cat_3_id;
			$m_content->web_video_provider = $web_video_provider;
			$m_content->web_video_id = $web_video_id;
			$m_content->stored_file_id_1 = $stored_file_id_1;
			$m_content->stored_file_id_2 = $stored_file_id_2;
			$m_content->stored_file_name_1 = $stored_file_name_1;
			$m_content->stored_file_name_2 = $stored_file_name_2;
			$m_content->set_tags((array) $tags);
			$m_content->set_images((array) $images);
			Detached::write('m_content', $m_content);
			return;
		}
		else
		{
			if ($is_new_content)
			     $m_pb_pr = new Model_PB_PR();
			else $m_pb_pr = Model_PB_PR::find($m_content->id);
			
			if ($is_new_scheduled && 
			    $dt_date_publish > Date::days(1))
			{
				$sch_n = new Model_Scheduled_Notification();
				$sch_n->related_id = $m_content->id;
				$sch_n->class = Model_Scheduled_Notification::CLASS_CONTENT_SCHEDULED;
				$sch_n->user_id = Auth::user()->id;
				$sch_n->save();
			}
			
			$m_pb_pr->cat_1_id = $cat_1_id;
			$m_pb_pr->cat_2_id = $cat_2_id;
			$m_pb_pr->cat_3_id = $cat_3_id;
			$m_pb_pr->web_video_provider = $web_video_provider;
			$m_pb_pr->web_video_id = $web_video_id;
			$m_pb_pr->stored_file_id_1 = $stored_file_id_1;
			$m_pb_pr->stored_file_id_2 = $stored_file_id_2;
			$m_pb_pr->stored_file_name_1 = $stored_file_name_1;
			$m_pb_pr->stored_file_name_2 = $stored_file_name_2;
			$m_pb_pr->content_id = $m_content->id;
			$m_pb_pr->save();
		}
		
		if (!$m_content->is_draft && !$m_content->is_published)
		{
			$available_credits = $m_content->is_premium
				? Auth::user()->pr_credits_premium()
				: Auth::user()->pr_credits_basic();
				
			if (!$available_credits)
			{
				// load feedback message for the user
				$feedback_view = 'manage/publish/partials/feedback/save_low_credits_warning';
				$feedback = $this->load->view($feedback_view, null, true);
				$this->add_feedback($feedback);
			}
		}
		
		// update the dashboard progress bar 
		Model_Bar::done('dashboard', 'pr-submission');
	}
	
}

?>