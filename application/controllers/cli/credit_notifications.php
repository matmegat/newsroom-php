<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

load_controller('cli/base');

class Credit_Notifications_Controller extends CLI_Base {
	
	public function index()
	{
		proc_nice(10);
		
		// level at which we send notification 
		// for premium press releases
		$pr_low_amount = 1;
		
		// we consider the email credits low when there are fewer 
		// than the number bundled with 1 press release
		$email_low_amount = Model_Setting::value('bundled_email_credits');
		
		// we consider accounts that have not had a notice
		// in the last 7 days but only those that
		// have been active within the last 30 days
		$date_7d_ago = Date::days(-7)->format(Date::FORMAT_MYSQL);
		$date_30d_ago = Date::days(-30)->format(Date::FORMAT_MYSQL);
		
		// remove old records about notifications
		$sql = "DELETE FROM nr_credit_notification 
			WHERE date_sent < '{$date_7d_ago}'";
		$this->db->query($sql);
		
		sleep(1);
		set_time_limit(60);
		
		$sql = "SELECT u.* FROM nr_user u
			LEFT JOIN nr_credit_notification cn
			ON u.id = cn.user_id AND cn.type = 'PR'
			WHERE u.date_active > '{$date_30d_ago}'
			AND cn.user_id IS NULL";
			
		$dbr = $this->db->query($sql);
		foreach ($dbr->result() as $record)
		{
			usleep(250000);
			set_time_limit(60);
			$user = Model_User::from_db_object($record);
			$stat = $user->pr_credits_premium_stat();
			
			// we consider only accounts that had some credits initially
			// and not accounts who still have all initial credits
			if ($stat->total > $stat->available && $stat->available <= $pr_low_amount)
			{
				$cn = new Model_Credit_Notification();
				$cn->date_sent = Date::$now->format(Date::FORMAT_MYSQL);
				$cn->user_id = $user->id;
				$cn->type = 'PR';
				$cn->save();
				
				$en = new Email_Notification();
				$en->set_content_view('low_credits_pr');
				$en->set_data('stat', $stat);
				$en->send($user);
			}
		}
		
		sleep(1);
		set_time_limit(60);
			
		$sql = "SELECT u.* FROM nr_user u
			LEFT JOIN nr_credit_notification cn
			ON u.id = cn.user_id AND cn.type = 'EMAIL'
			WHERE u.date_active > '{$date_30d_ago}'
			AND u.package > 0
			AND cn.user_id IS NULL";
			
		$dbr = $this->db->query($sql);
		foreach ($dbr->result() as $record)
		{
			usleep(250000);
			set_time_limit(60);
			$user = Model_User::from_db_object($record);
			$stat = $user->email_credits_stat();
			
			// we consider only accounts that had some credits initially
			// and not accounts who still have all initial credits
			if ($stat->total > $stat->available && $stat->available <= $email_low_amount)
			{
				$cn = new Model_Credit_Notification();
				$cn->date_sent = Date::$now->format(Date::FORMAT_MYSQL);
				$cn->user_id = $user->id;
				$cn->type = 'EMAIL';
				$cn->save();
				
				$en = new Email_Notification();
				$en->set_content_view('low_credits_email');
				$en->set_data('stat', $stat);
				$en->send($user);
			}
		}
	}
	
}

?>