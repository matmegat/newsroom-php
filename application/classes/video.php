<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Video {
	
	public static $provider_name;
	
	const PROVIDER_YOUTUBE = 'Video_Youtube';
	
	public static function providers()
	{
		return array(static::PROVIDER_YOUTUBE);
	}
	
	public static function is_valid_provider($provider)
	{
		$providers = static::providers();
		return in_array($provider, $providers);
	}
	
	public static function get_instance($provider, $video_id = null)
	{
		if (static::is_valid_provider($provider))
		{
			$instance = new $provider;
			$instance->video_id = 
				$instance->parse_video_id($video_id);
			return $instance;
		}
		
		return null;		
	}
	
	public static function get_provider_name($provider)
	{
		if (static::is_valid_provider($provider))
		{
			$name = $provider::$provider_name;
			if (!empty($name)) return $name;
		}
		
		return $provider;	
	}
	
	public static function resolve($provider, $video_id, $download_thumb = false)
	{
		$ci =& get_instance();
		$video = Video::get_instance($provider);
		if ($video === null)
			return null;
		
		$video_id = $video->parse_video_id($video_id);
		if ($video_id === null)
			return null;
		
		$video_data = $video->data();
		$video_image = null;
		
		if ($download_thumb)
			$video_image = $video->save_image();
		
		// both fail => assume an issue
		if ($video_data === null && $video_image === null)
			return null;
		
		if ($download_thumb)
		{
			// the resolved image is not valid so use default
			if (!$video_image || !Image::is_valid_file($video_image))
				$video_image = 'assets/im/failed_video.png';
			
			$v_sizes = $ci->conf('v_sizes'); 
			
			$image = new Model_Image();
			$image->company_id = value_or_null($ci->newsroom->company_id);
			$image->save();
			
			$si_original = Stored_Image::from_file($video_image, 'jpg');
			$si_thumb = $si_original->from_this_resized($v_sizes['thumb']);
			$si_finger = $si_original->from_this_resized($v_sizes['finger']);
			$si_cover = $si_original->from_this_resized($v_sizes['cover']);
			$si_original->move();		
			
			$image->add_variant($si_original->save_to_db(), 'original');
			$image->add_variant($si_thumb->save_to_db(), 'thumb');
			$image->add_variant($si_finger->save_to_db(), 'finger');
			$image->add_variant($si_cover->save_to_db(), 'cover');
		}
		
		$response = array();
		$response['video_id'] = $video_id;
		$response['video_data'] = $video_data;
		
		if ($download_thumb)
		{
			$response['image_id'] = $image->id;
			$response['image_url'] = $si_thumb->url();
		}
						
		return $response;
	}
	
	abstract public function parse_video_id($str);
	abstract public function save_image();
	abstract public function data();
	abstract public function render($width = 640, $height = 360);
	abstract public function url();
	
}

?>