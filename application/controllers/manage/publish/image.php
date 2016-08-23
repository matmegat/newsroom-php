<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/publish/content');

class Image_Controller extends Content_Base { 

	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iPublish';
		$this->vd->title[] = 'Images';
	}

	public function index()
	{
		$this->redirect('manage/publish/image/all');
	}
	
	protected function process_results($results)
	{
		if (!count($results))
			return $results;
		
		$mapping = array();
		$image_ids = array();
		foreach ($results as $result)
		{
			$image_id = (int) $result->image_id;
			$mapping[$image_id] = array();
			$image_ids[] = $image_id;			
		}
		
		$in_str = implode(',', $image_ids);
		$sql = "SELECT iv.image_id, iv.name, si.filename, si.width, si.height
			FROM nr_image_variant iv INNER JOIN nr_stored_image si 
			ON iv.stored_image_id = si.id WHERE iv.image_id 
			IN ({$in_str}) AND iv.name = 'finger' 
			OR iv.name = 'original'";
			
				
		$query = $this->db->query($sql);
		foreach ($query->result() as $result)
			$mapping[(int) $result->image_id][$result->name] = $result;
		
		foreach ($results as $result)
		{
			$si = $mapping[(int) $result->image_id];
			$si_original = Stored_Image::from_stored_filename($si['original']->filename);
			$si_finger = Stored_Image::from_stored_filename($si['finger']->filename);
			$result->image_url = $si_finger->url();
			$result->image_size = $si_original->human_size();
			$result->image_width = $si['original']->width;
			$result->image_height = $si['original']->height;
		}
		
		return $results;
	}
	
	public function edit($content_id = null)
	{		
		$vars = parent::edit($content_id);
		extract($vars, EXTR_SKIP);
		
		$vd = array();
		$vd['image'] = null;
		$vd['licenses'] = array();
		$vd['licenses'][] = 'CCA';
		$vd['licenses'][] = 'CCA: Share-alike';
		$vd['licenses'][] = 'CCA: Noncommercial';
		$vd['licenses'][] = 'CCA: No Derivatives';
		$vd['licenses'][] = 'All Rights Reserved';
		
		if ($m_content)
		{
			if ($image = Model_Image::find($m_content->image_id))
				$vd['image'] = $image;
		}
		
		$this->load->view('manage/header');
		$this->load->view('manage/publish/menu');
		$this->load->view('manage/pre-content');
		$this->load->view('manage/publish/image-edit', $vd);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
	public function edit_save()
	{
		$vars = parent::edit_save('image');
		extract($vars, EXTR_SKIP);
		
		$license = value_or_null($post['license']);
		$source = value_or_null($post['source']);
		$image_id = $post['image_id'];
		
		if ($is_preview)
		{
			$m_content = Detached::read('m_content');
			$m_content->license = $license;
			$m_content->source = $source;
			$m_content->image_id = $image_id;
			$m_content->set_tags((array) $tags);
			$m_content->set_images((array) $images);
			Detached::write('m_content', $m_content);
			return;
		}
		else
		{
			if ($is_new_content)
			     $m_pb_image = new Model_PB_Image();
			else $m_pb_image = Model_PB_Image::find($m_content->id);
			
			$m_pb_image->license = $license;
			$m_pb_image->source = $source;
			$m_pb_image->image_id = $image_id;
			$m_pb_image->content_id = $m_content->id;
			$m_pb_image->save();
		}
	}
	
}

?>