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

$sql = "select *, TIME_TO_SEC(TIMEDIFF(time_now, `time_start`)) as processed_sec from tb_report_date";
$result = $db->query($sql);
if(!$result['status']) exit(json_encode($result));

$ids = json_decode($result['rows'][0]['ids']);

$total = 0;
$processed = 0;

foreach($ids as $key=>$val){
	$total = $total + ($val->id_max - $val->id_min) + 1;
	$processed = $processed + ($val->id_cur - $val->id_min) + 1;
}

$remained = $total - $processed;
$remained_msec = intval(($remained*intval($result['rows'][0]['processed_sec'])*1000)/$processed);
$status = ($result['rows'][0]['started'] ? ($result['rows'][0]['completed'] ? "Completed" : ($result['rows'][0]['force_stop'] ? "Force Stopped" : "Running")) : "Not Started");

echo json_encode(array(
		'status'=>true,
		'progress_status'=>$status,
		'completed'=>(bool)intval($result['rows'][0]['completed']),
		'total'=>$total,
		'processed'=>($processed > 0 ? $processed : 0),
		'processed_msec'=>1000*intval($result['rows'][0]['processed_sec']),
		'remained_msec'=>$remained_msec
	));

?>