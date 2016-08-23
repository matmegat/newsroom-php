<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Email_Notification {

	protected $vd;
	protected $content_view;
	protected $container_view;
	
	public function __construct($view = null)
	{
		$this->content_view = $view;	
		$this->vd = array();
	}
	
	public function set_content_view($view)
	{
		$this->content_view = $view;
	}
	
	public function set_container_view($view)
	{
		$this->container_view = $view;
	}
	
	public function set_data($name, $value)
	{
		$this->vd[$name] = $value;
	}
	
	public function send($user, $subject = null)
	{
		$this->vd['user'] = $user;
		$ci =& get_instance();
		
		$content_view = "email/notification/{$this->content_view}";
		$content = $ci->load->view($content_view, $this->vd, true);
		$this->vd['content_view'] = $content;
		
		if (!$this->container_view) $this->container_view = 'email/container';			
		$email_content = $ci->load->view($this->container_view, $this->vd, true);		
		if ($subject === null) $subject = 'iNewswire Notification';
		
		$email = new Email();
		$email->set_to_email($user->email);
		$email->set_from_email($ci->conf('email_address'));
		$email->set_to_name($user->name());
		$email->set_from_name($ci->conf('email_name'));
		$email->set_subject($subject);
		$email->set_message($email_content);
		$email->enable_html();
		Mailer::send($email);
	}

}

?>