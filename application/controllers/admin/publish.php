<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class Publish_Controller extends Admin_Base {

	const LISTING_CHUNK_SIZE = 20;
	
	public function __construct()
	{
		parent::__construct();
		$this->vd->title[] = 'iPublish';
	}

	public function index($type = null, $status = null, $chunk = 1)
	{
		if ($type === null) $this->redirect(gstring('admin/publish/pr/all'));
		if ($status === null) $this->redirect(gstring("admin/publish/{$type}/all"));
		if (!$this->has_under_review($type) && $status === 'under_review')
			$this->redirect(gstring("admin/publish/{$type}/all"));
		
		if (!Model_Content::is_allowed_type($type)) show_404();
		if (!$this->is_allowed_status($status)) show_404();	
		
		$filters = array(
			'all' => null,
			'draft' => 'c.is_published = 0 AND c.is_draft = 1',
			'published' => 'c.is_published = 1',
			'scheduled' => 'c.is_published = 0 AND c.is_draft = 0 AND c.is_under_review = 0',
			'under_review' => 'c.is_under_review = 1',
		);
		
		$chunkination = new Chunkination($chunk);
		$chunkination->set_chunk_size(static::LISTING_CHUNK_SIZE);
		$url_format = gstring("admin/publish/{$type}/{$status}/-chunk-");
		$chunkination->set_url_format($url_format);
		$results = $this->fetch_results($type, $chunkination, $filters[$status]);
		$this->vd->title[] = Model_Content::full_type_plural($type);	
		
		if ($chunkination->is_out_of_bounds()) 
		{
			// out of bounds so redirect to first
			$url = "admin/publish/{$type}/{$status}";
			$this->redirect(gstring($url));
		}
			
		$this->vd->type = $type;
		$this->vd->status = $status;		
		$this->render_list($chunkination, $results);
	}
	
	public function approve($content_id, $view = false)
	{
		$content = Model_Content::find($content_id);
		if (!$content) $this->redirect('admin/publish');
		$content->is_approved = 1;
		$content->is_rejected = 0;
		$content->is_under_review = 0;
		$content->is_draft = 0;
		$content->save();
		
		if (!$content->is_legacy)
		{
			$sch_n = new Model_Scheduled_Notification();
			$sch_n->related_id = $content->id;
			$sch_n->class = Model_Scheduled_Notification::CLASS_CONTENT_APPROVED;
			$sch_n->user_id = $content->owner()->id;
			$sch_n->save();
		}
		
		$ap_event = new Iella_Event();
		$ap_event->data->id = $content->id;
		$ap_event->emit('content_approved');
		
		// load feedback message for the user
		$feedback_view = 'admin/publish/partials/approve_feedback';
		$feedback = $this->load->view($feedback_view, 
			array('content' => $content), true);
		$this->add_feedback($feedback);
		
		// redirect to view the content
		if ($view) $this->redirect($content->url());
		
		// redirect back to the last location
		$url = value_or_null($_SERVER['HTTP_REFERER']);
		// redirect back to list of content to be reviewed
		if (!$url) $url = "admin/publish/{$content->type}/under_review";
		$this->redirect($url, false);
	}
	
	public function reject($content, $view = false)
	{
		$content = Model_Content::find($content);
		if (!$content) $this->redirect('admin/publish');
		$content->load_content_data();
		$this->vd->canned = $canned = Model_Canned::find_all();		
		$this->vd->content = $content;
		
		if ($this->input->post('confirm'))
		{
			$content->is_approved = 0;
			$content->is_rejected = 1;
			$content->is_under_review = 0;
			$content->is_published = 0;
			$content->is_draft = 1;
			$content->save();
			
			$re_event = new Iella_Event();
			$re_event->data->id = $content->id;
			$re_event->emit('content_rejected');
			
			// consumed is not always present => calculated 
			if ($consumed = Model_Limit_PR_Consumed::find($content->id))
				$consumed->restore($content->owner());
			
			if (!$content->is_legacy)
			{
				$sch_n = new Model_Scheduled_Notification();
				$sch_n->related_id = $content->id;
				$sch_n->class = Model_Scheduled_Notification::CLASS_CONTENT_REJECTED;
				$sch_n->user_id = $content->owner()->id;
				$sch_n->data = serialize($this->input->post());
				$sch_n->save();
			}
			
			// load feedback message for the user
			$feedback_view = 'admin/publish/partials/reject_feedback';
			$feedback = $this->load->view($feedback_view, 
				array('content' => $content), true);
			$this->add_feedback($feedback);
			
			// redirect to view the content
			if ($view) $this->redirect($content->url());
			
			// redirect back to the last location
			$url = value_or_null($this->input->post('last-location'));
			if ($url) $this->redirect($url, false);
						
			// redirect back to list of content to be reviewed
			$url = "admin/publish/{$content->type}/under_review";
			$this->redirect($url);
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/publish/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/publish/reject');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
	public function edit($content_id)
	{
		$content = Model_Content::find($content_id);
		if (!$content) $this->redirect('admin/publish');
		$url = "manage/publish/{$content->type}/edit/{$content_id}";
		$this->admin_mode_from_company($content->company_id, $url);
	}
	
	public function delete($content_id)
	{
		$content = Model_Content::find($content_id);
		if (!$content) $this->redirect('admin/publish');
		$url = "manage/publish/{$content->type}/delete/{$content_id}";
		$this->admin_mode_from_company($content->company_id, $url);
	}
	
	public function stats($content_id)
	{
		$content = Model_Content::find($content_id);
		if (!$content) $this->redirect('admin/publish');
		$url = "manage/analyze/content/view/{$content_id}";
		$this->admin_mode_from_company($content->company_id, $url);
	}
	
	protected function fetch_results($type, $chunkination, $filter = null)
	{
		if (!$filter) $filter = 1;
		$limit_str = $chunkination->limit_str();
		$use_additional_tables = false;
		$additional_tables = null;
		$this->vd->filters = array();	
		
		if ($filter_search = $this->input->get('filter_search'))
		{
			$this->create_filter_search($filter_search);
			// restrict search results to these terms
			$search_fields = array('c.title');
			$terms_filter = sql_search_terms($search_fields, $filter_search);
			$filter = "{$filter} AND {$terms_filter}";
		}
		
		if ($filter_user = (int) $this->input->get('filter_user'))
		{
			$this->create_filter_user($filter_user);	
			// restrict search results to this user
			$filter = "{$filter} AND u.id = {$filter_user}";
			$use_additional_tables = true;
		}
		
		if ($filter_company = (int) $this->input->get('filter_company'))
		{
			$this->create_filter_company($filter_company);	
			// restrict search results to this user
			$filter = "{$filter} AND cm.id = {$filter_company}";
			$use_additional_tables = true;
		}
		
		// add sql for connecting in additional tables
		if ($use_additional_tables) $additional_tables = 
			"INNER JOIN nr_company cm ON c.company_id = cm.id
			 INNER JOIN nr_user u ON cm.user_id = u.id";
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS c.id FROM 
			nr_content c {$additional_tables}
			WHERE c.type = ? AND {$filter}
			/* prevent legacy draft showing up */
			AND (c.is_draft = 0 OR c.is_legacy = 0)
			ORDER BY c.id DESC {$limit_str}";
			
		$query = $this->db->query($sql, array($type));
		$id_list = array();
		foreach ($query->result() as $row)
			$id_list[] = (int) $row->id;
		
		// no results found so exit
		if (!$id_list) return array();
				
		$id_str = sql_in_list($id_list);
		$total_results = $this->db
			->query("SELECT FOUND_ROWS() AS count")
			->row()->count;
			
		$chunkination->set_total($total_results);
		if ($chunkination->is_out_of_bounds())
			return array();
			
		$sql = "SELECT c.*,
			cm.name AS o_company_name,
			cm.id AS o_company_id,
			u.first_name AS o_user_first_name,
			u.last_name AS o_user_last_name,
			u.email AS o_user_email,
			u.id AS o_user_id
			FROM nr_content c
			LEFT JOIN nr_company cm
			ON c.company_id = cm.id
			LEFT JOIN nr_user u 
			ON cm.user_id = u.id
			WHERE c.id IN ({$id_str}) 
			ORDER BY c.id DESC";
			
		$query = $this->db->query($sql);
		$results = Model_Content::from_db_all($query);		
		
		return $results;
	}
	
	protected function render_list($chunkination, $results)
	{
		$this->vd->chunkination = $chunkination;
		$this->vd->results = $results;
		
		$reject_modal = new Modal();
		$this->add_eob($reject_modal->render(800, 600));
		$this->vd->reject_modal_id = $reject_modal->id;
		
		$this->load->view('admin/header');
		$this->load->view('admin/publish/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/publish/list');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
	protected function is_allowed_status($status)
	{
		if ($status === 'all') return true;
		if ($status === 'under_review') return true;
		if ($status === 'published') return true;
		if ($status === 'scheduled') return true;
		if ($status === 'draft') return true;
		return false;
	}
	
	protected function has_under_review($type)
	{
		if ($type === Model_Content::TYPE_PR) 
			return true;
		return false;
	}

}

?>