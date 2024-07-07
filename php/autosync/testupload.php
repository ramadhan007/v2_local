<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$html = http_load("http://apm-jenius.senosoftech.com/upload?name=aris",0,array("token"=>'tug0eKy1kW5T88rez2tYXfI3F39g5M4I'));
// $html = http_load("http://localhost/senoapm/upload",0,array("token"=>'Ako%x.@-Zn~95W,72)'));
echo $html;

function upload_image($table, $id, $file_path){
	$ch = curl_init();
	$data = array('table' => $table, 'id' => $id, 'file' => $file_path);
	curl_setopt($ch, CURLOPT_URL, 'http://apm-jenius.senosoftech.com/upload/doUpload');
	curl_setopt($ch, CURLOPT_POST, 1);
	//CURLOPT_SAFE_UPLOAD defaulted to true in 5.6.0
	//So next line is required as of php >= 5.6.0
	curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_exec($ch);
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
		$fields_string = rtrim($fields_string, '&');
		curl_setopt($ch, CURLOPT_POST, true);
		echo $fields_string;
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