<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_parent_controller('cli/base');

class Transfer_Initial_Item extends CLI_Base {
	
	public function index($next_index, $bulk)
	{
		// TODO: REMOVE THIS FILE
		return;
		
		$ci =& get_instance();
		
		set_time_limit(300);
		$next_index = (int) $next_index;
		$db_result = $ci->db->query("SELECT * FROM prs_temp ORDER BY id DESC LIMIT {$next_index}, {$bulk}");
		if (!$db_result->num_rows()) return;
		
		foreach ($db_result->result() as $record)
		{	
			foreach ($record as $k => $v)
				$record->$k = Legacy_Text::repair($v);
			
			$user_id = (int) $record->userid;
			
			if ($record->c_name)
			{
				$company = Model_Company::find(array(
					array('user_id', $user_id),
					array('name', $record->c_name)));
				
				if (!$company) 
				{
					$company = new Model_Company();
					$company->user_id = $user_id;
					$company->name = $record->c_name;
				}
			}
			else
			{
				$company = Model_Company::find(array(
					array('user_id', $user_id),
					array('name', 'Unknown')));
				
				if (!$company) 
				{
					$company = new Model_Company();
					$company->user_id = $user_id;
					$company->name = 'Unknown';
				}
			}
			
			$company->save();		
				
			if ($company->company_contact_id)
			{
				$company_contact = Model_Company_Contact::find(array(
					array('company_id', $company->id),
					array('name', $record->c_contact));
				
				if (!$company_contact && trim($record->c_contact))
				{
					$new_cc = new Model_Company_Contact();
					$new_cc->name = $record->c_contact;
					$new_cc->company_id = $company->id;
					$new_cc->save();
				}
			}
			else
			{
				$new_cc = new Model_Company_Contact();
				$new_cc->name = value_or_null($record->c_contact);
				$new_cc->company_id = $company->id;
				$new_cc->save();
				$company->company_contact_id = $new_cc->id;
				$company->save();
			}
			
			$company_profile = Model_Company_Profile::find($company->id);
			if (!$company_profile) $company_profile = new Model_Company_Profile();
			
			// this is not 100% but it will have to do
			$company_profile->address_street = value_or_null($record->c_address1);
			$company_profile->address_city = value_or_null($record->c_address2);
			$company_profile->address_zip = value_or_null($record->c_zip);
			$company_profile->description = value_or_null($this->vd->pure($record->c_details));
			$company_profile->website = value_or_null($record->c_url);
			$company_profile->address_country_id = value_or_null($record->countryid);
			$company_profile->phone = value_or_null($record->c_phone);
			$company_profile->company_id = $company->id;
			$company_profile->save();
			
			$content = new Model_Content();
			$content->id = $record->id;
			$content->company_id = $company->id;
			$content->type = Model_Content::TYPE_PR;
			$content->title = $record->title;
			$content->slug = Model_Content::generate_slug($record->url_title);
			$content->date_created = $record->created;
			
			if (preg_match('#[1-9]#', $record->releasedate))
			{
				$record->publishingtime = (int) $record->publishingtime;
				$dt_date_publish = new DateTime("{$record->releasedate} {$record->publishingtime}:00:00");
				$content->date_publish = $dt_date_publish->format(Date::FORMAT_MYSQL);
			} 
			else if (preg_match('#[1-9]#', $record->approve_time))
			{
				$content->date_publish = $record->approve_time;
			}
			else
			{
				$content->date_publish = $record->created;
			}
			
			$content->is_published = 1;
			$content->is_draft = 0;
			$content->is_legacy = 1; 
			$content->save();
			
			$legacy_high = new Model_Legacy_PR_High();
			$legacy_high->values((array) $record);
			$legacy_high->id = $record->id;
			$legacy_high->save();
			
			$legacy_low = new Model_Legacy_PR_Low();
			$legacy_low->values((array) $record);
			$legacy_low->id = $record->id;
			$legacy_low->save();
			
			$db_result = $ci->db->query("SELECT tag FROM pr_tags INNER JOIN tags ON 
				pr_tags.tag_id = tags.id WHERE pr_tags.pr_id = ?",
				array($record->id));
			
			$tags = array();
			foreach ($db_result->result() as $row)
				$tags[] = $row->tag;
			
			$content->set_tags($tags);
			
		}
		
		exit(111);
	}
	
}

?>