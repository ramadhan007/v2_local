<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

if(date('H:i', time())!='00:00'){

	require_once("dbconnecti.php");
	
	$db = new DBConnecti();
	
	$sql = "select * from tb_monitor_journey_detail_new order by id asc limit 0,100";
	
	$result = $db->db_query($sql);
	
	if($result['num_rows']>0){
		$rows = $result['rows'];
		foreach($rows as $row){
			$sql = "SELECT	c.`id` AS device_id, c.`phone_number`, f.`name` AS location_name, g.`name` AS operator_name, d.`name` AS journey_name,
					e.name AS journey_detail_name, b.`location_lat`, b.`location_lng`, a.`cellid`, a.`network_type`, a.`signal_level`,
					a.`response_time`, a.`monitor_datetime`, a.`status`, a.`message`, a.`scheduled` AS scheduled_downtime
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON a.`monitor_journey_id` = b.`id`
					INNER JOIN `tb_location_device` AS c ON b.`location_device_id` = c.`id`
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id`
					INNER JOIN `tb_journey_detail` AS e ON a.`journey_detail_id` = e.`id`
					INNER JOIN `tb_location` AS f ON c.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON c.`operator_id` = g.`id`
			WHERE	a.`id` = '".$row['id']."'";
			
			$result1 = $db->db_query($sql);
			
			if($result1['num_rows']>0){
				$rows1 = $result1['rows'];
				$row1 = $rows1[0];
				$message = $row1['message'];
				$message = str_replace("Data :", "Data ;", $message);
				$message = str_replace("Data:", "Data;", $message);
				$message = str_replace("data :", "data ;", $message);
				$message = str_replace("data:", "data;", $message);
				$api_request = "http://202.157.177.204/saverawdata.php?app=4d74cx4fz4af4cp4jp4pd4q4423&device_id=".urlencode($row1['device_id'])."&phone_number=".urlencode($row1['phone_number'])."&location_name=".urlencode($row1['location_name'])."&operator_name=".urlencode($row1['operator_name'])."&journey_name=".urlencode($row1['journey_name'])."&journey_detail_name=".urlencode($row1['journey_detail_name'])."&location_lat=".urlencode($row1['location_lat'])."&location_lng=".urlencode($row1['location_lng'])."&cellid=".urlencode($row1['cellid'])."&network_type=".urlencode($row1['network_type'])."&signal_level=".urlencode($row1['signal_level'])."&response_time=".urlencode($row1['response_time'])."&monitor_datetime=".urlencode($row1['monitor_datetime'])."&status=".urlencode($row1['status'])."&message=".urlencode($message)."&scheduled_downtime=".urlencode($row1['scheduled_downtime']);
				$html = http_load($api_request);
				
				// echo $html;
				
				if($html=='{"status":1,"error":""}'){
					$db->db_query("DELETE FROM tb_monitor_journey_detail_new WHERE id = ".$row['id']);
				}
			}else{
				$db->db_query("INSERT INTO `tb_monitor_journey_detail_failed` VALUES (".$row['id'].");");
				$db->db_query("DELETE FROM tb_monitor_journey_detail_new WHERE id = ".$row['id']);
			}
		}
	}
}

function http_load($url, $maxlength=0, $array_post=array())
{
	// return file_get_contents($url);
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
	return $file_contents;
}

?>