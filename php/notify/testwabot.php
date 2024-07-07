<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$body = "<p>Level 1</p>
	<p>Title : Njajal Notif</p>
	<p>Ref : 12345</p>
	<p>Error Description : Sekedar njajal notifikasi oleh BOT</p>
	<p>Phone Number : 081234567890</p>
	<p>Location : Jakarta</p>
	<p>Telco : Telkomsel</p>
	<p>Date : 2019-09-20 05:20</p>";

$body_wa = strip_tags($body);
$body_wa = str_replace("\t", "", $body_wa);
$body_wa = str_replace(chr(13), "", $body_wa);
// $body_wa = str_replace($body, "<p>", "");
// $body_wa = str_replace($body_wa, "</p>", "");

// echo $body_wa;

send_notif_wa($body_wa);

function send_notif_wa($message){
	$array_post = array(
		'token' => "a8gUD5vEdlSXgG8L2k",
		'recipient' => 'SENOSOFT - CB',
		'recipient_type' => '1',
		'message' => $message,
	);
	echo http_load('http://42.1.62.186/senowabot/savenotif.php', 0, $array_post);
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

?>
