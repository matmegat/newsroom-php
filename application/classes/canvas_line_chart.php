<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Canvas_Line_Chart extends Line_Chart {
	
	public $colors;
	public $curve;
	public $expires;
	public $font;
	public $font_size;
	public $grid_size;
	public $height;
	public $point_size;
	public $width;
	
	public function __construct($data, $width = 500, $height = 200)
	{
		parent::__construct($data, $width, $height);
		$this->font_size = 10;
		$this->curve = true;
	}
	
	public function get_css_color($name)
	{
		$color = $this->colors->$name;
		return sprintf('rgba(%d, %d, %d, %.2f)',
			$color[0], $color[1],
			$color[2], (1 - ($color[3] / 128)));
	}
	
	public function render()
	{
		$data = new stdClass();
		$data->labels = array();
		$data->points = array();
		
		foreach ($this->data as $item)
		{
			$data->labels[] = $item->label;
			$data->points[] = $item->value;
		}
		
		$ci =& get_instance();
		$view_data = array('options' => $this, 'data' => $data);
		return $ci->load->view('manage/partials/canvas-line-chart', 
			$view_data, true);
	}
	
}

?>