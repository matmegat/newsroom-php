<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class Configuration_Controller extends Admin_Base {

	public function index()
	{
		$order = array('name','asc');
		$settings = Model_Setting::find_all(null, $order);
		
		if ($this->input->post())
		{
			foreach ($settings as $setting)
			{
				$name = $setting->name;
				$value = $this->input->post($name);
				if ($value === $setting->value) continue;
				$method = "on_save_{$name}";
				if (method_exists($this, $method))
					$value = call_user_func(array($this, $method), $value);
				$setting->set($value);
				$setting->save();
			}
			
			// load feedback message for the user
			$feedback_view = 'admin/settings/partials/configuration_save_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
		}
		
		$cols   = 3;
		$total  = count($settings);
		$remain = $total - (floor($total / $cols) * $cols);
		$divide = ceil($total / $cols);
		$offset = 0;
		
		for ($i = 1; $i <= $cols; $i++)
		{
			$slice = array_slice($settings, $offset, $divide);
			$this->vd->{"col_{$i}"} = $slice;
			$offset += $divide;
			if (--$remain == 0) 
				$divide--;
		}
		
		$editor_modal = new Modal();
		$editor_view = 'admin/settings/configuration-editor';
		$editor_content = $this->load->view($editor_view, null, true);
		$editor_modal->set_content($editor_content);
		$this->add_eob($editor_modal->render(800, 480));
		$this->vd->editor_modal_id = $editor_modal->id;
		
		$this->load->view('admin/header');
		$this->load->view('admin/settings/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/settings/configuration');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
	protected function on_save_reserved_newsrooms($value)
	{
		// http://goo.gl/urE9eM
		// normalize new lines to be windows-safe		
		$value = preg_replace('#(?>\r\n|\r|\n)#is', CRLF, $value);
		$lines = explode("\r\n", $value);
		
		// removes all existing reserved names
		$this->db->query('truncate nr_reserved_name');
		
		foreach ($lines as $line)
		{
			if (!($line = trim($line))) continue;
			if (str_starts_with($line, '#')) continue;
			$reserved = new Model_Reserved_Name();
			$reserved->regex = $line;
			$reserved->save();
		}
		
		return $value;
	}

}

?>