<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('manage/base');

class Listing_Base extends Manage_Base {
	
	protected $listing_section = null;
	protected $listing_sub_section = null;
	protected $listing_chunk_size = 10;

	public function all($chunk = 1)
	{
		$this->listing($chunk, 'all');
	}
	
	public function published($chunk = 1)
	{
		$filter = 'c.is_published = 1';
		$this->listing($chunk, 'published', null, $filter);
	}
	
	public function scheduled($chunk = 1)
	{
		$filter = 'c.is_published = 0 AND c.is_draft = 0 AND c.is_under_review = 0';
		$this->listing($chunk, 'scheduled', null, $filter);
	}
	
	public function draft($chunk = 1)
	{
		$filter = 'c.is_published = 0 AND c.is_draft = 1';
		$this->listing($chunk, 'draft', null, $filter);
	}
	
	protected function process_results($results)
	{
		return $results;
	}
	
	protected function listing($chunk, $status, $type = null, $filter = 1, $sql = null)
	{
		if ($type === null)
		{
			$type = $this->uri->segment(3);
			if (!Model_Content::is_allowed_type($type))
				show_404();
		}
		
		if ($type !== Model_Content::TYPE_PR
		    && !$this->newsroom->is_active)
		{
			// load feedback message for the user
			$feedback_view = 'manage/publish/partials/feedback/not_active_content_warning';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->use_feedback($feedback);
		}
		
		if ($type === Model_Content::TYPE_PR)
		{
			$this->vd->pr_credits_premium = Auth::user()->pr_credits_premium();
			$this->vd->pr_credits_basic = Auth::user()->pr_credits_basic();
		}
		
		$section = $this->listing_section;
		$sub = $this->listing_sub_section;
		
		$company_id = $this->newsroom->company_id;
				
		$this->load->view('manage/header');
		$this->load->view("manage/{$section}/menu");
		$this->load->view('manage/pre-content');
		
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size($this->listing_chunk_size);
		$limit_str = $chunkination->limit_str();
		
		if ($sql === null)
		{
			if (Model_Content::is_allowed_type($type))
			{
				$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM nr_content c 
					INNER JOIN nr_pb_{$type} tl ON c.company_id = ? 
					AND c.type = ? AND c.id = tl.content_id
					AND {$filter} ORDER BY c.id DESC {$limit_str}";	
			}
			else
			{
				$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM nr_content c 
					LEFT JOIN (
						SELECT t.content_id, GROUP_CONCAT(t.value) AS tags
						FROM nr_content_tag t GROUP BY t.content_id
					) t ON t.content_id = c.id
					WHERE c.company_id = ? AND {$filter} 
					ORDER BY c.id DESC {$limit_str}";
			}
		}
		
		$query = $this->db->query($sql, array($company_id, $type));
		$results = Model_Content::from_db_all($query);
		
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
		
		$url_format   = gstring("manage/{$section}/{$sub}/{$type}/{$status}/-chunk-");
		$listing_view = "manage/{$section}/{$sub}/{$type}";
		$chunkination->set_url_format($url_format);
		$chunkination->set_total($total_results);
		$this->vd->chunkination = $chunkination;
		
		$this->vd->results = $this->process_results($results);
		$this->load->view($listing_view);
		$this->load->view('manage/post-content');
		$this->load->view('manage/footer');
	}
	
}

?>