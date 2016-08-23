<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Campaign extends Model {
	
	protected static $__table = 'nr_campaign';
	protected static $__primary = 'id';
	protected static $markers = array(
		'first-name' => 'First Name',
		'last-name' => 'Last Name',
		'email-address' => 'Email Address',
		'tracking-link' => 'Tracking Link',
	);
	
	public static function markers()
	{
		return static::$markers;
	}
	
	public function set_lists($lists)
	{
		$this->db->query("DELETE FROM nr_campaign_x_contact_list
			WHERE campaign_id = ?", array($this->id));
		
		foreach ($lists as $list)
		{
			if ($list instanceof Model_Contact_List) $list = $list->id;
			$this->db->query("INSERT IGNORE INTO nr_campaign_x_contact_list
				(campaign_id, contact_list_id) VALUES (?, ?)", 
				array($this->id, (int) $list));
		}
	}
	
	public function get_lists()
	{
		$sql = "SELECT l.* FROM nr_contact_list l INNER JOIN 
			nr_campaign_x_contact_list x ON l.id = x.contact_list_id
			WHERE x.campaign_id = ?";
			
		$result = $this->db->query($sql, array($this->id));
		return Model_Contact_List::from_db_all($result);
	}
	
	public function load_content_data()
	{
		$table = 'nr_campaign_data';
		$this->load_data($table);
	}
	
	public function load_data($table)
	{
		$result = $this->db->get_where($table, 
			array('campaign_id' => $this->id));
		$data = $result->row();
		foreach ($data as $k => $v)
			$this->$k = $v;
	}
	
	public function send($contact, $content = null)
	{
		if (!($contact instanceof Model_Contact))
			throw new Exception();
		
		$ci =& get_instance();
		$nr = Model_Newsroom::find_company_id($this->company_id);
		
		$stats = new Statistics();
		$stats->set_newsroom($nr->name);
		$stats->set_campaign($this->id);
		$pixel = $stats->email_view_pixel($contact->email);
		
		$vd = array();
		$vd['pixel'] = $pixel;
		$vd['contact'] = $contact;
		$vd['content'] = $this->generate_content($contact, $content);
		$vd['unsubscribe'] = $contact->unsubscribe_link();
		$view = 'manage/partials/email-template';
		$content = $ci->load->view($view, $vd, true);
		$to_name = implode(' ', array($contact->first_name, 
			$contact->last_name));
		
		$em = new Email('iNewsWire Mailer');
		$em->set_to_email($contact->email);
		$em->set_from_email($this->sender_email);
		if (trim($to_name)) $em->set_to_name($to_name);
		$em->set_from_name($this->sender_name);
		$em->set_subject($this->subject);
		$em->set_message($content);
		$em->enable_html();
		
		set_time_limit(300);
		return Mailer::send($em);
	}
	
	public function send_recorded($content)
	{
		$ci =& get_instance();
		if (!$ci->conf('campaign_recorded_active'))
			return;
		
		$vd = array();
		$vd['content'] = $content;
		$vd['newsroom'] = Model_Newsroom::find_company_id($this->company_id);
		$vd['campaign'] = $this;
		$view = 'manage/partials/recorded-email-template';
		$content = $ci->load->view($view, $vd, true);
		
		$em = new Email('iNewsWire Mailer');
		$em->set_to_email($ci->conf('campaign_recorded_email'));
		$em->set_from_email($ci->conf('email_address'));
		$em->set_from_name($this->sender_name);
		$em->set_subject('iNewsWire Recorded Email');
		$em->set_message($content);
		$em->enable_html();
		
		return Mailer::send($em);
	}
	
	public function send_test($contact)
	{
		if (!$contact->email)
			return false;
		
		$ci =& get_instance();
		$nr = Model_Newsroom::find_company_id($this->company_id);
		
		$stats = new Statistics();
		$stats->set_newsroom($nr->name);
		$stats->set_campaign($this->id);
		$pixel = $stats->email_view_pixel($contact->email);
		
		$vd = array();
		$vd['pixel'] = $pixel;
		$vd['contact'] = $contact;
		$vd['content'] = $this->generate_content($contact);
		$view = 'manage/partials/email-template';
		$content = $ci->load->view($view, $vd, true);
		$to_name = implode(' ', array($contact->first_name, 
			$contact->last_name));
		
		$em = new Email('iNewsWire Mailer');
		$em->set_to_email($contact->email);
		$em->set_from_email($this->sender_email);
		if (trim($to_name)) $em->set_to_name($to_name);
		$em->set_from_name($this->sender_name);
		$em->set_subject($this->subject);
		$em->set_message($content);
		$em->enable_html();
				
		return Mailer::send($em);
	}
	
	public function generate_content($contact, $content = null)
	{
		if ($content === null)
		{
			if (!isset($this->content)) 
				$this->load_content_data();
			$content = $this->content;	
		}
		
		$ci =& get_instance();
		$stats = new Statistics();
		$m_content = Model_Content::find(value_or_null($this->content_id));
		$content_url = $m_content ? $m_content->url() : null;
		$pixel_user_id = $stats->email_pixel_user_id($contact->email);
		$tracking_url = $ci->website_url($content_url);
		$tracking_url = "{$tracking_url}?cam={$this->id}&puid={$pixel_user_id}";
		$pattern = '((%s))';
		
		$values = array(
			'first-name' => $contact->first_name,
			'last-name' => $contact->last_name,
			'email-address' => $contact->email,
			'tracking-link' => $tracking_url,
		);
		
		foreach (static::markers() as $marker => $label)
		{
			$value = $values[$marker];
			$marker_pattern = sprintf($pattern, $marker);
			$content = str_replace($marker_pattern, $value, $content);
		}
		
		return $content;
	}
	
	public function send_all()
	{	
		$recipient_contacts = $this->recipient_contacts();
		$data = Model_Campaign_Data::find($this->id);
		if (($data_contacts = @unserialize($data->contacts)) === false)
			$data_contacts = array();
		
		$data_contacts_hash = array();
		foreach ($data_contacts as $contact_id)
			$data_contacts_hash[$contact_id] = true;			
		
		foreach ($recipient_contacts as $contact)
		{
			$this->send($contact, $data->content);
			if (!isset($data_contacts_hash[$contact->id]))
			{
				$data_contacts_hash[$contact->id] = true;
				$data_contacts[] = $contact->id;
			}
		}
		
		$data->contacts = serialize($data_contacts);
		$data->save();
		
		$this->contact_count = count($data_contacts);
		$this->save();
		
		$this->send_recorded($data->content);
	}
	
	public function credits_required()
	{
		$recipient_contacts = $this->recipient_contacts();
		$required_credits = count($recipient_contacts);		
		return $required_credits;
	}
	
	// should only be called on actual send
	// as this also updates lists with 
	// the last campaign as this
	protected function recipient_contacts()
	{
		$m_contacts = array();
			
		if ($this->all_contacts)
		{
			$contacts = Model_Contact::find_all(array(
				array('company_id', $this->company_id),
				array('is_unsubscribed', 0)));
			
			foreach ($contacts as $contact)
				$m_contacts[] = $contact;
		}
		else
		{
			$i_contacts = array();
			$lists = $this->get_lists();
			foreach ($lists as $list)
			{
				$sql = "SELECT co.* FROM nr_contact_list_x_contact x 
					INNER JOIN nr_contact co ON x.contact_id = co.id
					WHERE x.contact_list_id = ? 
					AND co.is_unsubscribed = 0";
				
				$result = $this->db->query($sql, array($list->id));
				$contacts = (array) Model_Contact::from_db_all($result);
				$list->last_campaign_id = $this->id;
				$list->save();
				
				foreach ($contacts as $contact)
				{
					if (isset($i_contacts[$contact->id])) continue;
					$i_contacts[$contact->id] = true;
					$m_contacts[] = $contact;
				}
			}
		}
		
		return $m_contacts;
	}
	
	public function delete()
	{
		parent::delete();
		$this->db->delete('nr_campaign_x_contact_list', 
			array('campaign_id' => $this->id));
		$this->db->delete('nr_campaign_data', 
			array('campaign_id' => $this->id));
		
		if ($this->is_draft)
			$this->db->delete('nr_campaign_content_consumed', 
			array('campaign_id' => $this->id));
	}
	
}

?>