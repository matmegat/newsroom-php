<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CIL_Router extends CI_Router {
	
	protected $use_validated_request = false;
	protected $base_suffix = null;
	
	// runs the process again 
	// using a new controller base
	function _controller_rebase($suffix)
	{
		$this->base_suffix = $suffix;
		$this->uri->_reverse_reindex_segments();
		$this->_set_request($this->uri->segments);
		$this->uri->_reindex_segments();
	}
	
	// the controller dir
	function fetch_base()
	{
		$base = APPPATH;
		if ($this->base_suffix)
			  return "{$base}/controllers/{$this->base_suffix}/";
		else return "{$base}/controllers/";
	}
	
	// the controller file
	function fetch_file()
	{
		$file  = $this->fetch_base();
		$file .= $this->fetch_directory();
		$file .= $this->fetch_class();
		$file .= '.php';
		return $file;
	}
	
	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */
	function _validate_request($segments)
	{		
		$this->use_validated_request = false;
		if (count($segments) == 0)
			return array();
		
		while (!isset($segments[0]) && $i++ < 10)
		{
			$segments[0] = null;
			array_shift($segments);
		}
		
		$base = $this->fetch_base();
		// Is the controller in a sub-folder?
		if (is_dir("{$base}/{$segments[0]}") && 
		   (is_file("{$base}/{$segments[0]}/{$this->default_controller}.php") ||
			(count($segments) > 1 && 
			(is_dir("{$base}/{$segments[0]}/{$segments[1]}") ||
			 is_file("{$base}/{$segments[0]}/{$segments[1]}.php")))))
		{
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);
				
			while (count($segments) > 0 && 
					(is_file("{$base}/{$this->directory}/{$segments[0]}/{$this->default_controller}.php") ||
					(count($segments) > 1 && 
					(is_dir("{$base}/{$this->directory}/{$segments[0]}/{$segments[1]}") ||
					 is_file("{$base}/{$this->directory}/{$segments[0]}/{$segments[1]}.php")))))
			{
				// Set the directory and remove it from the segment array
				$this->set_directory($this->directory.$segments[0]);
				$segments = array_slice($segments, 1);
			}

			if (count($segments) > 0)
			{
				if (file_exists("{$base}/{$this->directory}/{$segments[0]}.php")) return $segments;
				if (file_exists("{$base}/{$this->directory}/{$this->default_controller}.php"))
				{
					$this->use_validated_request = true;
					$this->set_class('main');
					$this->set_method(null);
					array_unshift($segments, null);
					array_unshift($segments, null);
					return $segments;
				}
			}
			else
			{
				return array();
			}
			
			// reset to base dir
			$this->set_directory('');
		}
		
		// Does the requested controller exist in the root folder?
		if (file_exists("{$base}/{$segments[0]}.php"))
		{
			return $segments;
		}
		
		// Does the default controller exist in the root folder?
		if (file_exists("{$base}/{$this->default_controller}.php"))
		{
			$this->use_validated_request = true;
			$this->set_class('main');
			$this->set_method(null);
			return $segments;
		}

		// 404_override functionality
		if (!empty($this->routes['404_override']))
		{
			$x = explode('/', $this->routes['404_override']);			
			$this->set_method(isset($x[1]) ? $x[1] : 'index');
			$this->set_class($x[0]);
			return $x;
		}

		// nothing else to do at this point but show a 404
		show_404("{$this->directory}/{$segments[0]}");
	}
	
	/**
	 * Set the Route
	 *
	 * This function takes an array of URI segments as
	 * input, and sets the current class/method
	 *
	 * @access	private
	 * @param	array
	 * @param	bool
	 * @return	void
	 */
	function _set_request($segments = array())
	{		
		$segments = $this->_validate_request($segments);

		// validate already decided on the details
		if ($this->use_validated_request)
		{
			$this->uri->rsegments = $segments;
			return;
		}
		
		if (count($segments) == 0)
		{
			return $this->_set_default_controller();
		}
			
		$this->set_class($segments[0]);
			
		if (isset($segments[1]))
		{
			// A standard method request
			$this->set_method($segments[1]);
		}
		else
		{
			// This lets the "routed" segment array identify that the default
			// index method is being used.
			$segments[1] = 'index';
		}

		// Update our "routed" segment array to contain the segments.
		// Note: If there is no custom routing, this array will be
		// identical to $this->uri->segments
		$this->uri->rsegments = $segments;		
	}
	
	/**
	 *  Set the directory name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function set_directory($dir)
	{
		$this->directory = str_replace('.', '', $dir) . '/';
	}
	
	/**
	 * Fetch the current class
	 *
	 * @access	public
	 * @return	string
	 */
	function fetch_class()
	{
		// attempt to load main 
		if (!($class = $this->class))
			$class = 'main';
		
		// this doesn't exist when first called
		// to load the file based on class name
		if (class_exists("{$class}_Controller"))
			$class = "{$class}_Controller";
		return $class;
	}
	
	/**
	 *  Fetch the current method
	 *
	 * @access	public
	 * @return	string
	 */
	function fetch_method()
	{
		if ($this->method == $this->fetch_class())
		{
			return 'index';
		}

		return $this->method;
	}
		
}

?>