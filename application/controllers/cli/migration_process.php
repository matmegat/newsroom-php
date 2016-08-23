<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Migration_Process_Controller extends CLI_Base {
	
	protected $uid = null;
	protected $log_file = null;
	protected $status_file = null;
	
	const FEATURE_LOGO = 1;
	const FEATURE_IMAGES = 2;
	const FEATURE_KEYWORD_LINKS = 3;
	// const FEATURE_ADS = 3;
	// const FEATURE_LINKS = 5;
	const FEATURE_FILES = 7;
	
	public function __construct()
	{
		parent::__construct();
		$this->log_file = 'application/logs/migration.log';
		if (!is_dir(dirname($this->log_file)))
			mkdir(dirname($this->log_file));
	}
	
	public function index($uid)
	{		
		$this->uid = $uid;
		try { $this->process($uid); }
		catch (Exception $e)
		{ 
			$message = $e->getMessage();
			if (!$message) return;
			$this->error($message);
		}
	}
	
	public function process($uid)
	{
		// --------------------
		// initial sanity check
		// --------------------
	
		$uid = (int) $uid;
		$ldb = LEGACY::database();
		$luser = $ldb->select('*')->from('users')
			->where('id', $uid)
			->get()->row();
			
		if ($luser->is_migrated)
		{
			$this->error('user is marked as done');
			throw new Exception();
			return;
		}
		
		// status message for user and log
		$this->status('started');
		$this->trace('started');
		
		// reset time limit 
		set_time_limit(300);
		
		// large 512M memory limit for images+
		ini_set('memory_limit', 536870912);
		
		// set user as migrated and void pass
		$junk_pass = md5(microtime(true));
		$junk_pass = "MIG_V2_{$junk_pass}";
		$ldb->where('id', $luser->id)
		->update('users', array(
			'is_migrated' => '1',
			'password' => $junk_pass,
		));
		
		// create user within new system
		$user_base = new Model_User_Base();
		$user_base->id = $luser->id;
		$user_base->first_name = value_or_null($luser->fname);
		$user_base->last_name = value_or_null($luser->lname);
		$user_base->email = value_or_null($luser->email);
		$user_base->is_active = 1;
		$user_base->is_verified = 1;
		$user_base->is_admin = (int) ((bool) $luser->admin);
		$user_base->is_reseller = (int) ((bool) $luser->reseller);
		$user_base->date_created = $luser->created;
		$user_base->notes = $luser->notes;
		$user_base->save();
		
		// load real user model instead of user_base
		$user = Model_User::find($user_base->id);
		$user_id = $user->id;
		$user->set_password($luser->password);
		$user->save();
		
		// status message for user and log
		$this->status('migrated user profile');
		$this->trace('user account migrated');
		
		$uploads_dir = $this->conf('compat_dir');
		$uploads_dir = "{$uploads_dir}/Uploads/";
		
		// sql to fetch unmigrated pr
		$select_PR = "SELECT * FROM prs 
			WHERE	userid = {$user_id} 
			AND is_migrated = 0 
			ORDER BY id ASC
			LIMIT 1";
			
		while (true)
		{
			// reset time limit 
			set_time_limit(300);
			sleep(1);
			
			if (!($prs = Model_PRS::from_db($ldb->query($select_PR)))) break;			
			// clean up the text based data
			foreach ($prs as $k => $v) $prs->{$k} = to_utf8_3b($v);
			$prs->c_name = trim($prs->c_name);			
			
			// attempt to find an existing company
			$sql = "SELECT * FROM nr_newsroom WHERE
				user_id = {$user_id} AND company_name LIKE ?";
			$dbr = $this->db->query($sql, array($prs->c_name));
			$newsroom = Model_Newsroom::from_db($dbr);
			
			if (!$newsroom)
			{
				// create a new company and profile
				// create company/newsroom for $user_id
				$newsroom = Model_Newsroom::create($user_id, $prs->c_name);
				$newsroom->save();
				
				// create a new company profile and assign to company
				$company_profile = new Model_Company_Profile();
				$company_profile->company_id = $newsroom->company_id;
				 
				// set the values for the company profile
				// - recommended: use value_or_null if the value might be empty
				// - the address1 and address2 can be assigned to address_street
				// and address_city. the state/county/apt-number can all
				// be included in the street/city lines and it will work fine.
				// it is not nessecary to parse the address lines so that
				// you can fill the address_apt_suite and address_state fields.
				$company_profile->address_street = value_or_null($prs->c_address1);
				$company_profile->address_city = value_or_null($prs->c_address2);
				$company_profile->address_zip = value_or_null($prs->c_zip);
				$company_profile->website = value_or_null($prs->c_url);
				$company_profile->phone = value_or_null($prs->c_phone);
				$company_profile->description = value_or_null($this->vd->pure($prs->c_details));
				 
				// the id of the country from nr_country (or countries table in old db)
				$company_profile->address_country_id = value_or_null($prs->countryid);
				 
				// social media accounts for the company
				// - there are classes you can use to parse these from URLs if needed
				$company_profile->soc_twitter = value_or_null(Social_Twitter_Profile::parse_id($prs->socialtwitter));
				$company_profile->soc_facebook = value_or_null(Social_Facebook_Profile::parse_id($prs->socialfacebook));
				$company_profile->soc_gplus = value_or_null(Social_GPlus_Profile::parse_id($prs->socialgoogle));
				$company_profile->soc_youtube = value_or_null(Social_Youtube_Profile::parse_id($prs->socialyoutube));
				 
				// save to database
				$company_profile->save();
			}
			else
			{
				// update company profile if needed
				$company_profile = Model_Company_Profile::find($newsroom->company_id);
				
				// set the values for the company profile
				// - recommended: use value_or_null if the value might be empty
				// - the address1 and address2 can be assigned to address_street
				// and address_city. the state/county/apt-number can all
				// be included in the street/city lines and it will work fine.
				// it is not nessecary to parse the address lines so that
				// you can fill the address_apt_suite and address_state fields.
				if ($prs->c_address1) $company_profile->address_street = value_or_null($prs->c_address1);
				if ($prs->c_address2) $company_profile->address_city = value_or_null($prs->c_address2);
				if ($prs->c_zip) $company_profile->address_zip = value_or_null($prs->c_zip);
				if ($prs->c_url) $company_profile->website = value_or_null($prs->c_url);
				if ($prs->c_phone) $company_profile->phone = value_or_null($prs->c_phone);
				if ($prs->c_details) $company_profile->description = value_or_null($this->vd->pure($prs->c_details));
				
				// the id of the country from nr_country (or countries table in old db)
				if ($prs->countryid) $company_profile->address_country_id = value_or_null($prs->countryid);
				
				// social media accounts for the company
				// - there are classes you can use to parse these from URLs if needed
				if ($prs->socialtwitter) $company_profile->soc_twitter = value_or_null(Social_Twitter_Profile::parse_id($prs->socialtwitter));
				if ($prs->socialfacebook) $company_profile->soc_facebook = value_or_null(Social_Facebook_Profile::parse_id($prs->socialfacebook));
				if ($prs->socialgoogle) $company_profile->soc_gplus = value_or_null(Social_GPlus_Profile::parse_id($prs->socialgoogle));
				if ($prs->socialyoutube) $company_profile->soc_youtube = value_or_null(Social_Youtube_Profile::parse_id($prs->socialyoutube));
				
				// save to database
				$company_profile->save();
			}
			
			if (!$newsroom->company_contact_id)
			{
				// create a new press contact and assign to company
				$company_contact = new Model_Company_Contact();
				$company_contact->company_id = $newsroom->company_id;
				
				if ($prs->c_contact)
				{
					// name is available so use it
					$company_contact->name = $prs->c_contact;
					$company_contact->title = "Press Contact";
				}
				else
				{
					// use "press contact" as name instead 
					$company_contact->name = "Press Contact";
					$company_contact->title = null;
				}
				 
				// this should to be the company email
				// unless you know the email address of the
				// press contact separately
				$company_contact->email = $user->email;
				 
				// save the press contact
				$company_contact->save();
				 
				// assign this contact as the press/main contact
				// and save the changes to the company
				$newsroom->company_contact_id = $company_contact->id;
				$newsroom->save();
			}
			else if ($prs->c_contact)
			{
				$company_contact = Model_Company_Contact::find($newsroom->company_contact_id);				
				// name is available so use it
				$company_contact->name = $prs->c_contact;
				$company_contact->title = "Press Contact";
				$company_contact->save();
			}
			
			$prf = array();			
			$sql = "SELECT * FROM pr_features WHERE
				prid = {$prs->id} ORDER BY `index` ASC";
			$dbr = $ldb->query($sql);
			foreach ($dbr->result() as $row)
				$prf[$row->featureid][] = $row->value;
				
			if (($logo_file = @$prf[static::FEATURE_LOGO][0]))
			{
				$logo_file = "{$uploads_dir}{$logo_file}";
				
				// this is compatability with dev environment
				if (strpos($uploads_dir, 'net.staite.dev') !== false)
				{
					// download the logo file from i-newswire
					$logo_file_basename = basename($logo_file);
					$inews_logo_url = "http://www.i-newswire.com/Uploads/{$logo_file_basename}";
					@copy($inews_logo_url, $logo_file);
				}
				
				if (Image::is_valid_file($logo_file))
				{
					// import the logo image into the system
					$logo_im = LEGACY_Image::import("logo", $logo_file);
					 
					// assign to the new company and save
					$logo_im->company_id = $newsroom->company_id;
					$logo_im->save();
					
					if (!($newsroom_custom = Model_Newsroom_Custom::find($newsroom->company_id)))
					{
						// create newsroom customization object
						// and assign to the new company
						$newsroom_custom = new Model_Newsroom_Custom();
						$newsroom_custom->company_id = $newsroom->company_id;
					}
					 
					// set it to use the new logo image and save
					$newsroom_custom->logo_image_id = $logo_im->id;
					$newsroom_custom->save();
				}
			}
			
			// create a new content object
			$m_content = new Model_Content();
			$m_content->company_id = $newsroom->company_id;
			$m_content->type = Model_Content::TYPE_PR;
			
			// status should be approved or rejected
			if ($prs->status != 1 && $prs->status != 2)
				$this->error("invalid status {$prs->status} ({$prs->id})");
			
			// convert to rejected because didn't pay!
			if ($prs->packageid > 1 && $prs->payment_status < 2)
				$prs->status = 1;
			
			// approved, published if status is 2, draft otherwise.
			$m_content->is_published = (int) ($prs->status == 2);
			$m_content->is_approved = (int) ($prs->status == 2);			
			$m_content->is_draft = (int) (!$m_content->is_published);
			$m_content->is_rejected = (int) (!$m_content->is_published);
			
			// no schedule for staff review
			$m_content->is_under_review = 0;
			 
			// PR from legacy source
			$m_content->is_legacy = 1;
			
			// premium if packageid is 2 or 3 (1 is basic)
			$m_content->is_premium = (int) ($prs->packageid > 1);
			
			// it was a scheduled release so use that date/time 
			if (preg_match('#[1-9]#', $prs->releasedate))
			{
				$prs->publishingtime = (int) $prs->publishingtime;
				$dt_date_publish = new DateTime("{$prs->releasedate} {$prs->publishingtime}:00:00");
				$m_content->date_publish = $dt_date_publish->format(Date::FORMAT_MYSQL);
			} 
			// fallback to approve time and created time
			else if (preg_match('#[1-9]#', $prs->approve_time))
			     $m_content->date_publish = $prs->approve_time;
			else $m_content->date_publish = $prs->created;	
			$m_content->date_created = $prs->created;
			 
			// set the title and slug
			$m_content->title = $prs->title;
			$m_content->title_to_slug();
			 
			// save the m_content
			$m_content->save();
			 
			// create new content data and link to content
			$content_data = new Model_Content_Data();
			$content_data->content_id = $m_content->id;
			 
			// content is stored in html format
			// - use nl2p() or nl2br() to convert to paragraph or line breaks
			// - convert bbcode to html for links (and anything else)
			// - use the pure() method as shown below to ensure safe html
			$content = nl2p(trim($prs->detail));
			$content = $this->vd->pure(preg_replace(
				'#\[url[:=]([^\s\]]+)\s*[^\]]*\]([^\[]+)\[/url\]#is', 
				'<a href="$1" target="_blank">$2</a>',
				$content));
			 
			// the content and summary of the PR
			$content_data->content = $content;
			$content_data->summary = $this->vd->pure($prs->summary);
			
			// the "keyword links" to additional links
			if (($additional_link_1 = @$prf[static::FEATURE_KEYWORD_LINKS][0]))
			{
				$additional_link_1 = unserialize($additional_link_1);
				$content_data->rel_res_pri_title = value_or_null($additional_link_1['keyword']);
				$content_data->rel_res_pri_link = value_or_null($additional_link_1['link']);
			}
			
			// the "keyword links" to additional links
			if (($additional_link_2 = @$prf[static::FEATURE_KEYWORD_LINKS][1]))
			{
				$additional_link_2 = unserialize($additional_link_2);
				$content_data->rel_res_sec_title = value_or_null($additional_link_2['keyword']);
				$content_data->rel_res_sec_link = value_or_null($additional_link_2['link']);
			}
			 
			// save content data
			$content_data->save();
			
			// create object to store data specific
			// to the Press Release content type
			// and link it to the content
			$content_data_PR = new Model_PB_PR();
			$content_data_PR->content_id = $m_content->id;
			 
			// the category ID
			// - use the parent category ID if
			// there is no sub category
			$content_data_PR->cat_1_id = $prs->sub_cat
				? $prs->sub_cat
				: $prs->parent_cat;
			 
			// some videos do not use youtube!!!!
			// * this can also contain html!
			if (preg_match('#youtu\.?be#is', $prs->videourl))
			{
				// set the video details (if needed)
				$content_data_PR->web_video_provider = Video::PROVIDER_YOUTUBE;
				$video = Video::get_instance(Video::PROVIDER_YOUTUBE);
				$content_data_PR->web_video_id = $video->parse_video_id($prs->videourl);
			}
			 
			// save the PR specific data
			$content_data_PR->save();
			
			// additional files #1
			if (($add_file_1 = @$prf[static::FEATURE_FILES][0]) && 
			    preg_match('/^(.+)###(.+)$/', $add_file_1, $match))
			{
				$add_file_1 = $match[1];
				$add_name_1 = $match[2];
				$add_file_1 = "{$uploads_dir}{$add_file_1}";
				
				// this is compatability with dev environment
				if (strpos($uploads_dir, 'net.staite.dev') !== false)
				{
					// download the logo file from i-newswire
					$add_file_1_basename = basename($add_file_1);
					$inews_add_file_1_url = "http://www.i-newswire.com/Uploads/{$add_file_1_basename}";
					@copy($inews_add_file_1_url, $add_file_1);
				}
				
				// add the file into the system
				$stored_file_1 = LEGACY_File::import($add_file_1);
				// store the file details into the content data object
				$content_data_PR->stored_file_id_1 = $stored_file_1->id;
				$content_data_PR->stored_file_name_1 = $add_name_1;
			}
			
			// additional files #2
			if (($add_file_2 = @$prf[static::FEATURE_FILES][1]) && 
			    preg_match('/^(.+)###(.+)$/', $add_file_2, $match))
			{
				$add_file_2 = $match[1];
				$add_name_2 = $match[2];
				$add_file_2 = "{$uploads_dir}{$add_file_2}";
				
				// this is compatability with dev environment
				if (strpos($uploads_dir, 'net.staite.dev') !== false)
				{
					// download the logo file from i-newswire
					$add_file_2_basename = basename($add_file_2);
					$inews_add_file_2_url = "http://www.i-newswire.com/Uploads/{$add_file_2_basename}";
					@copy($inews_add_file_2_url, $add_file_2);
				}
				
				// add the file into the system
				$stored_file_2 = LEGACY_File::import($add_file_2);
				// store the file details into the content data object
				$content_data_PR->stored_file_id_2 = $stored_file_2->id;
				$content_data_PR->stored_file_name_2 = $add_name_2;
			}
			 
			// re-save the content data
			$content_data_PR->save();
			
			// additional images 
			$images = array();
			foreach ((array) @$prf[static::FEATURE_IMAGES] as $k => $im_file)
			{
				$im_file = "{$uploads_dir}{$im_file}";
				
				// this is compatability with dev environment
				if (strpos($uploads_dir, 'net.staite.dev') !== false)
				{
					// download the image file from i-newswire
					$im_file_basename = basename($im_file);
					$inews_im_url = "http://www.i-newswire.com/Uploads/{$im_file_basename}";
					@copy($inews_im_url, $im_file);
				}
				
				if (Image::is_valid_file($im_file))
				{
					// import the images into the new system and add to array
					$images[] = LEGACY_Image::import('related', $im_file);
				}
			}
			 
			// add images to content
			$m_content->set_images($images);
			
			$tags = array();
			// fetch all tags for PR
			$sql_tags = "SELECT t.tag FROM pr_tags pt INNER JOIN 
				tags t ON pr_id = {$prs->id} AND pt.tag_id = t.id";
			$dbr = $ldb->query($sql_tags);
			foreach ($dbr->result() as $row)
				$tags[] = $row->tag;
			
			// add tags to content
			$m_content->set_tags($tags);
			
			// mark pr as migrated
			$sql = "UPDATE prs SET is_migrated = 1, 
				migrated_content_id = {$m_content->id} 
				WHERE id = {$prs->id}";
			$ldb->query($sql);
			
			// status message for user and log to system
			$this->status("migrated press release {$prs->id}");
			$this->trace("press release migrated ({$prs->id})");
		}
		
		// ---------------
		// clean and trace
		// ---------------
		
		$ldb->where('id', $luser->id)
		->update('users', array(
			'is_migration_finished' => '1',
		));
		
		$this->trace('finished');
		$this->status('finished');
		return;
	}
	
	protected function write($message, $class)
	{
		$date = new DateTime();
		$date = $date->format(Date::FORMAT_MYSQL);		
		$format = '[%s] [%s] user: %d, message: %s%s';
		$message = sprintf($format, $date, $class, 
			$this->uid, $message, PHP_EOL);		
		
		echo $message; ob_flush(); flush();
		$handle = fopen($this->log_file, 'a+');
		fwrite($handle, $message);
		fclose($handle);
	}
	
	protected function trace($message)
	{
		$this->write($message, 'trace');
	}
	
	protected function error($message)
	{
		$this->write($message, 'error');
	}
	
	protected function status($message)
	{
		$date = new DateTime();
		$date = $date->format(Date::FORMAT_MYSQL);
		$format = '[%s] %s%s';
		$message = sprintf($format, $date, $message, PHP_EOL);		
		$name = "migration_status_{$this->uid}";
		$statuses = @unserialize(Data_Cache::read($name));
		if (!is_array($statuses))
			$statuses = array();
		array_unshift($statuses, $message);
		$store = serialize($statuses);
		Data_Cache::write($name, $store, 300);
	}
	
}

// model for quick access to PRS
class Model_PRS extends Model {
	protected static $__table = 'prs';
}

// model for quick access to PR_Features
class Model_PRF extends Model {
	protected static $__table = 'pr_features';
}

function handle_error($errno, $errstr, $errfile, $errline, array $errcontext)
{
	// error was suppressed 
	if (0 === error_reporting()) return false;
	$errfile = str_replace(getcwd(), '', $errfile);
	throw new Exception("$errstr {$errfile} #{$errline}");
}

set_error_handler('handle_error', error_reporting());

?>