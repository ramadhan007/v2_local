<?php

require_once("dbconnecti.php");

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon_archive',
	'pass' => '$seno4r15%',
);

$db = new DBConnecti($params);

echo json_encode($db->db_query("select * from tb_monitor_journey_detail"));

?>