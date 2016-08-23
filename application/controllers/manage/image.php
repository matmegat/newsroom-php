<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Image_Controller extends Manage_Base {

	public function upload()
	{
		$response = array();
		$si_original = Stored_Image::from_uploaded_file('image');
		
		if (!$si_original->is_valid_image())
		{
			$response['status'] = false;
			$this->json($response);
			return;
		}
		
		$v_sizes = $this->conf('v_sizes');
		
		$image = new Model_Image();
		$image->company_id = $this->newsroom->company_id;
		$image->save();
		
		$si_original->move();
		
		// if filesize exceeds 512K then save again
		if (($size_limit = $this->input->post('size_limit'))
			&& $si_original->size() > (int) $size_limit)
		{
			$si_smaller = $si_original->from_this_resized();
			
			// new image is smaller than the original
			if ($si_smaller->size() < $si_original->size())
			{
				$si_original->delete();
				$si_original = $si_smaller;
			}
			else
			{
				$si_smaller->delete();
			}
		}		
		
		$image->add_variant($si_original->save_to_db(), 'original');
		
		$im_original = Image::from_file($si_original->actual_filename());
		$im_width = $im_original->width();
		$im_height = $im_original->height();
		
		$response['status'] = true;
		$response['image_id'] = $image->id;
		$response['files'] = array();
		$response['files']['original'] = $si_original->url();
		
		$variants = $this->input->post('variants');
		if (!$variants) $variants = array('thumb','finger','cover');
		
		foreach ($variants as $variant)
		{
			if ($variant === 'original') continue;
			if (!isset($v_sizes[$variant])) continue;
				
			$v_size = $v_sizes[$variant];
			if (isset($v_size->min_width) && $im_width < $v_size->min_width)
				continue;
				
			$si_variant = $si_original->from_this_resized($v_size);
			$image->add_variant($si_variant->save_to_db(), $variant);
			$response['files'][$variant] = $si_variant->url();
		}
		
		$this->json($response);
	}
	
	public function remove()
	{
		$id = (int) $this->input->post('id');
		if ($id === 0) return $this->json(false);
		
		$image = Model_Image::find($id);		
		
		// check we loaded correct image
		if ((int) $image->id !== $id)
			return $this->json(false);
		
		// check that the image belongs to company
		if ((int) $image->company_id !== (int) $this->newsroom->company_id)
			return $this->json(false);
		
		$image->remove();
		$this->json(true);
	}

}

?>