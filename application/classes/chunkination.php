<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chunkination {
	
	private $chunk_size = 10;
	private $chunk;
	private $total;
	private $url_format;
	
	public function __construct($chunk)
	{
		if (!$chunk) $chunk = 1;
		$this->chunk = $chunk - 1;
		$this->total = 0;
	}
	
	public function set_chunk_size($value)
	{
		$this->chunk_size = $value;
	}
	
	public function set_url_format($value)
	{
		$this->url_format = $value;
	}
	
	public function set_total($value)
	{
		$this->total = $value;
	}
	
	public function offset()
	{
		return $this->chunk * $this->chunk_size;
	}
	
	public function chunk_size()
	{
		return $this->chunk_size;
	}
	
	public function total()
	{
		return $this->total;
	}
	
	public function is_out_of_bounds()
	{
		if ($this->chunk === 0) return false;
		if ($this->chunk < 0) return true;
		if ($this->offset() >= $this->total) return true;
		return false;
	}
	
	public function limit_str()
	{
		$offset = $this->offset();
		if ($offset < 0) return "LIMIT 0";
		return "LIMIT {$offset}, {$this->chunk_size}";
	}
	
	public function render($template = null)
	{
		if ($template === null)
			$template = 'partials/chunkination';
		
		$current = $this->chunk;
		
		$prev = $this->chunk > 0 ? ($this->chunk - 1) : null;
		$next = ((($this->chunk + 1) * $this->chunk_size) 
			< $this->total	? ($this->chunk + 1) : null);
		
		$prev_2 = $this->chunk > 1 ? ($this->chunk - 2) : null;
		$next_2 = ((($this->chunk + 2) * $this->chunk_size) 
			< $this->total	? ($this->chunk + 2) : null);
					
		$first = $this->chunk > 0 ? 0 : null;
		$last = ((($this->chunk + 1) * $this->chunk_size) 
			< $this->total ? (ceil($this->total / $this->chunk_size) - 1) : null);
		
		$view_data = array();
		$view_data['current'] = $current;
		$view_data['prev_2'] = $prev_2;
		$view_data['next_2'] = $next_2;
		$view_data['first'] = $first;
		$view_data['prev'] = $prev;
		$view_data['next'] = $next;
		$view_data['last'] = $last;
		
		foreach ($view_data as $k => $chunk)
		{
			if ($chunk === null) continue;
			$view_data[$k] = new stdClass();
			$view_data[$k]->url = str_replace('-chunk-', 
				($chunk + 1), $this->url_format);
			$view_data[$k]->chunk = $chunk + 1;
		}
		
		$ci =& get_instance();
		return $ci->load->view($template, $view_data, true);
	}
	
}

?>