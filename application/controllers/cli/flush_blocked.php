<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Flush_Blocked_Controller extends CLI_Base {
	
	public function index()
	{
		// removes all blocked addresses older than 365 days
		$sql = "DELETE FROM nr_blocked WHERE date_blocked < ?";
		$date_cut = Date::days(-365)->format(Date::FORMAT_MYSQL);
		$this->db->query($sql, array($date_cut));
	}
	
}

?>