<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('api/iella/base');

class Classic_Publish_List_Controller extends Iella_Base {
	
	public function index()
	{
		$this->iella_out->types = array();
		foreach (Model_Content::allowed_types() as $type)
		{
			$criteria = array();
			$criteria[] = array('is_under_review', '1');
			$criteria[] = array('type', $type);
			$count = Model_Content::count_all($criteria);
			$this->iella_out->types[$type] = new stdClass();
			$this->iella_out->types[$type]->count = $count;
			$this->iella_out->types[$type]->name = 
				Model_Content::full_type_plural($type);
		}
	}
	
}

?>