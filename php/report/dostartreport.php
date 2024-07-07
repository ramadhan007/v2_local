<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("dbconnecti.php");
require_once("localpassword.php");

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon_report',
	'pass' => '$seno4r15%',
);

$db = new DBConnecti($params);

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon',
	'pass' => '$seno4r15%',
);

$db_src = new DBConnecti($params);

$password = $_POST['password'];
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];

if($password==$local_password){
	if(!valid_date($date_start)) exit("Invalid start date!");
	if(!valid_date($date_end)) exit("Invalid end date!");
	
	$ar_table = array("error", "monitor_journey", "monitor_journey_detail", "monitor_journey_nvt");
	$ar_sql = array(
			"SELECT	{mm}(a.id) AS ret_id
			FROM 	`tb_error` AS a
			WHERE	a.`error_date` = '{date}'",
			"SELECT	{mm}(a.id) AS ret_id
			FROM 	`tb_monitor_journey` AS a
			WHERE	a.`monitor_date` = '{date}'",
			"SELECT	{mm}(a.id) AS ret_id
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON a.`monitor_journey_id` = b.`id`
			WHERE	b.`monitor_date` = '{date}'",
			"SELECT	{mm}(a.id) AS ret_id
			FROM 	`tb_monitor_journey_nvt` AS a
					INNER JOIN `tb_monitor_journey` AS b ON a.`monitor_journey_id` = b.`id`
			WHERE	b.`monitor_date` = '{date}'"
		);
	$data = array();
	for($i=0; $i<count($ar_table); $i++){
		$sql = str_replace("{date}","$date_start",str_replace("{mm}","MIN",$ar_sql[$i]));
		echo $sql."<br />";
		$result = $db_src->query($sql);
		if(!($result['status'] && $result['num_rows'])) exit(json_encode($result));
		$id = array();
		$id['id_min'] = $result['rows'][0]['ret_id'];
		$id['id_cur'] = 0;
		
		$sql = str_replace("{date}","$date_end",str_replace("{mm}","MAX",$ar_sql[$i]));
		echo $sql."<br />";
		$result = $db_src->query($sql);
		if(!($result['status'] && $result['num_rows'])) exit(json_encode($result));
		$id['id_max'] = $result['rows'][0]['ret_id'];
		$data[$ar_table[$i]] = $id;
	}
	
	$ids = json_encode((object)$data, JSON_PRETTY_PRINT);
	
	$sql = "CALL clear_report();";
	echo $sql."<br />";
	$result = $db->query($sql);
	if(!$result['status']) echo json_encode($result);
	
	$sql = "update tb_report_date set date_start = '$date_start', date_end = '$date_end', started = 0, completed = 0, is_running = 0, force_stop = 0, ids = '".fix_db_value($ids)."'";
	echo $sql."<br />";
	$result = $db->query($sql);
	if(!$result['status']) echo json_encode($result);
	echo "Reporting task started successfully :)";
}else{
	echo "Wrong password!!";
}

function valid_date($str)
{
	if($str=="") return false;
	if(!preg_match("/^([0-9]{4})-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$/", $str))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function fix_db_value($val){
	$str_value = str_replace("'",chr(92)."'",$val);
	// $str_value = str_replace('"',chr(92).'"',$str_value);
	$str_value = str_replace(chr(145),chr(92).chr(145),$str_value);
	$str_value = str_replace(chr(146),chr(92).chr(146),$str_value);
	$str_value = str_replace(chr(13).chr(10),chr(92)."r".chr(92)."n",$str_value);
	return $str_value;
}

?>