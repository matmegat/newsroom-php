<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Line_Chart {
	
	public $colors;
	public $expires;
	public $font;
	public $font_size;
	public $grid_size;
	public $height;
	public $point_size;
	public $width;
	
	public function __construct($data, $width = 500, $height = 200)
	{
		$this->colors         = new stdClass();
		$this->colors->grid   = array(245, 245, 255, 0);
		$this->colors->step   = array(215, 215, 215, 0);
		$this->colors->font   = array(110, 110, 110, 0);
		$this->colors->line   = array(228, 93, 43, 0);
		$this->colors->fill   = array(251, 154, 6, 90);
		$this->colors->point  = array(228, 93, 43, 0);
		$this->font           = 'assets/other/arial.ttf';
		$this->width          = $width;
		$this->height         = $height;
		$this->data           = $data;
		$this->expires        = 7200;
		$this->point_size		 = 8;
		$this->font_size      = 8;
		$this->grid_size      = 5;
	}
	
	public function render()
	{
		if ($this->point_size)
		{
			// renders points with anti-aliasing. 
			require_once 'application/helpers/image_smooth_arc.php';
		}
		
		$width = $this->width;
		$height = $this->height;
		$grid_size = $this->grid_size;
		$font_size = $this->font_size;
		$point_size = $this->point_size;
		$days = count($this->data);
		$chart_data = $this->data;
		$font_file = $this->font;
		
		$im = imagecreatetruecolor($width, $height);
		$back_color = imagecolorallocate($im, 255, 255, 255);
		imagefill($im, 0, 0, $back_color);
		imagealphablending($im, true);
		imageantialias($im, true);
		
		$grid_color = imagecolorallocatealpha($im, 
			$this->colors->grid[0], $this->colors->grid[1], 
			$this->colors->grid[2], $this->colors->grid[3]);	
				
		$step_color = imagecolorallocatealpha($im, 
			$this->colors->step[0], $this->colors->step[1], 
			$this->colors->step[2], $this->colors->step[3]);
		
		$font_color = imagecolorallocatealpha($im, 
			$this->colors->font[0], $this->colors->font[1], 
			$this->colors->font[2], $this->colors->font[3]);
		
		$line_color = imagecolorallocatealpha($im, 
			$this->colors->line[0], $this->colors->line[1], 
			$this->colors->line[2], $this->colors->line[3]);
		
		$fill_color = imagecolorallocatealpha($im, 
			$this->colors->fill[0], $this->colors->fill[1], 
			$this->colors->fill[2], $this->colors->fill[3]);
		
		// uses custom smooth arc function
		$point_color = $this->colors->point; 
		
		if ($grid_size > 0)
		{
			for ($x = $grid_size; $x < $width; $x += $grid_size)
				imageline($im, $x, 0, $x, $height, $grid_color);
			for ($y = $grid_size; $y < $height; $y += $grid_size)
				imageline($im, 0, $y, $width, $y, $grid_color);
		}
		
		$max_value = 0;
		$min_value = PHP_INT_MAX;
		
		foreach ($chart_data as $item)
		{
			$max_value = max($max_value, $item->value);
			$min_value = min($min_value, $item->value);
		}
		
		$rel_step = pow(10, (strlen((string) $max_value) - 1));
		$min_step = $min_value - ($min_value % $rel_step);
		$max_step = $rel_step;
		
		// increase max step until sufficient margin
		while ($max_step < ($max_value + $rel_step))
			$max_step += $rel_step;
		
		// decrease min step until sufficient margin
		while ($min_step > ($min_value - $rel_step))
			$min_step -= $rel_step;
		
		if ($min_step < 0)
			$min_step = 0;
		
		// case when we have no results
		if ($max_step < 3 && $min_step === 0)
		{
			$min_step = -1;
			$max_step = 3;
			$rel_step = 1;
		}
		
		for ($step_i = 1; $step_i <= 3; $step_i++)
		{
			$step_percent = ($step_i / 4);
			$y = $height - round($height * $step_percent);
			imageline($im, 0, $y, $width, $y, $step_color);
		}
		
		$last_x_center = 0;
		$last_y_center = 0;
		$stored_points = array(0, $height);
		
		for ($i = 0; $i < $days; $i++)
		{
			$item = $chart_data[$i];
			$rel_value = (($item->value - $min_step) / ($max_step - $min_step));
			$y_center = round($height - ($rel_value * $height)) - 1;
			$x_center = round($width * ($i / ($days - 1))) - 1;
			
			if ($i > 0)	
				imageline($im, $last_x_center, $last_y_center, 
					$x_center, $y_center, $line_color);
			
			$last_x_center = $x_center;
			$last_y_center = $y_center;
			
			$stored_points[] = $x_center;
			$stored_points[] = $y_center;
		}
		
		$stored_points[] = $width;
		$stored_points[] = $height;
		imagefilledpolygon($im, $stored_points,
			($days + 2), $fill_color);
		
		for ($i = 1; $this->point_size && $i < $days - 1; $i++)
		{
			$x_center = $stored_points[$i*2] - 1;
			$y_center = $stored_points[$i*2+1] - 1;
			
			if ($y_center > ($height - $point_size - 5))
				continue;
			
			imagesmootharc($im, $x_center, $y_center, $point_size,
				$point_size, $point_color, 0, (2 * M_PI));
		}
		
		for ($step_i = 1; $step_i <= 3; $step_i++)
		{
			$step_percent = ($step_i / 4);
			$y = $height - round($height * $step_percent);
			$label = $min_step + (int) ($step_percent * ($max_step - $min_step));
			$bounds = imagettfbbox($font_size, 0, $font_file, $label);
			imagettftext($im, $font_size, 0, 5, (5 + $y + (-$bounds[5])), 
				$font_color, $font_file, $label);
		}
				
		$bounds = imagettfbbox($font_size, 0, $font_file, $max_step);
		imagettftext($im, $font_size, 0, 5, (5 + (-$bounds[5])), 
			$font_color, $font_file, $max_step);
		
		$label_inc = floor($width / 100) + 2;
		$label_inc = ceil($days / $label_inc);
		
		for ($i = $label_inc; $i < ($days - 1); $i += $label_inc)
		{
			$item = $chart_data[$i];
			$label = $item->label;
			$x_center = round($width * ($i / ($days - 1))) - 1;
			$bounds = imagettfbbox($font_size, 0, $font_file, $label);
			$x_left = $x_center - ($bounds[4] / 2);
			imagettftext($im, $font_size, 0, $x_left, ($height - 5), 
				$font_color, $font_file, $label);
		}
		
		ob_clean();
		$ci =& get_instance();
		$ci->expires($this->expires);
		header("Content-Type: image/png");
		imagepng($im);
		exit;
	}
	
}

?>