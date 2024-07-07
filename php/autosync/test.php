<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

chdir(dirname(__FILE__));

header( 'Content-type: text/html; charset=utf-8' );

if (defined('STDIN')) {
  $device_id = $argv[1];
} else {
  $device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : "";
}

echo $device_id;

?>
