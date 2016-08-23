<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Model_Company_Email_Count extends Model {
	
	protected static $__table = 'nr_company_email_count';	
	protected static $__primary = 'company_id';
	
	protected $count;
	
	public static function create($company)
	{
		if ($company instanceof Model_Newsroom)
			$company = $company->company_id;
		if ($company instanceof Model_Company)
			$company = $company->id;
		
		$cec = new Model_Company_Email_Count();
		$cec->date_sent = Date::$now->format(Date::FORMAT_MYSQL);
		$cec->company_id = $company;		
		return $cec;
	}
	
	public function record($count)
	{
		$this->count = $count;
		$this->save();
	}
	
}

?>