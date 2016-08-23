<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Image {
	
	const FORMAT_GIF 	  = 'gif';
	const FORMAT_JPEG   = 'jpg';
	const FORMAT_PNG 	  = 'png';	
	
	const MIME_GIF 	  = 'image/gif';
	const MIME_JPEG 	  = 'image/jpeg';
	const MIME_PNG 	  = 'image/png';
	
	const JPEG_QUALITY  = 90;
	
	protected $__im_source;
	protected $__im_result;
	
	protected $__width_source;
	protected $__width_result;
	protected $__width_cropped;
	
	protected $__height_source;	
	protected $__height_result;	
	protected $__height_cropped;
	
	protected $__format = self::FORMAT_JPEG;	
	protected $__cropped = false;
	
	protected $__max_ratio_diff_exceeded = false;
	protected $__max_ratio_diff_margin = 0;
	protected $__max_ratio_diff = false;
	protected $__max_height = false;
	protected $__max_width = false;
	
	protected static $__supported_mime_types = array(
		
		self::MIME_GIF    => 'imagecreatefromgif',
		self::MIME_JPEG   => 'imagecreatefromjpeg',
		self::MIME_PNG    => 'imagecreatefrompng',
		
	);
	
	protected static $__supported_save_formats = array(
		
		self::FORMAT_GIF   => 'imagegif',
		self::FORMAT_JPEG  => 'imagejpeg',
		self::FORMAT_PNG   => 'imagepng',
		
	);
	
	public static function is_valid_file($filename)
	{
		$im = static::from_file($filename);
		return $im->is_valid();
	}
	
	public static function from_file($filename)
	{
		if (!is_file($filename))
			return new static();
		
		$mime = File_Util::detect_mime($filename);
		if (!isset(static::$__supported_mime_types[$mime]))
			return new static();
		
		$image = new static();
		$handler = static::$__supported_mime_types[$mime];
		$image->__im_source = call_user_func_array($handler, array($filename));
		$image->__width_source = imagesx($image->__im_source);
		$image->__height_source = imagesy($image->__im_source);
		return $image;
	}
	
	public function is_valid()
	{
		return $this->__im_source && 
			$this->__width_source &&
			$this->__height_source;
	}
	
	public function width($value = null)
	{
		if ($value === null)
			return $this->__width_source;		
		$this->__width_result = $value;
		return $this;
	}
	
	public function height($value = null)
	{
		if ($value === null)
			return $this->__height_source;
		$this->__height_result = $value;
		return $this;
	}
	
	public function cropped($cropped)
	{
		$this->__cropped = $cropped;
		return $this;
	}
	
	public function im_source()
	{
		return $this->__im_source;
	}
	
	public function im_result()
	{
		return $this->__im_result;
	}
	
	public function format($value = null)
	{
		if ($value === null)
			return $this->__format;
		$this->__format = $value;
		return $this;
	}
	
	public function set($options)
	{
		foreach ($options as $k => $v)
			if (property_exists($this, "__{$k}"))
				$this->{"__{$k}"} = $v;
	}
		
	protected function calc_dimensions()
	{
		// no dimensions => use source dimensions
		if (!$this->__width_result && !$this->__height_result)
		{
			$this->__width_result = $this->__width_source; 
			$this->__height_result = $this->__height_source;
			return;
		}
		
		// no height => calculate
		if ($this->__width_result && !$this->__height_result)
		{
			$ratio = $this->__width_result / $this->__width_source;
			$this->__height_result = (int) ($ratio * $this->__height_source);
			
			if ($this->__max_height && $this->__height_result > $this->__max_height)
			{
				$this->__width_cropped = $this->__width_result;
				$this->__height_cropped = $this->__max_height;
				$this->__cropped = true;
			}
			
			return;
		}
		
		// no width => calculate
		if ($this->__height_result && !$this->__width_result)
		{
			$ratio = $this->__height_result / $this->__height_source;
			$this->__width_result = (int) ($ratio * $this->__width_source);
			
			if ($this->__max_width && $this->__width_result > $this->__max_width)
			{
				$this->__width_cropped = $this->__max_width;
				$this->__height_cropped = $this->__height_result;
				$this->__cropped = true;
			}
			
			return;
		}
		
		$ratio_width = $this->__width_result / $this->__width_source;
		$ratio_height = $this->__height_result / $this->__height_source;
		
		if ($this->__cropped)
		{			
			$ratio_source = $this->__width_source / $this->__height_source;
			$ratio_desired = $this->__width_result / $this->__height_result;
			$ratio_diff = abs($ratio_source - $ratio_desired);
			
			$this->__width_cropped = $this->__width_result;
			$this->__height_cropped = $this->__height_result;
			
			if ($this->__max_ratio_diff !== false && $ratio_diff > $this->__max_ratio_diff)
			{
				if ($ratio_source > $ratio_desired)
				{
					$this->__width_result = $this->__width_cropped;
					$this->__height_result = $ratio_width * $this->__height_source;
				}
				else
				{
					$this->__height_result = $this->__height_cropped;
					$this->__width_result = $ratio_height * $this->__width_source;					
				}
				
				$this->__width_result -= ($this->__max_ratio_diff_margin * 2);
				$this->__height_result -= ($this->__max_ratio_diff_margin * 2);
				$this->__max_ratio_diff_exceeded = true;
				return;
			}			
			
			if ($ratio_source > $ratio_desired)
			{
				$this->__width_result = $ratio_height * $this->__width_source;
				return;
			}
			
			if ($ratio_source < $ratio_desired)
			{
				$this->__height_result = $ratio_width * $this->__height_source;
				return;
			}
		}
		
		if ($ratio_width < $ratio_height)
		{
			$this->__width_result = (int) ($ratio_width * $this->__width_source);
			$this->__height_result = (int) ($ratio_width * $this->__height_source);
		}
		else
		{
			$this->__width_result = (int) ($ratio_height * $this->__width_source);
			$this->__height_result = (int) ($ratio_height * $this->__height_source);
		}
	}
	
	protected function execute_crop()
	{
		if (!$this->__cropped) return;
	
		$im_resized = $this->__im_result;
		$this->__im_result = 
			imagecreatetruecolor($this->__width_cropped, 
			$this->__height_cropped);
		
		if ($this->__max_ratio_diff_exceeded)
		{
			$this->initial_fill();
			$tx_width = $this->__width_result;
			$tx_height = $this->__height_result;
			$dst_x = floor(($this->__width_cropped - $this->__width_result) / 2);
			$dst_y = floor(($this->__height_cropped - $this->__height_result) / 2);
			$src_x = 0;
			$src_y = 0;
		}
		else
		{
			$tx_width = $this->__width_cropped;
			$tx_height = $this->__height_cropped;
			$src_x = floor(($this->__width_result - $this->__width_cropped) / 2);			
			$src_y = floor(($this->__height_result - $this->__height_cropped) / 2);	
			$dst_x = 0;
			$dst_y = 0;
		}
		
		imagecopy($this->__im_result, 
			$im_resized, $dst_x, $dst_y, $src_x, $src_y,
			$tx_width, $tx_height);
	}
	
	protected function initial_fill()
	{
		if ($this->__format === static::FORMAT_JPEG)
		{
			$white = imagecolorallocate($this->__im_result, 255, 255, 255);
			imagefill($this->__im_result, 0, 0, $white);
			imagesavealpha($this->__im_result, false);
		}
		else
		{
			$transparent = imagecolorallocatealpha($this->__im_result, 0, 0, 0, 127);
			imagefill($this->__im_result, 0, 0, $transparent);
			imagesavealpha($this->__im_result, true);
		}
	}
		
	public function execute()
	{
		$this->calc_dimensions();	
		
		if ($this->__width_result <= 1)
			$this->__width_result = 1;
		if ($this->__height_result <= 1)
			$this->__height_result = 1;
		
		$this->__im_result = 
			imagecreatetruecolor($this->__width_result, 
			$this->__height_result);
		$this->initial_fill();
		
		imagecopyresampled(
			$this->__im_result, 
			$this->__im_source, 0, 0, 0, 0, $this->__width_result,
			$this->__height_result, $this->__width_source, 
			$this->__height_source);
		
		$this->execute_crop();
		
		return $this->__im_result;
	}
	
	public function save($filename, $quality = null)
	{
		$this->execute();
		
		$format = $this->__format;
		if ($format === null) $format = static::FORMAT_JPEG;		
		if ($quality === null && $format === static::FORMAT_JPEG)
			$quality = static::JPEG_QUALITY;
		
		if (!isset(static::$__supported_save_formats[$format]))
			throw new Image_Format_Exception();
		
		$args = array();
		$args[] = $this->__im_result;
		$args[] = $filename;		
		if ($quality !== null)
			$args[] = $quality;
		
		$handler = static::$__supported_save_formats[$format];
		call_user_func_array($handler, $args);
	}
	
}



?>