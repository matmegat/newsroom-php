<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('admin/base');

class View_Controller extends Admin_Base {

	public function index($user_id = null)
	{
		if ($this->input->post('save'))
		{
			if (!($user = Model_User::find($user_id)))
			{
				$user = Model_User::create();
				$pass = Model_User::generate_password();
				$user->set_password($pass);
			}
			
			$email = strtolower($this->input->post('email'));
			$is_active = $this->input->post('is_active');
			$is_admin = $this->input->post('is_admin');
			$notes = value_or_null($this->input->post('notes'));
			$first_name = value_or_null($this->input->post('first_name'));
			$last_name = value_or_null($this->input->post('last_name'));
			
			// if we have duplicate email address then set null
			// and let admin know that the user must be updated
			if (($other_user = Model_User::find_email($email)) && 
			    $other_user->id != $user->id)
			{
				$email = null;
				// load feedback message for the user
				$feedback_view = 'admin/users/partials/duplicate_email_feedback';
				$feedback = $this->load->view($feedback_view, null, true);
				$this->add_feedback($feedback);
			}
			
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			if ($user->email != $email) 
				$user->is_verified = 1;
			$user->email = $email;
			$user->is_admin = $is_admin;
			$user->is_active = $is_active;
			$user->notes = $notes;
			$user->save();
			
			$ac_class = (array) $this->input->post('ac_class');
			$ac_amount = (array) $this->input->post('ac_amount');
			$ac_expires = (array) $this->input->post('ac_expires');
			
			foreach ($ac_class as $k => $class)
			{
				if ($class == 'pr_premium' || $class == 'pr_basic')
					$held = new Model_Limit_PR_Held();				
				if ($class == 'pr_premium')
					$held->type = Model_Content::PREMIUM;				
				if ($class == 'pr_basic')
					$held->type = Model_Content::BASIC;				
				if ($class == 'email')
					$held = new Model_Limit_Email_Held();				
				if ($class == 'newsroom')
					$held = new Model_Limit_Newsroom_Held();
				
				$held->user_id = $user->id;
				$held->amount_total = $ac_amount[$k];
				$held->date_expires = $ac_expires[$k];
				$held->save();
			}
			
			// load feedback message for the user
			$feedback_view = 'admin/users/partials/save_feedback';
			$feedback = $this->load->view($feedback_view, null, true);
			$this->add_feedback($feedback);
			
			// redirect back to the user page 
			$this->redirect("admin/users/view/{$user->id}");
		}
		
		if (!($user = Model_User::find($user_id)))
			$user = new Model_User();
		$this->vd->user = $user;
		
		if ($user->id)
		{
			$this->vd->credit_data = new stdClass();
			$this->vd->credit_data->pr_premium = $user->pr_credits_premium_stat();
			$this->vd->credit_data->pr_basic = $user->pr_credits_basic_stat();
			$this->vd->credit_data->email = $user->email_credits_stat();
			$this->vd->credit_data->newsroom = $user->newsroom_credits_stat();
			
			$pr_premium_held = Model_Limit_PR_Held::find_collection($user, Model_Content::PREMIUM);
			$pr_premium_held = $pr_premium_held->collection();
			$this->vd->credit_data->pr_premium->held = $pr_premium_held;
			
			$pr_basic_held = Model_Limit_PR_Held::find_collection($user, Model_Content::BASIC);
			$pr_basic_held = $pr_basic_held->collection();
			$this->vd->credit_data->pr_basic->held = $pr_basic_held;
			
			$email_held = Model_Limit_Email_Held::find_collection($user);
			$email_held = $email_held->collection();
			$this->vd->credit_data->email->held = $email_held;
			
			$newsroom_held = Model_Limit_Newsroom_Held::find_collection($user);
			$newsroom_held = $newsroom_held->collection();
			$this->vd->credit_data->newsroom->held = $newsroom_held;
			
			$sql = "SELECT COUNT(1) AS count FROM nr_content c 
				INNER JOIN nr_company cm ON cm.user_id = ?
				AND c.company_id = cm.id 
				AND c.is_published = 1";
			$dbr = $this->db->query($sql, array($user->id));
			$this->vd->published_count = $dbr->row()->count;
			
			$sql = "SELECT COUNT(1) AS count FROM nr_campaign c 
				INNER JOIN nr_company cm ON cm.user_id = ?
				AND c.company_id = cm.id";
			$dbr = $this->db->query($sql, array($user->id));
			$this->vd->campaign_count = $dbr->row()->count;
			
			$sql = "SELECT COUNT(1) AS count 
				FROM nr_company cm WHERE cm.user_id = ?";
			$dbr = $this->db->query($sql, array($user->id));
			$this->vd->companies_count = $dbr->row()->count;
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/users/menu');
		$this->load->view('admin/pre-content');
		$this->load->view('admin/users/view');
		$this->load->view('admin/post-content');
		$this->load->view('admin/footer');
	}
	
	public function reset($user_id = null)
	{
		if (!$this->input->post('confirm')) return;
		if (!($user = Model_User::find($user_id)))
			$this->json(null);
		$password = Model_User::generate_password();
		$user->set_password($password);
		$user->save();
		
		$response = new stdClass();
		$response->password = $password;
		$this->json($response);
	}
	
}

?>	