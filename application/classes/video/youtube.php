<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Video_Youtube extends Video {
	
	public $video_id;
	public static $provider_name = 'YouTube';
	
	public function __construct($video_id = null)
	{
		$this->video_id = $video_id;
	}
	
	public function parse_video_id($str)
	{
		$str = trim($str);
		
		// the entire string is the video id
		if (preg_match('#^[a-zA-Z0-9_-]{10,20}$#i', $str))
			return $this->video_id = $str;
	
		$url_pattern = 
			# http://stackoverflow.com/questions/3392993
			# http://rubular.com/r/M9PJYcQxRW
			'#(?<=[vi]=)[a-z0-9\-]+(?=(?:[\?\/&\#"\']|$))
			 |(?<=[vi]\/)[a-z0-9\-]+(?=(?:[\?\/&\#"\']|$))
			 |(?<=embed\/)[a-z0-9\-]+(?=(?:[\?\/&\#"\']|$))
			 |(?<=youtu\.be\/)[a-z0-9\-]+(?=(?:[\?\/&\#"\']|$))
			 #ix';
			
		// extract the video id from a video link
		if (preg_match($url_pattern, $str, $match))
			return $this->video_id = $match[0];
		
		return $this->video_id = null;
	}
	
	public function save_image()
	{
		if (!$this->video_id)
			return null;
		
		$id = $this->video_id;
		$versions = array();
		$versions[] = 'maxresdefault';
		$versions[] = 'hqdefault';
		$versions[] = 'default';
		
		$b_file = File_Util::buffer_file();
		$result = false;
		
		foreach ($versions as $version)
		{
			$url = "http://img.youtube.com/vi/{$id}/{$version}.jpg";
			$result = @copy($url, $b_file);
			if ($result) break;
		}
		
		if (!$result) 
			return null;
		
		if (Image::is_valid_file($b_file))
			return $b_file;
		
		return null;
	}
	
	public function data()
	{
		if (!$this->video_id)
			return null;
		
		$data = new stdClass();
		
		$url = "http://gdata.youtube.com/feeds/api/videos/";
		$url = "{$url}{$this->video_id}?v=2&alt=json";
		$raw = @file_get_contents($url);
		$source = @json_decode($raw);
		
		if (!isset($source->entry))
			return null;
		
		$source = $source->entry;
		$data->title = @$source->title->{'$t'};
		$data->author = (string) @$source->author[0]->name->{'$t'};
		$data->published = new DateTime(@$source->published->{'$t'});
		$data->published->setTimezone(Date::$utc);
		$data->views = @$source->{'yt$statistics'}->viewCount;
		$data->dislikes = @$source->{'yt$rating'}->numDislikes;
		$data->likes =@ $source->{'yt$rating'}->numLikes;
		$data->duration = @$source->{'media$group'}->{'yt$duration'}->seconds;
		$data->description = @$source->{'media$group'}->{'media$description'}->{'$t'};
		$data->can_embed = false;
		
		foreach ((array) @$source->{'yt$accessControl'} as $ac)
		{
			if ($ac->action !== 'embed') continue;
			$data->can_embed = $ac->permission === 'allowed';
			break;
		}
	
		return $data;
	}
	
	public function render($width = 640, $height = 360, $options = array())
	{
		if (!$this->video_id)
			return null;
		
		$ci =& get_instance();
		$view_data = array();
		$view_data['width'] = $width;
		$view_data['height'] = $height;
		$view_data['id'] = $this->video_id;
		$view_data['options'] = $options;
		
		return $ci->load->view('partials/video/youtube', 
			$view_data, true);
	}
	
	public function url()
	{
		if (!$this->video_id)
			return null;
		
		$url = "http://www.youtube.com/watch?v={$this->video_id}";
		return $url;
	}
	
}

?>