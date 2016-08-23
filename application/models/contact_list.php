<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Contact_List extends Model {
	
	protected static $__table = 'nr_contact_list';
	protected static $__primary = 'id';
	
	public function delete()
	{
		parent::delete();
		$this->db->delete('nr_contact_list_x_contact', 
			array('contact_list_id' => $this->id));
		$this->db->delete('nr_campaign_x_contact_list', 
			array('contact_list_id' => $this->id));
	}
	
	public function add_contact($contact)
	{
		if ($contact instanceof Model_Contact)
			$contact = $contact->id;
		
		$this->db->query("INSERT IGNORE INTO nr_contact_list_x_contact
			(contact_list_id, contact_id) VALUES (?, ?)", 
			array($this->id, (int) $contact));
	}
	
	public function remove_contact($contact)
	{
		if ($contact instanceof Model_Contact)
			$contact = $contact->id;
		
		$this->db->query("DELETE FROM nr_contact_list_x_contact
			WHERE contact_list_id = ? AND contact_id = ?", 
			array($this->id, (int) $contact));
	}
	
}

?>