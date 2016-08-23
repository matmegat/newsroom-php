<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_User extends Model {
	
	public $has_full_access = false;
	
	protected $n_pr_free_available = NR_DEFAULT;
	protected $m_limit_pr_premium = NR_DEFAULT;
	protected $m_limit_pr_basic = NR_DEFAULT;
	protected $m_limit_pr_held_premium = NR_DEFAULT;
	protected $m_limit_pr_held_basic = NR_DEFAULT;
	protected $m_limit_email_inherited = NR_DEFAULT;
	protected $m_limit_email_held = NR_DEFAULT;
	protected $m_limit_newsroom_held = NR_DEFAULT;
	
	const PACKAGE_SILVER = 1;
	const PACKAGE_GOLD = 2;
	const PACKAGE_PLATINUM = 3;
	
	protected static $__table = 'nr_user';
	
	public static function find_email($email)
	{
		$ci =& get_instance();
		$dbi = $ci->db->select('*')
			->from(static::$__table)
			->where('email', $email);
			
		return static::from_db($dbi->get());
	}
	
	public static function find_company_id($company)
	{
		$table = static::$__table;
		$sql = "SELECT u.* FROM {$table} u INNER JOIN 
			nr_newsroom n ON n.user_id = u.id WHERE
			n.company_id = ?";
		
		$ci =& get_instance();
		$dbr = $ci->db->query($sql, array($company));
			
		return static::from_db($dbr);
	}
	
	public static function authenticate($email, $password)
	{
		$user = static::find_email($email);
		if ($user === false) return false;
		if (!Blowfish::__hash($password, $user->password)) return false;
		return $user;
	}
	
	public function set_password($password)
	{
		$password = Blowfish::__hash($password);
		$this->password = $password;
	}
	
	public function newsrooms()
	{
		return Model_Newsroom::find_user_id($this->id);
	}
	
	public function default_newsroom()
	{
		if (!($newsroom = Model_Newsroom::find_user_default($this)))
			$newsroom = Model_Newsroom::create($this);
		return $newsroom;
	}
	
	public function name()
	{
		return "{$this->first_name} {$this->last_name}";
	}
	
	public function m_limit_pr_premium()
	{
		if ($this->m_limit_pr_premium === NR_DEFAULT)
			$this->m_limit_pr_premium = Model_Limit_PR::find_premium($this);
		return $this->m_limit_pr_premium;
	}
	
	public function m_limit_pr_basic()
	{
		if ($this->m_limit_pr_basic === NR_DEFAULT)
			$this->m_limit_pr_basic = Model_Limit_PR::find_basic($this);
		return $this->m_limit_pr_basic;
	}
	
	public function m_limit_pr_held_premium()
	{
		if ($this->m_limit_pr_held_premium === NR_DEFAULT)
			$this->m_limit_pr_held_premium = Model_Limit_PR_Held::find_premium($this);
		return $this->m_limit_pr_held_premium;
	}
	
	public function m_limit_pr_held_basic()
	{
		if ($this->m_limit_pr_held_basic === NR_DEFAULT)
			$this->m_limit_pr_held_basic = Model_Limit_PR_Held::find_basic($this);
		return $this->m_limit_pr_held_basic;
	}
	
	public function m_limit_email_inherited()
	{
		if ($this->m_limit_email_inherited === NR_DEFAULT)
			$this->m_limit_email_inherited = Model_Limit_Email_Inherited::find($this->m_limit_pr_premium());
		return $this->m_limit_email_inherited;
	}
	
	public function m_limit_email_held()
	{
		if ($this->m_limit_email_held === NR_DEFAULT)
			$this->m_limit_email_held = Model_Limit_Email_Held::find_user($this);
		return $this->m_limit_email_held;
	}
	
	public function m_limit_newsroom_held()
	{
		if ($this->m_limit_newsroom_held === NR_DEFAULT)
			$this->m_limit_newsroom_held = Model_Limit_Newsroom_Held::find_user($this);
		return $this->m_limit_newsroom_held;
	}
	
	public function email_credits()
	{
		$available = 0;
		
		if ($this->m_limit_email_inherited())
			$available += $this->m_limit_email_inherited()->available();
		if ($this->m_limit_email_held())
			$available += $this->m_limit_email_held()->available();
		
		return $available;
	}
	
	public function pr_credits_premium()
	{
		$available = 0;
		
		if ($this->m_limit_pr_premium())
			$available += $this->m_limit_pr_premium()->available();
		if ($this->m_limit_pr_held_premium())
			$available += $this->m_limit_pr_held_premium()->available();
		
		return $available;
	}
	
	public function pr_credits_basic()
	{
		$available = 0;
		
		if ($this->m_limit_pr_basic())
			$available += $this->m_limit_pr_basic()->available();
		else if ($this->is_free_user())
			$available += $this->calculate_pr_credits_basic();
		
		if ($this->m_limit_pr_held_basic())
			$available += $this->m_limit_pr_held_basic()->available();
		
		return $available;
	}
	
	public function email_credits_stat()
	{
		$stat = new stdClass();
		$stat->held_available = 0;
		$stat->held_total = 0;
		$stat->rollover_available = 0;
		$stat->rollover_total = 0;
		$stat->available = 0;
		$stat->total = 0;
		$stat->used = 0;
		
		if ($this->m_limit_email_inherited())
		{
			$stat->rollover_available += $this->m_limit_email_inherited()->available();
			$stat->rollover_total += $this->m_limit_email_inherited()->total();
			$stat->rollover_days = $this->m_limit_pr_premium()->days();
		}
		
		if ($this->m_limit_email_held())
		{
			$stat->held_available += $this->m_limit_email_held()->available();
			$stat->held_total += $this->m_limit_email_held()->total();
		}
		
		$stat->rollover_used = $stat->rollover_total - $stat->rollover_available;
		$stat->held_used = $stat->held_total - $stat->held_available;
		
		$stat->available += $stat->rollover_available;
		$stat->total += $stat->rollover_total;			
		$stat->available += $stat->held_available;
		$stat->total += $stat->held_total;
		$stat->used = $stat->total - $stat->available;
		
		return $stat;
	}
	
	public function pr_credits_premium_stat()
	{
		$stat = new stdClass();
		$stat->held_available = 0;
		$stat->held_total = 0;
		$stat->rollover_available = 0;
		$stat->rollover_total = 0;
		$stat->available = 0;
		$stat->total = 0;
		$stat->used = 0;
		
		if ($this->m_limit_pr_premium())
		{
			$stat->rollover_available += $this->m_limit_pr_premium()->available();
			$stat->rollover_total += $this->m_limit_pr_premium()->total();
			$stat->rollover_days = $this->m_limit_pr_premium()->days();
		}
		
		if ($this->m_limit_pr_held_premium())
		{
			$stat->held_available += $this->m_limit_pr_held_premium()->available();
			$stat->held_total += $this->m_limit_pr_held_premium()->total();
		}
		
		$stat->rollover_used = $stat->rollover_total - $stat->rollover_available;
		$stat->held_used = $stat->held_total - $stat->held_available;
		
		$stat->available += $stat->rollover_available;
		$stat->total += $stat->rollover_total;			
		$stat->available += $stat->held_available;
		$stat->total += $stat->held_total;
		$stat->used = $stat->total - $stat->available;
		
		return $stat;
	}
	
	public function pr_credits_basic_stat()
	{
		$stat = new stdClass();
		$stat->held_available = 0;
		$stat->held_total = 0;
		$stat->rollover_available = 0;
		$stat->rollover_total = 0;
		$stat->available = 0;
		$stat->total = 0;
		$stat->used = 0;
		
		if ($this->m_limit_pr_basic())
		{
			$stat->rollover_available += $this->m_limit_pr_basic()->available();
			$stat->rollover_total += $this->m_limit_pr_basic()->total();
			$stat->rollover_days = $this->m_limit_pr_basic()->days();
		}		
		else if ($this->is_free_user())
		{
			$stat->rollover_available += $this->calculate_pr_credits_basic();	
			$stat->rollover_total += Model_Setting::value('free_basic_pr_count');
			$stat->rollover_days = Model_Setting::value('free_basic_pr_period');
		}
						
		if ($this->m_limit_pr_held_basic())
		{
			$stat->held_available += $this->m_limit_pr_held_basic()->available();
			$stat->held_total += $this->m_limit_pr_held_basic()->total();
		}
		
		$stat->rollover_used = $stat->rollover_total - $stat->rollover_available;
		$stat->held_used = $stat->held_total - $stat->held_available;
			
		$stat->available += $stat->rollover_available;
		$stat->total += $stat->rollover_total;			
		$stat->available += $stat->held_available;
		$stat->total += $stat->held_total;	
		$stat->used = $stat->total - $stat->available;
		
		return $stat;
	}
	
	protected function calculate_pr_credits_basic()
	{
		if ($this->n_pr_free_available === NR_DEFAULT)
		{
			$count = Model_Setting::value('free_basic_pr_count');
			$period = Model_Setting::value('free_basic_pr_period');
			$dt_cut = Date::days(-$period)->format(Date::FORMAT_MYSQL);
			
			$sql = "SELECT 1 FROM nr_company cm
				INNER JOIN nr_content ct ON 
				cm.user_id = {$this->id} AND
				cm.id = ct.company_id AND
				ct.type = 'pr' AND
				ct.is_premium = 0 AND
				(ct.is_published = 1 OR 
				 ct.is_under_review = 1 OR 
				 ct.is_approved = 1) AND
				ct.date_publish > '{$dt_cut}'";
				
			$dbr = $this->db->query($sql);
			$used = $dbr->num_rows();			
			$this->n_pr_free_available = max(0, ($count - $used));
		}
		
		return $this->n_pr_free_available;
	}
	
	public function consume_pr_credit_basic($content)
	{	
		if ($this->m_limit_pr_basic() && $this->m_limit_pr_basic()->available())
			return $this->m_limit_pr_basic()->consume($content);		
		if ($this->m_limit_pr_held_basic() && $this->m_limit_pr_held_basic()->available())
			return $this->m_limit_pr_held_basic()->consume($content);
	}
	
	public function consume_pr_credit_premium($content)
	{
		if ($this->m_limit_pr_premium() && $this->m_limit_pr_premium()->available())
			return $this->m_limit_pr_premium()->consume($content);
		if ($this->m_limit_pr_held_premium() && $this->m_limit_pr_held_premium()->available())
			return $this->m_limit_pr_held_premium()->consume($content);
	}
	
	public function consume_email_credits($count)
	{	
		if ($inherited = $this->m_limit_email_inherited())
			if (($count -= $inherited->consume($count)) <= 0) return;
		if ($held = $this->m_limit_email_held())
			if (($count -= $held->consume($count)) <= 0) return;
	}
	
	public function newsroom_credits_stat()
	{
		$stat = new stdClass();
		$stat->rollover = $this->newsroom_credits_from_package();
		$stat->total = $stat->rollover;
		if ($this->m_limit_newsroom_held())
			$stat->total += $this->m_limit_newsroom_held()->total();
		$stat->used = $this->newsroom_credits_used();
		$stat->available = $stat->total - $stat->used;
		return $stat;
	}
	
	public function newsroom_credits_from_package()
	{
		if ($this->package == static::PACKAGE_PLATINUM)
			  $name = 'newsroom_credits_platinum';
		else if ($this->package == static::PACKAGE_GOLD)
			  $name = 'newsroom_credits_gold';
		else if ($this->package == static::PACKAGE_SILVER)
			  $name = 'newsroom_credits_silver';
		else $name = 'newsroom_credits_free';
		
		$total = (int) Model_Setting::value($name);
		return $total;
	}
	
	public function newsroom_credits_total()
	{
		$total = $this->newsroom_credits_from_package();
		if ($this->m_limit_newsroom_held())
			$total += $this->m_limit_newsroom_held()->total();
		return $total;
	}
	
	public function newsroom_credits_used()
	{
		$criteria = array();
		$criteria[] = array('user_id', $this->id);
		$criteria[] = array('is_active', 1);
		return Model_Newsroom::count($criteria);
	}
	
	public function newsroom_credits_available()
	{
		return $this->newsroom_credits_total() 
			- $this->newsroom_credits_used();
	}
	
	public function newsroom_credits()
	{
		return $this->newsroom_credits_total() 
			- $this->newsroom_credits_used();
	}
	
	public function is_free_user()
	{
		return ! $this->has_silver_access();
	}
	
	public function has_platinum_access()
	{
		return $this->package == static::PACKAGE_PLATINUM ||
		       $this->has_full_access;
	}
	
	public function has_gold_access()
	{
		return $this->package == static::PACKAGE_PLATINUM ||
		       $this->package == static::PACKAGE_GOLD ||
		       $this->has_full_access;
	}
	
	public function has_silver_access()
	{
		return $this->package == static::PACKAGE_PLATINUM ||
		       $this->package == static::PACKAGE_GOLD || 
		       $this->package == static::PACKAGE_SILVER ||
		       $this->has_full_access;
	}
	
	public function package_name()
	{
		if ($this->package == static::PACKAGE_PLATINUM)
			  return 'Platinum';
		else if ($this->package == static::PACKAGE_GOLD)
			  return 'Gold';
		else if ($this->package == static::PACKAGE_SILVER)
			  return 'Silver';
		else return 'Free';
	}
	
	public function package_expires()
	{
		if ($this->m_limit_pr_premium())
			return $this->m_limit_pr_premium()->date_expires;
		return null;
	}
	
	public static function generate_password($length = 16)
	{
		// password can contain common symbols
		// letters (including capital) and numbers
		$chars  = '-=~!@#$%^&*()_+,.<>?;:[]{}';
		$chars .= 'abcdefghijklmnopqrstuwxyz';
		$chars .= 'ABCDEFGHIJKLMNOPQRSTUWXYZ';
		$chars .= '0123456789';		
		for ($i = 0, $pass = null; $i < $length; $i++)
			$pass .= $chars[mt_rand(0, strlen($chars))];
		return $pass;
	}
	
	public static function create()
	{
		return Model_User_Base::create_user();
	}
		
}

?>