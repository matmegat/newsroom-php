<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Publish_To_DocSites_Controller extends CLI_Base {
	
	public function index()
	{
		$date_low = Date::hours(-48)->format(Date::FORMAT_MYSQL);
		$date_high = Date::hours(-3)->format(Date::FORMAT_MYSQL);
		
		$sql = "SELECT c.* FROM nr_content c 
			LEFT JOIN nr_content_docsite cd 
			ON c.id = cd.content_id 
			WHERE c.is_premium = 1 
			AND c.is_published = 1
			AND c.date_publish > '{$date_low}'
			AND c.date_publish < '{$date_high}'
			AND cd.content_id IS NULL LIMIT 1";
		
		while (true)
		{
			set_time_limit(300);
			
			$dbr = $this->db->query($sql);
			$m_content = Model_Content::from_db($dbr);
			if (!$m_content) break;
			
			$cd = new Model_Content_DocSite();
			$cd->content_id = $m_content->id;
			$cd->save();
			
			$url = $m_content->url_raw();
			$report = new Report($url);
			$file = $report->generate();
			$name = $this->filename($m_content);
			$title = $m_content->title;
			
			$issuu = new DocSite_Issuu();
			$scribd = new DocSite_Scribd();
			
			$cd->docsite_issuu = $issuu->upload($file, $name, $title);
			$cd->docsite_scribd = $scribd->upload($file, $name, $title);
			$cd->save();
			
			sleep(15);
		}
	}
	
	protected function filename($m_content)
	{
		$slug = substr($m_content->slug, 0, 32);
		$slug = preg_replace('#[^a-z0-9]#i', '_', $slug);
		$slug = preg_replace('#__#i', '_', $slug);
		$slug = preg_replace('#(^_|_$)#i', '', $slug);
		return "{$m_content->id}_{$slug}.pdf";
	}
	
}

?>