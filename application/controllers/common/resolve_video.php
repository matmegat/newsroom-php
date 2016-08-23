<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resolve_Video_Controller extends CIL_Controller {
	
	public function index()
	{
		if (!Auth::is_user_online()) return;
		$provider = $this->input->post('external_video_provider');
		$video_id = $this->input->post('external_video_id');
		$this->json(Video::resolve($provider, $video_id, false));
	}
	
}

?>