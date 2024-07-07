<?php

date_default_timezone_set('Asia/Jakarta');

$token = $_POST['token'];

if($token=='vuMy+U)z@Bscqa$.^N'){

	require_once("dbconnecti.php");
	
	//get class
	$db = new DBConnecti();
	
	//get & decrypt posted params
	$monitor_journey_id = $_POST['monitor_journey_id'];
	$journey_detail_id = $_POST['journey_detail_id'];
	$network_type = $_POST['network_type'];
	if(strlen($network_type)>2) $network_type = substr($network_type,0,2);
	$cellid = $_POST['cellid'];
	$signal_level = $_POST['signal_level'];
	$response_time = $_POST['response_time'];
	$latency = $_POST['latency'];
	$packet_loss = $_POST['packet_loss'];
	$status = $_POST['status'];
	$message = fix_sql_string($_POST['message']);
	$monitor_datetime = $_POST['datetime'];
	$repeat_no = $_POST['repeat_no'];
	
	// get device_id
	$sql = "SELECT	`device_id`
			FROM 	`tb_monitor_journey`
			WHERE	id = $monitor_journey_id";
	
	$result = $db->db_query($sql);
	$row = $result['rows'];
	
	$device_id = $row[0]['device_id'];
	
	$sql = "SELECT fn_insert_monitor_journey_detail('$monitor_journey_id','$journey_detail_id','$network_type','$cellid','$signal_level','$response_time','$latency','$packet_loss','$status','$message','$monitor_datetime','$repeat_no') AS id";
	
	$db->write_log($device_id, $sql);
	
	$result = $db->db_query($sql);
	
	$db->write_log($device_id, json_encode($result));
	
	echo json_encode($result);
}

function fix_sql_string($val){
	$str_value = str_replace("'",chr(92)."'",$val);
	$str_value = str_replace(chr(145),chr(92).chr(145),$str_value);
	$str_value = str_replace(chr(146),chr(92).chr(146),$str_value);
	$str_value = str_replace(chr(13).chr(10),chr(92)."r".chr(92)."n",$str_value);
	return $str_value;
}

?>