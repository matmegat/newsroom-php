<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_Scheduled_Notification extends Model {
	
	const CLASS_CONTENT_SCHEDULED      = 1;
	const CLASS_CONTENT_UNDER_REVIEW   = 2;
	const CLASS_CONTENT_APPROVED       = 3;
	const CLASS_CONTENT_REJECTED       = 4;
	
	protected static $__table = 'nr_scheduled_notification';
	
}

?>