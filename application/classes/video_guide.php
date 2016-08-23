<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Video_Guide {
		
	public static function has_auto_show_enabled($section)
	{	
		$ci =& get_instance();
		$recorded = $ci->session->get('video_guide_record');
		if (isset($recorded[$section]))
			return false;		
		
		$sql = "SELECT 1 FROM nr_video_guide_record 
			WHERE user_id = ? AND section = ?";
		
		$params = array(Auth::user()->id, $section);
		return ! $ci->db->query($sql, $params)->num_rows();
	}

	public static function render() 
	{
		$ci =& get_instance();
		
		$section = $ci->uri->segment(2);
		if ($section === 'overview')
			$section = $ci->uri->segment(3);
		
		$criteria = array('section', $section);
		$videos = Model_Video_Guide::find_all($criteria);
		if (!$videos) return null;
		
		$ci->vd->videos = $videos;
		$ci->vd->section = $section;
		$ci->vd->auto_show = static::has_auto_show_enabled($section);	
		
		$session =& $ci->session->reference();
		if (!isset($session['video_guide_record']))
			$session['video_guide_record'] = array();
		$session['video_guide_record'][$section] = 1;
			
		return $ci->load->view('manage/partials/video_guide_modal.php');
	}
	
}

?>