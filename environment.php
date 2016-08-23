<?php

if (!isset($_SERVER['HTTP_HOST']))
	$_SERVER['HTTP_HOST'] = 'CLI';

function class_autoload($class)
{
	$lower = strtolower($class);
	
	$file = "application/core/{$class}.php";
	if (is_file($file)) { require_once $file; return; }
	
	if (preg_match('#^model_#', $lower)) 
	{
		$model = substr($lower, 6);
		$file = "application/models/{$model}.php";
		if (is_file($file)) { require_once $file; return; }
		$model = str_replace('_', '/', $model);
		$file = "application/models/{$model}.php";
		if (is_file($file)) { require_once $file; return; }
	}
		
	$file = "application/classes/{$lower}.php";
	if (is_file($file)) { require_once $file; return; }
	
	$lower = str_replace('_', '/', $lower);
	$file = "application/classes/{$lower}.php";
	if (is_file($file)) { require_once $file; return; }
}

function lib_autoload($name)
{
	$file = "application/libraries/{$name}/loader.php";
	if (is_file($file)) require_once $file;
}

function load_controller($name)
{
	$file = "application/controllers/{$name}.php";
	if (is_file($file)) require_once $file;
}

function load_parent_controller($name)
{
	load_controller($name);
}

function load_shared_fnc($name)
{
	load_controller($name);
}

// repair all input data
require_once 'utf8_safe.php';

// load environment specific config
require_once 'application/helpers/functions.php';

// load environment specific config
require_once 'application/config/environment.php';

// the remote address of the user (or null for unknown)
$env['remote_addr'] = @$_SERVER['REMOTE_ADDR'];

// access to cookies in CIL
$env['cookies'] = @$_COOKIE;

// set the protocol based on header from nginx
if (!function_exists('apache_request_headers'))
	{ function apache_request_headers() { return array(); }}
$apache_request_headers = apache_request_headers();
if (@$apache_request_headers['X-SSL-Protocol'])
	$env['protocol'] = 'https://';

// set the real hostname when tunnel is used
if ($env['website_tunnel_host'] === $_SERVER['HTTP_HOST'])
	  $env['host'] = $env['website_host'];
else $env['host'] = $_SERVER['HTTP_HOST'];

// sessions store
require_once 'data_cache.php';
require_once 'data_cache_session_handler.php';

// set the version information (git commit)
$env['version'] = file_get_contents('version');

// memcache will keep the session active for this
Data_Cache_Session_Handler::$session_duration = 
	$env['session_duration'];

// define the environment from config
define('ENVIRONMENT', $env['environment']);

// non-null default value 
define('NR_DEFAULT', $env['nr_default']);

// carriage return, new line
define('CRLF', "\r\n");

// if the domain is not using subdomain then we do not use session
if (str_ends_with($_SERVER['HTTP_HOST'], $env['session_domain']))
{
	// initialize sub-domain sessions
	// ini_set('session.gc_maxlifetime', '86400'); 
	session_set_cookie_params($env['session_duration'], 
		$env['session_path'], $env['session_domain']);
	session_start();
}

// enable auto loading of classes
spl_autoload_register('class_autoload');

// set the default timezone
date_default_timezone_set($env['timezone']);

// buffer out
ob_start();

?>