<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require_once("dbconnecti.php");

$db = new DBConnecti();

$sql = "SELECT	a.*, b.`application`, c.`name` AS location_name, d.`name` AS operator_name
		FROM 	`tb_location_device_notif` AS a
				INNER JOIN `tb_location_device` AS b ON a.`location_device_id` = b.`id`
				INNER JOIN `tb_location` AS c ON b.`location_id` = c.`id`
				INNER JOIN `tb_operator` AS d ON b.`operator_id` = d.`id`
		WHERE	NOT a.`notified`
				AND (a.`status` OR (NOT a.`status` AND TIME_TO_SEC(TIMEDIFF(NOW(), a.`status_time`)) > (60*10)))";

$result = $db->db_query($sql);

if($result['num_rows']>0){
	$rows = $result['rows'];
	foreach($rows as $row){
		$body_wa = get_sentence($row['status']);
		foreach($row as $key=>$val){
			$body_wa = str_replace("{".$key."}", $val, $body_wa);
		}
		$html = send_notif_wa($body_wa);
		if($html=='{"status":1,"error":""}'){
			$db->db_query("UPDATE tb_location_device_notif SET notified = 1 WHERE location_device_id = ".$row['location_device_id']);
		}
	}
}

function dateDifference($date_1 , $date_2)
{
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);
   
	$interval = date_diff($datetime1, $datetime2);
	
	$output = formatTime($interval);
	
	return $output;
   
}

function formatTime($interval)
{
	$output = $interval->format('#%y# Years #%m# Months #%d# Days #%h# Hours #%i# Minutes #%s# Seconds');
	$output = str_replace('#0# Years ', '', $output);
	$output = str_replace('#0# Months ', '', $output);
	$output = str_replace('#0# Days ', '', $output);
	$output = str_replace('#0# Hours ', '', $output);
	$output = str_replace('#0# Minutes ', '', $output);
	$output = str_replace('#0# Seconds', '', $output);
	
	$output = str_replace('#1# Years ', '#1# Year ', $output);
	$output = str_replace('#1# Months ', '#1# Month ', $output);
	$output = str_replace('#1# Days ', '#1# Day ', $output);
	$output = str_replace('#1# Hours ', '#1# Hour ', $output);
	$output = str_replace('#1# Minutes ', '#1# Minute ', $output);
	$output = str_replace('#1# Seconds', '#1# Second', $output);
	
	$output = str_replace('#', '', $output);
	
	return $output;
   
}

function send_notif_wa($message){
	$array_post = array(
		'token' => "a8gUD5vEdlSXgG8L2k",
		'recipient' => 'SENOSOFT - TS',
		'recipient_type' => '1',
		'message' => $message,
	);
	return http_load('http://42.1.62.186/senowabot/savenotif.php', 0, $array_post);
}

function http_load($url, $maxlength=0, $array_post=array())
{
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

function http_get_after($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,$i+strlen($strcari));
	return trim($strtmp);
}

function http_get_before($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,0,$i);
	return trim($strtmp);
}

function http_get_between($strtext, $strawal, $strakhir)
{
	preg_match('@'.$strawal.'(.*?)'.$strakhir.'@si',$strtext,$data);
	if(count($data)>1){
		return $data[1];
	}else{
		return '';
	}
}

function get_sentence($status){
	$ar_sentences_off = array(
			'BOT {location_name} {operator_name} OFF 10 menit yang lalu',
			'BOT {location_name} {operator_name} offline 10 menit yang lalu',
			'BOT {location_name} {operator_name} terdeteksi OFF 10 menit yang lalu',
			'BOT {location_name} {operator_name} terdeteksi offline 10 menit yang lalu',
			'BOT {location_name} {operator_name} sepertinya OFF dari 10 menit yang lalu',
			'BOT {location_name} {operator_name} sepertinya offline dari 10 menit yang lalu',
			'BOT {location_name} {operator_name} kayaknya offline dari 10 menit yang lalu',
			'BOT {location_name} {operator_name} sudah OFF 10 menit, tolong dicek ya!',
			'BOT {location_name} {operator_name} sudah OFF 10 menit, tolong dicek ya guys!',
			'BOT {location_name} {operator_name} sudah OFF 10 menit, tolong dicek ya tim!',
			'Hai gaes, sepertinya BOT {location_name} {operator_name} offline nih! Sudah ada 10 menit',
			'Hai gaes, sepertinya BOT {location_name} {operator_name} offline nih, sudah ada 10 menit, tolong dicek ya',
			'Halo tim, sepertinya BOT {location_name} {operator_name} offline nih! Sudah ada 10 menit',
			'Halo tim, sepertinya BOT {location_name} {operator_name} offline nih, sudah ada 10 menit, tolong dicek ya',
			'Oe.. BOT {location_name} {operator_name} offline tuh! Sudah dari 10 menit yang lalu',
			'Oe.. BOT {location_name} {operator_name} offline tuh, sudah dari 10 menit yang lalu, buruan dicek ya!',
			'Oe.. BOT {location_name} {operator_name} offline tuh, sudah dari 10 menit yang lalu, jangan lupa dicek ya!',
		);
		
	$ar_sentences_off = array(
			'BOT {location_name} {operator_name} OFF 10 menit yang lalu',
		);
		
	$ar_sentences_on = array(
			'BOT {location_name} {operator_name} sudah ON',
			'BOT {location_name} {operator_name} sudah online',
			'Yeay.. BOT {location_name} {operator_name} sudah online',
			'Yeay.. BOT {location_name} {operator_name} sudah online lagi',
			'Yeay.. BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
			'Yes.. BOT {location_name} {operator_name} sudah online',
			'Yes.. BOT {location_name} {operator_name} sudah online lagi',
			'Yes.. BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
			'Mantap! BOT {location_name} {operator_name} sudah online',
			'Mantap! BOT {location_name} {operator_name} sudah online lagi',
			'Mantap! BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
			'Alhamdulillah! BOT {location_name} {operator_name} sudah online',
			'Alhamdulillah! BOT {location_name} {operator_name} sudah online lagi',
			'Alhamdulillah! BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
		);
		
	$ar_sentences_on = array(
			'BOT {location_name} {operator_name} sudah ON',
		);
	
	if($status){
		$ar_sentences = $ar_sentences_on;
	}else{
		$ar_sentences = $ar_sentences_off;
	}
	
	$j = count($ar_sentences);
	$i = rand(1, $j);
	
	return $ar_sentences[$i-1];
	
}

?>