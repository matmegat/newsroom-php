<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Fin_Distribution_Update_Controller extends CLI_Base {
	
	const LOWER_LIMIT = 50;
	const UPPER_LIMIT = 100;
	
	public function index()
	{
		while (true)
		{
			// => 3 hours total from distribution
			$date_3_hours_ago  = Date::hours(-3)->format(Date::FORMAT_MYSQL);
			// => 12 hours total from distribution
			$date_9_hours_ago  = Date::hours(-3)->format(Date::FORMAT_MYSQL);
			// => 24 hours total from distribution
			$date_12_hours_ago = Date::hours(-12)->format(Date::FORMAT_MYSQL);
			// => 48 hours total from distribution
			$date_24_hours_ago = Date::hours(-24)->format(Date::FORMAT_MYSQL);
		
			$sql = "SELECT f.*, c.id as content_id
				FROM nr_content c
				LEFT JOIN nr_fin_distribution f ON 
				c.id = f.content_id
				WHERE c.is_published = 1 
				AND c.is_premium = 1
				AND c.date_publish < ?
				AND (f.update_status IS NULL 
					OR (f.update_status = 1 AND f.date_last_update < ?)
					OR (f.update_status = 2 AND f.date_last_update < ?)
					OR (f.update_status = 3 AND f.date_last_update < ?))
				LIMIT 1";
	
			set_time_limit(60);
			$db_result = $this->db->query($sql, 
				array($date_3_hours_ago, $date_9_hours_ago,
				      $date_12_hours_ago, $date_24_hours_ago));
			$db_record = $db_result->row();
			if (!$db_record) break;
			
			$m_fin_dist = Model_Fin_Distribution::from_object($db_record);
			$this->run_update($m_fin_dist);
		}
	}
	
	protected function run_update($m_fin_dist)
	{
		$ci =& get_instance();
		$m_content = Model_Content::find($m_fin_dist->content_id);
		$permalink = rawurlencode($ci->website_url($m_content->url()));
		
		if (!$m_fin_dist->random_limit)
			$m_fin_dist->random_limit = rand(
				static::LOWER_LIMIT, static::UPPER_LIMIT);
	
		$fin_check = $this->conf('fin_distribution_url');
		$fin_check = sprintf($fin_check, $permalink);
		$results_xml = @file_get_contents($fin_check);
		$results_dom = @simplexml_load_string($results_xml);
		$results_item = @$results_dom->channel->item;
	
		$critieria = array('content_id', $m_fin_dist->content_id);
		$service_count = Model_Fin_Distribution_Service::count($critieria);
	
		foreach ($results_item as $item)
		{
			if ($service_count >= $m_fin_dist->random_limit)
			{
				$m_fin_dist->date_last_update = Date::$now->format(Date::FORMAT_MYSQL);
				$m_fin_dist->update_status = 0;
				$m_fin_dist->save();
				return;
			}
			
			$fs_hash = substr(md5($item->source), 0, 16);
			$service = Model_Fin_Service::find($fs_hash);
			if (!$service) $service = $this->create_new_service($fs_hash, $item);				
			$dist_service = Model_Fin_Distribution_Service::find_id(
				array($m_fin_dist->content_id, $fs_hash));
			if ($dist_service) continue;
			
			$dist_service = new Model_Fin_Distribution_Service();
			$dist_service->attributed_company_id = $m_content->company_id;
			$dist_service->content_id = $m_fin_dist->content_id;
			$dist_service->fs_hash = $fs_hash;
			$dist_service->url = (string) $item->link;
			$dist_service->date_discovered = 
				$this->random_date_discovered($m_fin_dist->update_status);
				
			$dist_service->save();
			$service_count++;
		}
		
		$m_fin_dist->date_last_update = Date::$now->format(Date::FORMAT_MYSQL);
		$m_fin_dist->updates++;
		$m_fin_dist->save();
	}
	
	protected function random_date_discovered($update_status)
	{
		if ($update_status <= 1)
			  $seconds = rand(0, 43200);
		else $seconds = rand(0, 86400);
		return Date::seconds($seconds)->format(Date::FORMAT_MYSQL);
	}
	
	protected function create_new_service($fs_hash, $item)
	{
		set_time_limit(60);
		$service = new Model_Fin_Service();
		$service->hash = $fs_hash;
		$service->url = (string) $item->source;
		$service->name = (string) $item->title;
		$service->save();
		
		$ns_children = $item->children('pr', true);
		if (isset($ns_children->Logo))
		{
			$ch = curl_init();
			$bf = File_Util::buffer_file();
			$bfs = fopen($bf, 'wb');
			
			curl_setopt($ch, CURLOPT_URL, (string) $ns_children->Logo);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_FILE, $bfs);
			curl_exec($ch);
			curl_close($ch);
			fclose($bfs);
			
			$sim = Stored_Image::from_file($bf);
			if (!$sim->is_valid_image()) 
				return $service;
			
			$m_im = new Model_Image();
			$m_im->save();
			
			$v_sizes = $this->conf('v_sizes');			
			$sim_thumb = $sim->from_this_resized($v_sizes['dist-thumb']);
			$sim_finger = $sim->from_this_resized($v_sizes['dist-finger']);
			$m_im->add_variant($sim_thumb->save_to_db(), 'dist-thumb');
			$m_im->add_variant($sim_finger->save_to_db(), 'dist-finger');
			$service->logo_image_id = $m_im->id;
			$service->save();
		}
		
		return $service;
	}
	
}

?>