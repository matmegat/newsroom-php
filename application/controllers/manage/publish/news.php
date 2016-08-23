<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/publish/content');

class News_Controller extends Content_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iPublish';
		$this->vd->title[] = 'News';
	}

	public function index()
	{
		$this->redirect('manage/publish/news/all');
	}
	
	public function edit($content_id = null)
	{		
		$vars = parent::edit($content_id);
		extract($vars, EXTR_SKIP);
		
		$this->load->view('manage/header');
		$this->load->view('manage/publish/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/publish/news-edit');
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_save()
	{
		$vars = parent::edit_save('news');
		extract($vars, EXTR_SKIP);
		
		$cat_1_id = value_or_null($post['category'][0]);
		$cat_2_id = value_or_null($post['category'][1]);
		$cat_3_id = value_or_null($post['category'][2]);

		if ($is_preview)
		{
			$m_content = Detached::read('m_content');
			$m_content->cat_1_id = $cat_1_id;
			$m_content->cat_2_id = $cat_2_id;
			$m_content->cat_3_id = $cat_3_id;
			$m_content->set_tags((array) $tags);
			$m_content->set_images((array) $images);
			Detached::write('m_content', $m_content);
			return;
		}
		else
		{
			if ($is_new_content)
			     $m_pb_news = new Model_PB_News();
			else $m_pb_news = Model_PB_News::find($m_content->id);
			
			$m_pb_news->cat_1_id = $cat_1_id;
			$m_pb_news->cat_2_id = $cat_2_id;
			$m_pb_news->cat_3_id = $cat_3_id;
			$m_pb_news->content_id = $m_content->id;
			$m_pb_news->save();
		}
	}
	
}

?>