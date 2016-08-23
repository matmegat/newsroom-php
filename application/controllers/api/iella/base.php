<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Iella_Base extends CIL_Controller {
	
	protected $iella_in;
	protected $iella_out;
	
	public function __construct()
	{
		parent::__construct();
		if ($this->authorize() !== true) die('denied');
		$iella_in_str = $this->input->post('iella-in');
		$this->iella_in = json_decode($iella_in_str);
		$this->iella_out = new stdClass();
	}
	
	protected function send()
	{
		ob_clean();
		$iella_out_str = json_encode($this->iella_out);
		$this->output->set_content_type('application/json');
		$this->output->set_output($iella_out_str);
	}
	
	protected function authorize()
	{	
		if ($secret = $this->input->post('iella-secret'))
		{
			$secret_file = $this->conf('iella_secret_file');
			if ($secret === file_get_contents($secret_file))
				return true;
		}
		else
		{
			$test_secret_file = Stored_File::from_uploaded_file('iella-secret');
			if ($test_secret_file->exists())
			{
				$secret = $test_secret_file->read();
				$iella_secret_file = $this->conf('iella_secret_file');
				if ($secret === file_get_contents($iella_secret_file))
					return true;
			}
		}
	}
	
	protected function iella_in($name)
	{
		if (isset($this->iella_in[$name]))
			return $this->iella_in[$name];
		return null;
	}
	
	protected function iella_out($name, $value)
	{
		$this->iella_out[$name] = $value;
	}
	
	protected function __on_execution_end($exception)
	{
		if ($exception !== null)
		{
			$this->iella_out = new stdClass();
			$this->iella_out->exception = 
				$exception->__toString();
		}
		
		$this->send();
	}
	
}

?>