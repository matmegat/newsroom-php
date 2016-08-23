<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Send_Overall_Report_Emails_Controller extends CLI_Base {
		
	public function index($when)
	{
		if (!isset($when)) exit(-1);
		
		$sql = "SELECT rs.company_id, rs.overall_email
			FROM nr_report_setting rs INNER JOIN nr_newsroom n
			ON rs.company_id = n.company_id AND n.is_active = 1
			WHERE rs.overall_email IS NOT NULL 
			AND rs.overall_when = ? ORDER BY company_id ASC";
				
		$query = $this->db->query($sql, array($when));
		if (!$query->num_rows()) return;
		
		$results = array();
		foreach ($query->result() as $result)
			$results[] = $result;
	
		foreach ($results as $result)
		{
			set_time_limit(300);
			
			$nr = Model_Newsroom::find_company_id($result->company_id);
			if (!$nr->is_active) continue;
			
			$url = $nr->url('manage/analyze/overall/report_index');
			$report = new Report($url);
			$file = $report->generate();
			
			$overall_email = new Report_Email();
			$overall_email->set_context($nr->company_name);
			$overall_email->set_type(REPORT_EMAIL::TYPE_OVERALL);
			$overall_email->set_addresses($result->overall_email);
			$overall_email->send($file);
			
			unlink($file);
		}
	}
	
}

?>