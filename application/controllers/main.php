<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ($ENV['website_host'] === $ENV['host'])
 	$RTR->_controller_rebase('website');
else if (!count($URI->segments))
	$RTR->set_class('browse');
else show_404();

throw new Controller_Pass_Exception();

?>