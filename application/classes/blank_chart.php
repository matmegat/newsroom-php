<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blank_Chart {
	
	public $colors;
	public $expires;
	public $font;
	public $font_size;
	public $grid_size;
	public $height;
	public $width;
	
	public function __construct($width = 500, $height = 200)
	{
		$this->colors         = new stdClass();
		$this->colors->grid   = array(245, 245, 255, 0);
		$this->colors->font   = array(200, 200, 200, 0);
		$this->font           = 'assets/other/arial.ttf';
		$this->width          = $width;
		$this->height         = $height;
		$this->expires        = 7200;
		$this->font_size      = 24;
		$this->grid_size      = 5;
	}
	
	public function render()
	{
		$width = $this->width;
		$height = $this->height;
		$grid_size = $this->grid_size;
		$font_size = $this->font_size;
		$font_file = $this->font;
		
		$im = imagecreatetruecolor($width, $height);
		$back_color = imagecolorallocate($im, 255, 255, 255);
		imagefill($im, 0, 0, $back_color);
		imagealphablending($im, true);
		imageantialias($im, true);
		
		$grid_color = imagecolorallocatealpha($im, 
			$this->colors->grid[0], $this->colors->grid[1], 
			$this->colors->grid[2], $this->colors->grid[3]);	
		
		$font_color = imagecolorallocatealpha($im, 
			$this->colors->font[0], $this->colors->font[1], 
			$this->colors->font[2], $this->colors->font[3]);
		
		if ($grid_size > 0)
		{
			for ($x = $grid_size; $x < $width; $x += $grid_size)
				imageline($im, $x, 0, $x, $height, $grid_color);
			for ($y = $grid_size; $y < $height; $y += $grid_size)
				imageline($im, 0, $y, $width, $y, $grid_color);
		}
		
		$label = "Chart Unavailable";
		$bounds = imagettfbbox($font_size, 0, $font_file, $label);
		$x = ($width / 2) - ($bounds[4] / 2);
		$y = ($height / 2) - ($bounds[5] / 2);
		imagettftext($im, $font_size, 0, $x, $y, 
			$font_color, $font_file, $label);
		
		ob_clean();
		$ci =& get_instance();
		$ci->expires($this->expires);
		header("Content-Type: image/png");
		imagepng($im);
		exit;
	}
	
}

?>