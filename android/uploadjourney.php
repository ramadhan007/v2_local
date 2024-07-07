<?php

date_default_timezone_set('Asia/Jakarta');

$token = $_POST['token'];

if($token=='Ako%x.@-Zn~95W,72)'){

	require_once("dbconnecti.php");
	
	//get class
	$db = new DBConnecti();
	
	//get & decrypt posted params
	// $sql = $crypt->Decrypt($_POST['sql']);
	$device_id = $_POST['device_id'];
	$journey_id = $_POST['journey_id'];
	$location_lat = $_POST['lat'];
	$location_lng = $_POST['long'];
	$monitor_datetime = $_POST['datetime'];
	$sql = "SELECT fn_insert_monitor_journey('$device_id','$journey_id','$location_lat','$location_lng','$monitor_datetime') AS id";
	
	$db->write_log($device_id, $sql);
	
	$result = $db->db_query($sql);
	
	$db->write_log($device_id, json_encode($result));
	
	echo json_encode($result);
}

?>