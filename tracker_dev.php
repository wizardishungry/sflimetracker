<?php
if (version_compare(PHP_VERSION, '5.1.0') === -1) {
    $message='PHP version 5.1.0+ required; ' . PHP_VERSION . " installed\n";
    echo "<h3>$message</h3>";
    exit($message);
}

// this check prevents access to debug front conrollers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
/*if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1')))
{
  die('Your are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}*/

require_once(dirname(__FILE__).'/config/ProjectConfiguration.class.php');

$app="tracker"; $env="dev"; $debug=true;
require_once(dirname(__FILE__).'/lib/initialize.php');
