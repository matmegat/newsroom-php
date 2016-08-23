<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Video_Guide_Record_Controller extends Manage_Base {

	public function index($section)
	{
		$sql = "INSERT IGNORE INTO nr_video_guide_record
			(user_id, section) VALUES (?, ?)";
			
		$params = array(Auth::user()->id, $section);
		$this->db->query($sql, $params);
	}

}

?>