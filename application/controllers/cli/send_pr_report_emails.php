<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Send_PR_Report_Emails_Controller extends CLI_Base {
		
	public function index()
	{
		$this->send(30);
		$this->send(7);
	}
	
	protected function send($when)
	{
		$when = (int) $when;
		$now_str = Date::$now->format(Date::FORMAT_MYSQL);		
		$sql = "SELECT c.company_id, c.id, c.title, rs.pr_email
			FROM nr_report_setting rs INNER JOIN nr_content c
			ON rs.pr_email IS NOT NULL AND rs.pr_when = '{$when}'
			AND rs.company_id = c.company_id AND c.type = ?
			AND c.date_publish <= DATE_SUB(UTC_TIMESTAMP(), INTERVAL {$when} DAY)
			AND c.is_published = 1 AND c.is_legacy = 0
			LEFT JOIN nr_report_sent rse ON c.id = rse.content_id
			WHERE rse.date_sent IS NULL";
				
		$query = $this->db->query($sql, array(Model_Content::TYPE_PR));
		if (!$query->num_rows()) return;
		
		$results = array();
		$sent_ids = array();
		
		foreach ($query->result() as $result)
		{
			$data = array();
			$data['date_sent'] = $now_str;
			$data['content_id'] = $result->id;
			$this->db->insert('nr_report_sent', $data);
			$results[] = $result;
		}
		
		foreach ($results as $result)
		{
			set_time_limit(300);			
			$nr = Model_Newsroom::find_company_id($result->company_id);
			$files = array();
			
			$url = "manage/analyze/content/report_index/{$result->id}";
			$url = $nr->url($url);
			$report = new Report($url);
			$report_file = $report->generate();
			$files['stats.pdf'] = $report_file;
			
			$url = "manage/analyze/content/dist_index/{$result->id}";
			$url = $nr->url($url);
			$dist = new Report($url);
			$dist_file = $dist->generate();
			$files['distribution.pdf'] = $dist_file;
			
			$pr_email = new Report_Email();
			$pr_email->set_context($result->title);
			$pr_email->set_type(REPORT_EMAIL::TYPE_PR);
			$pr_email->set_addresses($result->pr_email);
			$pr_email->send($files);
			
			unlink($report_file);
			unlink($dist_file);
		}
	}
	
}

?>