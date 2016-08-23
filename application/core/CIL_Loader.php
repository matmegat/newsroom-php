<?php

class CIL_Loader extends CI_Loader {
	
	protected $within_view = 0;
	
	/**
	 * Load View
	 *
	 * This function is used to load a "view" file.  It has three parameters:
	 *
	 * 1. The name of the "view" file to be included.
	 * 2. An associative array of data to be extracted for use in the view.
	 * 3. TRUE/FALSE - whether to return the data or load it.  In
	 * some cases it's advantageous to be able to return data so that
	 * a developer can process it in some way.
	 *
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	void
	 */
	public function view($view, $vars = array(), $return = false)
	{
		// force return on multiple levels
		if ($this->within_view++ > 0)
			$return = true;
		
		// make ci accessible 
		$vars['ci'] =& get_instance();
		$vars['vd'] = $vars['ci']->vd;
		$return = parent::view($view, $vars, $return);
		$this->within_view--;
		return $return;
	}
	
}

?>