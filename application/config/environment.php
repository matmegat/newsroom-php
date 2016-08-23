<?php

$env = array();
$ENV = & $env;

// this is appended to newsroom name 
// * revert to automatic when domain doesn't end with this
$env['session_domain'] = '.i-newswire.com';

// the length of the session cookie
$env['session_duration'] = 86400;

// the path of the session cookie
$env['session_path'] = '/';

// this should almost always be UTC
// * changing this will break things
// * must be set for piwik too
$env['timezone'] = 'UTC';

// a default value that is non-null
$env['nr_default'] = 'NR_DEFAULT_5BAEAE3F';

// the ENVIRONMENT constant value
$env['environment'] = 'production';

// the tunnel hostname of the new i-newswire website
$env['website_tunnel_host'] = 'website.i-newswire.com';

// the actual hostname of the i-newswire website
$env['website_host'] = 'www.i-newswire.com';

// the default protocol to use 
$env['protocol'] = 'http://';

// the current host (null)
$env['host'] = null;

// load any local version of config
require_once 'environment.local.php';

?>
