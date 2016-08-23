<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Video_Guide_Thumbnail_Controller extends CLI_Base {
	
	public function index()
	{
		// the size of the thumbnail 
		$v_size = $this->conf('v_sizes', 'video-guide');		
		set_time_limit(300);
		
		$sql = "SELECT * FROM nr_video_guide 
			WHERE external_video_id IS NOT NULL
			AND stored_image_id IS NULL";
		
		$dbr = $this->db->query($sql);		
		$all = Model_Video_Guide::from_db_all($dbr);
		
		foreach ($all as $video_guide)
		{
			$video = Video::get_instance(
				$video_guide->external_video_provider,
				$video_guide->external_video_id);
			
			$image_file = $video->save_image();
			if ($image_file === null)
				continue;
			
			$im_original = Stored_Image::from_file($image_file);
			$im_thumb = $im_original->from_this_resized($v_size);
			$stored_image_id = $im_thumb->save_to_db();
			$video_guide->stored_image_id = $stored_image_id;
			$video_guide->save();
						
			echo $im_thumb->url();
			echo PHP_EOL;
		}
	}
	
}

?>