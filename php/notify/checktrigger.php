<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

if(date('H:i', time())!='00:00'){

	require_once("dbconnecti.php");
	
	$db = new DBConnecti();
	
	$sql = "select * from tb_trigger_device order by id asc limit 0,100";
	
	$result = $db->db_query($sql);
	
	if($result['num_rows']>0){
		$rows = $result['rows'];
		foreach($rows as $row){
			$html = http_load("https://realdataidapm.com/dashboard/php/trigger/updatedevice.php?app=4d74cx4fz&device_id=".$row['device_id']."&status=".$row['status']);
			if($html=='{"status":1,"error":""}'){
				$db->db_query("DELETE FROM tb_trigger_device WHERE id = ".$row['id']);
			}
		}
	}
	
	$sql = "select * from tb_trigger_journey order by id asc limit 0,100";
	
	$result = $db->db_query($sql);
	
	if($result['num_rows']>0){
		$rows = $result['rows'];
		foreach($rows as $row){
			$html = http_load("https://realdataidapm.com/dashboard/php/trigger/updatejourney.php?app=4d74cx4fz&sub_journey_id=".$row['journey_detail_id']."&location_id=".$row['location_id']."&operator_id=".$row['operator_id']."&status=".$row['status']);
			if($html=='{"status":1,"error":""}'){
				$db->db_query("DELETE FROM tb_trigger_journey WHERE id = ".$row['id']);
			}
		}
	}
}

function http_load($url, $maxlength=0, $array_post=array())
{
	return file_get_contents($url);
	/* echo $url;
	$ch = curl_init();
	$timeout = 0;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if($array_post)
	{
		$fields_string = '';
		foreach($array_post as $key=>$value)
		{
			$fields_string .= $key.'='.$value.'&';
		}
		//echo $fields_string; exit;
		rtrim($fields_string, '&');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	if($maxlength>0)
	{
		curl_setopt($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD, $maxlength);
	}
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return $file_contents; */
}

?>