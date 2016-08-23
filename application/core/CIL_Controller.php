<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CIL_Controller extends CI_Controller {
	
	public $vd;
	public $session;
	public $newsroom;
	public $newsrooms;
	public $feedback;
	public $is_own_domain;
	public $is_admo_host;
	public $is_common_host;
	public $is_website_host;
	public $is_detached_host;
	public $local_tz;
	public $eob;
	
	private $m_common = NR_DEFAULT;

	public function __construct()
	{
		parent::__construct();
		
		// environment config
		$this->env = $GLOBALS['env'];
		
		// check if blocked
		$this->check_blocked();
		
		// load the configuration
		$this->config->load('newsroom', true);
		$this->vd = new View_Data();
		$this->vd->assets_base = $this->conf('assets_base');
		$this->vd->version = $this->env['version'];
		
		if ($this->input->is_cli_request())
			return;
		
		$this->session = new Session();
		$this->output->set_content_type('text/html; charset=utf-8');
		$this->vd->title = array();
		
		if (isset($this->title))
		{
			$this->vd->title[] = $this->title;
			unset($this->title);
		}
		
		// feedback messages to display in template
		$this->feedback = $this->session->get('nr_feedback');
		
		$common_host = $this->conf('common_host');
		$website_host = $this->conf('website_host');
		
		// determine if we are on website host
		if ($website_host === $this->env['host'])		
			$this->is_website_host = true;
		
		// determine if we are on common host 
		if ($common_host === $this->env['host'] ||
		    $website_host === $this->env['host'])
		{
			$this->is_common_host = true;
			$this->newsroom = Model_Newsroom::common();
			
			if ($this->uri->segment(1) === 'admin') return;
			if ($this->uri->segment(1) === 'reseller') return;
			
			if (!Auth::requires_user()) return;
			if (!Auth::is_user_online()) 
				$this->redirect('shared/login');
			
			// manage_base implements own allowed common
			if ($this->uri->segment(1) === 'manage') 
				return Auth::check();
			
			// find a newsroom => create newsroom on fail?
			$newsroom = Auth::user()->default_newsroom();
			$this->redirect(gstring($newsroom->url($this->uri->uri_string())), false);
			return;
		}
		
		$pattern = $this->conf('host_pattern');
		$detached_pattern = $this->conf('detached_pattern');
		$admo_pattern = $this->conf('admo_pattern');
		
		// extract the newsroom name from standard hosts
		if (preg_match($pattern, $_SERVER['HTTP_HOST'], $match))
		{
			$this->newsroom = Model_Newsroom::find_name($match[1]);
			if (!$this->newsroom) show_404($this->uri->uri_string());
			$this->is_own_domain = false;
		}
		else if (preg_match($detached_pattern, $this->env['host'], $match))
		{
			$newsroom_name = $match[1];
			if ($newsroom_name === $this->conf('common_host_name'))
				  $this->newsroom = $this->common();
			else $this->newsroom = Model_Newsroom::find_name($newsroom_name);
			if (!$this->newsroom) show_404($this->uri->uri_string());
			$this->is_detached_host = true;
			$this->is_own_domain = false;
			
			if (Auth::requires_user()) 
				$this->redirect(gstring($this->newsroom->url(
					$this->uri->uri_string())), false);
						
			if (!Detached::is_used())
				// no detached items found so nothing is previewed
				$this->redirect(gstring($this->newsroom->url($this->uri->uri_string())), false);
			
			if ($this->newsroom->is_common)
				$this->is_common_host = true;
			
			// load feedback message for the user
			$feedback_view = 'manage/partials/is-detached-feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
			
			if ($newsroom = Detached::read('newsroom'))
			{
				// is_active status should be true
				$newsroom->is_active = $this->newsroom->is_active;
				$this->newsroom = $newsroom;
			}
		}
		else if (preg_match($admo_pattern, $this->env['host'], $match))
		{
			if (!Auth::is_admin_controlled()) show_404();
			$admo_user_id = $match[1];
			if ($admo_user_id == Auth::user()->id)
				$this->redirect($this->common()->url('manage'), false);
			Auth::admo($admo_user_id);
			$this->newsroom = $this->common();
			$this->is_common_host = true;
			$this->is_admo_host = true;
		}
		else
		{
			$this->newsroom = Model_Newsroom::find_domain($_SERVER['HTTP_HOST']);
			if (!$this->newsroom) show_404($this->uri->uri_string());
			$this->is_own_domain = true;
		}
		
		Auth::from_secret();
		
		if ($this->newsroom->domain && !$this->is_detached_host)
		{
			$requires_own_domain = $this->newsroom->requires_own_domain();
			
			if (!$requires_own_domain && $this->is_own_domain)
			{
				$relative = $this->uri->uri_string();
				$url = $this->newsroom->url($relative);
				$this->redirect(gstring($url), false);
			}
			
			if ($requires_own_domain && !$this->is_own_domain)
			{
				$relative = $this->uri->uri_string();
				$url = $this->newsroom->url($relative, true);
				$this->redirect(gstring($url), false);
			}
		}
		
		Auth::check();
	}
	
	public function _remap($method, $params = array())
	{
		$oee_method = '__on_execution_end';
		$oes_method = '__on_execution_start';
		$method_base = $method;
		$exception = null;
		$params_slice = 0;
		
		// call a __construct like function
		if (method_exists($this, $oes_method))
			call_user_func(array($this, $oes_method));
		
		if ($method === null) 
		{
			if (!$params) $params = array('index');
			$method_base = $method = $params[0];
			$params = array_slice($params, 1);
		}
		
		for ($i = 0; $i < count($params); $i++)
		{
			$method_base = "{$method_base}_{$params[$i]}";
			if (method_exists($this, $method_base))
				$params_slice = max($params_slice, $i + 1);
		}
		
		if ($params_slice > 0)
		{
			$method_params = implode('_', array_slice($params, 0, $params_slice));
			$params = array_slice($params, $params_slice);
			$method = "{$method}_{$method_params}";
			try { $rv = call_user_func_array(array($this, $method), $params); }
			catch (Exception $exception) { $rv = null; }
			if (method_exists($this, $oee_method))
				  call_user_func(array($this, $oee_method), $exception);
			else if ($exception !== null) throw $exception;
			return $rv;
		}
		
		if (method_exists($this, $method))
		{
			try { $rv = call_user_func_array(array($this, $method), $params); }
			catch (Exception $exception) { $rv = null; }
			if (method_exists($this, $oee_method))
				  call_user_func(array($this, $oee_method), $exception);
			else if ($exception !== null) throw $exception;
			return $rv;
		}
		
		if (method_exists($this, 'index'))
		{
			try { $rv = call_user_func_array(array($this, 'index'), 
				array_merge(array($method), $params)); }
			catch (Exception $exception) { $rv = null; }
			if (method_exists($this, $oee_method))
				  call_user_func(array($this, $oee_method), $exception);
			else if ($exception !== null) throw $exception;
			return $rv;
		}
				
		show_404();
	}
	
	public function common()
	{
		if ($this->m_common === NR_DEFAULT)
			$this->m_common = Model_Newsroom::common();
		return $this->m_common;
	}
	
	public function local_tz()
	{
		if ($this->local_tz) return $this->local_tz;
		if ($this->newsroom && $this->newsroom->timezone) return 
			$this->local_tz = new DateTimeZone($this->newsroom->timezone);
		return $this->local_tz = new DateTimeZone($this->conf('timezone'));
	}
	
	public function conf($name, $index = null)
	{
		// load from the database first
		$value = Model_Setting::value($name);
		if ($value !== null) return $value;
		
		// load from the config files
		$value = $this->config->item($name, 'newsroom');
		if ($index !== null) $value = $value[$index];
		return $value;
	}
	
	public function json($data)
	{
		ob_clean();
		$data = json_encode($data);
		$this->output->set_content_type('application/json');
		$this->output->set_output($data);
	}
	
	public function redirect($url, $relative = true, $terminate = true) 
	{
		if ($relative)
		{
			// prefix the base for an absolute url
			$base_url = $this->config->item('base_url');
			$url = "{$base_url}{$url}";
		}
		
		if ($url === null) $this->redirect(null);
		$header = sprintf('Location: %s', $url);
		header($header);
		if ($terminate)
			exit();
	}
	
	public function set_redirect($url, $relative = true) 
	{
		$this->redirect($url, $relative, false);
	}
	
	public function website_url($relative_url) 
	{
		$host = $this->conf('website_host');
		$protocol = $this->env['protocol'];
		return "{$protocol}{$host}/{$relative_url}";
	}
	
	public function admo_url($relative_url, $user_id = null) 
	{
		if (!$user_id) $user_id = Auth::user()->id;
		$host = $this->conf('admin_host');
		return "http://{$host}/{$relative_url}";
	}
	
	public function add_feedback($feedback)
	{
		$session =& $this->session->reference();
		if (!isset($session['nr_feedback']) || 
		    !is_array($session['nr_feedback']))
			$session['nr_feedback'] = array();
		$session['nr_feedback'][] = $feedback;
	}
	
	public function use_feedback($feedback)
	{
		if (!$this->feedback) $this->feedback = array();
		$this->feedback[] = $feedback;
	}
	
	public function clear_feedback()
	{
		$this->session->delete('nr_feedback');
	}
	
	public function add_eob($eob)
	{
		if (!$this->eob) $this->eob = array();
		$this->eob[] = $eob;
	}
	
	public function check_blocked()
	{
		$address = $this->env['remote_addr'];
		$cookies = $this->env['cookies'];
		$ckename = 'CICB';		
		
		if (isset($cookies[$ckename]))
		{
			$blocked = new Model_Blocked();
			$blocked->addr = $address;
			$blocked->save();			
			Auth::$is_blocked = true;
			return;
		}
		
		if (Model_Blocked::find($address)) 
		{
			setcookie($ckename, 1, 31536000 + time(), 
				$this->env['session_path'],
				$this->env['session_domain']);
			Auth::$is_blocked = true;
			return;
		}
	}
	
	public function denied()
	{		
		if (!Auth::is_user_online())
		{
			// redirect to login and preserve intent
			$hash = md5(microtime(true));
			Data_Cache::write($hash, gstring($this->uri->uri_string));
			$this->redirect("shared/login?intent={$hash}");	
		}
			
		// if its not manage then must be fail
		if ($this->uri->segment(1) !== 'manage')
			show_404();
		
		// are there some segments we can reverse?
		if (count($segments = $this->uri->segment_array()) <= 1)
			show_404();
		
		// reverse segments to try again
		$segments = array_slice($segments, 0, -1);
		$url = implode('/', $segments);
		$this->redirect($url);
	}
	
	public function expires($expires_secs)
	{
		// allows cache for $expires_secs
		$expires_time = time() + $expires_secs;
		$expires_date = gmdate(DateTime::RFC1123, $expires_time);
		header("Cache-Control: public, max-age={$expires_secs}");
		header("Expires: {$expires_date}");		
		header("Pragma: public");
	}
	
	public function force_download($name, $type, $size)
	{
		// force the user to download the file
		$expires_date = gmdate(DateTime::RFC1123, 0);
		header("Pragma: public");
		header("Expires: {$expires_date}");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header("Content-Disposition: attachment; filename=\"{$name}\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: {$size}");
		header("Content-Type: {$type}");
		header("Connection: close");
	}

}

?>