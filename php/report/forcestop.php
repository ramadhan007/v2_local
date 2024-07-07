<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("dbconnecti.php");

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon_report',
	'pass' => '$seno4r15%',
);

$db = new DBConnecti($params);

$sql = "update tb_report_date set force_stop = 1";
$result = $db->query($sql);
exit(json_encode($result));

?>