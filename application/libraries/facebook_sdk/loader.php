<?php

if (defined('loaded_facebook_sdk')) return;
define('loaded_facebook_sdk', true);

require_once 'files/src/facebook.php';

lib_autoload('facebook_sdk');

?>