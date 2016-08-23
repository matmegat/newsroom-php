<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class DocSite_Base {

	protected $dsconf;

	public function __construct()
	{
		$ci =& get_instance();
		// the class name and config index are paired
		$class = strtolower(get_called_class());
		$dsconf = $ci->conf($class);
		$this->dsconf = new stdClass();
		foreach ($dsconf as $k => $v)
			$this->dsconf->{$k} = $v;
		
		// load unirest lib
		lib_autoload('unirest');
		Unirest::timeout(60);
	}
	
	abstract public function upload($file, $name, $title);
	
}

?>