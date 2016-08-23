<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DocSite_Issuu extends DocSite_Base {
	
	public function upload($file, $name, $title)
	{
		if (!is_file($file))
			throw new Exception();
				
		$conf = $this->dsconf;
		$params = array(
			'action' => 'issuu.document.upload',
			'apiKey' => $conf->api_key,
			'downloadable' => 'true',
			'format' => 'json',
			'name' => $name,
			'title' => $title,
		);
		
		$params['signature'] = $this->signature($params);
		$params['file'] = Unirest::file($file, $name);
		$response = Unirest::post($conf->url_upload, null, $params);
		var_dump($response);
		$doc_name = @$response->body->rsp->_content->document->name;
		if (!$doc_name) return null;
		
		$doc_url = sprintf($conf->url_doc, $doc_name);
		return $doc_url;
	}
	
	protected function signature($params)
	{
		$data = $this->dsconf->secret;
		foreach ($params as $k => $v)
			$data = "{$data}{$k}{$v}";
		return md5($data);
	}
	
}

?>