<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Social_Facebook_Post extends Social_Facebook_API {
	
	const DATA_MESSAGE 		= 'message';
	const DATA_NAME 			= 'name';
	const DATA_CAPTION 		= 'caption';
	const DATA_LINK 			= 'link';
	const DATA_DESCRIPTION 	= 'description';
	const DATA_PICTURE 		= 'picture';
	const FEED_ME           = 'me';
	
	protected $data = array();
	
	public function set_data($name, $value = null)
	{
		if (is_array($name))
			return $this->data = $name;
		return $this->data[$name] = $value;
	}	
	
	protected function page_access_token($page)
	{
		// caution: pages list is paginated (5000 per page)
		$page_data = $this->facebook->api("/{$page}?fields=access_token", 'get');
		if (!isset($page_data['access_token'])) return false;
		return $page_data['access_token'];
	}
	
	public function save()
	{
		try 
		{
			// set to the user access token first
			$this->facebook->setAccessToken($this->access_token);
			$feed = static::FEED_ME;
			
			if ($this->page !== null)
			{
				$page_access_token = $this->page_access_token($this->page);
				if (!$page_access_token) return false;
				$this->facebook->setAccessToken($page_access_token);
				$feed = $this->page;
			}
						
			$res = $this->facebook->api("/{$feed}/feed", 'POST', $this->data);
			// page id will be prefixed before the post id so extract		
			if (!preg_match('#^([0-9]+_)?([0-9]+)$#is', $res['id'], $match))
				return false;
			
			return $match[2];
		}
		catch (Exception $e)
		{
			return false;
		}
	}
	
	public static function page_list($access_token)
	{
		$facebook = Social_Facebook_API::instance();
		$facebook->setAccessToken($access_token);
		try { $pages = $facebook->api('/me/accounts', 'get'); }
		catch (Exception $e) { return false; }
		
		if (!isset($pages['data']) || !is_array($pages['data']))
			return false;
		
		$pages_with_permission = array();		
		foreach ($pages['data'] as $page)
		{
			if (!isset($page['access_token'])) continue;
			if (!in_array('CREATE_CONTENT', $page['perms'])) continue;
			$page_result = new stdClass();
			$page_result->id = $page['id'];
			$page_result->name = $page['name'];
			$page_result->access_token = $page['access_token'];
			$pages_with_permission[] = $page_result;
		}
		
		return $pages_with_permission;
	}
	
}

?>