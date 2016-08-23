<?php

class Report {

	protected $url = null;
	protected $cover = null;
	protected $out = null;
	
	public static function from_file($file)
	{
		$report = new static(null);
		$report->out = $file;
		return $report;
	}
	
	public function __construct($url)
	{
		$this->url = $url;
	}
	
	public function set_cover($url)
	{
		$this->cover = $url;
	}
	
	public function generate()
	{
		$ci =& get_instance();
			
		$this->out = File_Util::buffer_file();
		$cover_url = escapeshellarg($this->cover);
		$report_url = escapeshellarg($this->url);
		$out_file = escapeshellarg($this->out);
		$executable = 'application/binaries/wkhtmltopdf/convert';
		$secret_file = escapeshellarg($ci->conf('auth_secret_file'));
		
		$command = '%s --quiet --cover %s --post-file auth-secret %s %s %s';
		$command = sprintf($command, $executable, $cover_url, 
			$secret_file, $report_url, $out_file);
		
		if (isset($ci->session))
			$ci->session->close();
		shell_exec($command);
		return $this->out;
	}
	
	public function deliver($name = null)
	{
		ob_clean();
		if ($name === null)
			$name = 'report.pdf';
		$type = 'application/pdf';
		$size = filesize($this->out);
		$ci =& get_instance();
		$ci->force_download($name, $type, $size);
		readfile($this->out);
		unlink($this->out);
		exit();
	}
	
	public function indirect()
	{
		$ci =& get_instance();
		$token = md5(microtime(true));
		$session_name = "download_token_{$token}";
		$ci->vd->download_url = "shared/download/report/{$token}";
		Data_Cache::write($session_name, $this->out);
		
		// load feedback (and download) message for the user
		$feedback_view = 'manage/partials/report-generated-feedback';
		$feedback = $ci->load->view($feedback_view, null, true);
		$ci->add_feedback($feedback);
	}
  
}

?>