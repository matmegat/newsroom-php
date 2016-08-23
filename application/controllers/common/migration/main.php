<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main_Controller extends CIL_Controller {
	
	public function __on_execution_start()
	{
		// find user in old database
		$this->ldb = LEGACY::database();
		$result = $this->ldb->select('*')->from('users')
			->where('id', $this->session->get('userID'))->get();
		$user_record = $result->row();
		$this->user = $user_record;
		
		// no user online
		if (!$this->user)
			// redirect to login
			$this->redirect('common/migration/login');
	}
	
	public function index()
	{
		$vd = $this->vd;
		$user_is_duplicate = (bool) $this->ldb->from('users')
			->where('email', $this->user->email)
			->where('id != ', $this->user->id)
			->count_all_results();
			
		$sql = "SELECT pc.package FROM users lu
			LEFT JOIN user_package_deals upd
				ON lu.user_package_deal_id = upd.id
				AND upd.start_date <= NOW() 
				AND upd.end_date >= NOW()
			LEFT JOIN {$this->db->database}.nr_package_conversion pc 
				ON upd.deal_id = pc.legacy_deal_id
			WHERE lu.id = ?";
				
		$row = $this->ldb->query($sql, array($this->user->id))->row();
		$package = $vd->package = $row->package;
		
		$vd->is_reseller = (bool) $this->user->reseller;
		$vd->user_ready = !$this->user->reseller &&
			$this->user->active && !$this->user->verify_code &&
			preg_match('#^.+@[a-z0-9\-\.]+$#', $this->user->email) &&
			!$user_is_duplicate && !$this->user->is_migrated;
		$vd->is_migrated = $this->user->is_migrated;
		
		if ($package == 3)
		     $vd->access_level = 'platinum';
		else if ($package == 2)
		     $vd->access_level = 'gold';
		else if ($package == 1)
		     $vd->access_level = 'silver';
		else $vd->access_level = 'free';
		
		$date_7d = Date::days(-7)->format(Date::FORMAT_MYSQL);
		$date_2d = Date::days(-2)->format(Date::FORMAT_MYSQL);
		
		$sql = "SELECT 1 FROM prs p
			WHERE userid = ? AND
			(created > ? OR approve_time > ? OR
			releasedate > ? OR updated > ?
			OR status = -5 OR status = 10 OR status = 0) LIMIT 1";
				
		$vd->pr_within_7_days = (bool) $this->ldb->query($sql, array($this->user->id, 
			$date_7d, $date_7d, $date_7d, $date_7d))->num_rows();
		$vd->pr_within_2_days = (bool) $this->ldb->query($sql, array($this->user->id, 
			$date_2d, $date_2d, $date_2d, $date_2d))->num_rows();
		
		// check the number of migration processes running
		$lines = shell_exec("ps -eF | grep migration_process | grep -v grep");
		$lines = substr_count($lines, "\n");
		$vd->server_busy = $lines >= 2;
		
		$this->load->view('common/migration/header');
		$this->load->view('common/migration/index');
		$this->load->view('common/migration/footer');
	}
	
	public function exec()
	{
		if (!$this->input->post('confirm'))
			$this->redirect('common/migration');
		$this->set_redirect('common/migration/status');
		
		$user_id = (int) $this->user->id;
		$command = "php-cli index.php cli migration_process {$user_id}";
		$command = "nohup {$command} >/dev/null 2>&1 &";
		exec($command);
	}
	
	public function status()
	{
		$this->load->view('common/migration/header');
		$this->load->view('common/migration/status');
		$this->load->view('common/migration/footer');
	}
	
	public function status_poll()
	{
		$response = new stdClass();
		$response->finished = 0;
		
		// migration is finished => update session
		if ($this->user->is_migration_finished)
		{
			LEGACY_Auth::logout();
			Auth::login(Model_User::find($this->user->id));
			$response->finished = 1;
			
			// load feedback message for the user
			$feedback_view = 'common/migration/welcome_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
		}
		
		// load the latest messages from data cache
		$name = "migration_status_{$this->user->id}";
		$statuses = @unserialize(Data_Cache::read($name));
		if (!is_array($statuses)) $statuses = array();
		Data_Cache::write($name, serialize(array()), 300);
		$response->statuses = $statuses;
		
		return $this->json($response);
	}
	
}

?>