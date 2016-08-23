<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Modal {
	
	public $auto_show = false;
	public $content;
	public $id;
	public $title;
	
	public function __construct()
	{
		$this->id = substr(md5(microtime()), 0, 8);
		$this->id = "m{$this->id}";
	}
	
	public function set_content($content)
	{
		$this->content = $content;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function auto_show($auto_show)
	{
		$this->auto_show = $auto_show;
	}
	
	public function render($width, $height)
	{
		$view_data = array();
		$view_data['width'] = (int) $width;
		$view_data['height'] = (int) $height;
		$view_data['content'] = $this->content;
		$view_data['title'] = $this->title;
		$view_data['as'] = $this->auto_show;
		$view_data['id'] = $this->id;
		$ci =& get_instance();
		return $ci->load->view('shared/partials/modal.php',
			$view_data, true);
	}
	
}

?>