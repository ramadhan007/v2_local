<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

chdir(dirname(__FILE__));

header( 'Content-type: text/html; charset=utf-8' );

if (defined('STDIN')) {
  $device_id = $argv[1];
} else {
  $device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : "";
}

if(date('H:i', time())!='00:00'){
	
	require_once("dbconnecti.php");
  	require_once("../../config/urlconfig.php");
	
	$db = new DBConnecti();
  	$URLConfig = new URLConfig();
	
	$screenshot_path_rel = get_setting($db, "screenshot_path_rel", "userfiles/screenshot/monitor_journey_detail");
	$screenshot_ext = get_setting($db, "screenshot_ext", "png");
	
	// get local_setting apm_client_id
	$sql = "select value from tb_local_setting where name = 'apm_client_id'";
	$result = $db->db_query($sql);
	
	if($result['num_rows']>0){
		$apm_client_id = $result["rows"][0]["value"];
	}else{
		$apm_client_id = '0';
	}
	$apm_client_id = $apm_client_id + 100;
	
	$ada_update = true;
	
	while($ada_update){
		
		$sql = "select * from tb_log_update".($device_id ? " where device_id = '$device_id'" : "")." order by id asc limit 0,100";
		
		echo "Checking new update...."."<br>\n";
		
		$result = $db->db_query($sql);
		
		$ada_update = false;
		
		if($result['num_rows']>0){
			
			echo "New updates found, processing..."."<br>\n";
			
			$ada_update = true;
			$rows = $result['rows'];
			$i = 0;
			foreach($rows as $row){
				$i++;
				$sql = "SELECT	*
					FROM 	".$row["table_name"]."
					WHERE	id = '".$row['record_id']."'";
				
				$result1 = $db->db_query($sql);
				
				$data_ready = false;
				
				if($result1['num_rows']>0){
					$rows1 = $result1['rows'];
					$row1 = $rows1[0];
					
					$row1["id_ori"] = $row1["id"];
					$row1["id"] = $apm_client_id.substr("0000000000000000".$row1["id_ori"],-16);
					
					$row1['token'] = 'tug0eKy1kW5T88rez2tYXfI3F39g5M4I';
					
					if($row["table_name"]=='tb_monitor_journey'){
						$api_file = "uploadJourneyNew";
					}elseif($row["table_name"]=='tb_monitor_journey_detail'){
						$api_file = "uploadJourneyDetailNew";
						$row1["monitor_journey_id"] = $apm_client_id.substr("0000000000000000".$row1["monitor_journey_id"],-16);
					}if($row["table_name"]=='tb_monitor_journey_nvt'){
						$api_file = "uploadJourneyNvtNew";
						$row1["monitor_journey_id"] = $apm_client_id.substr("0000000000000000".$row1["monitor_journey_id"],-16);
					}
					
					/* $param = "";
					foreach($row1 as $key=>$value){
						$param .= "&".$key."=".urlencode($value);
					}
					$param = substr($param, 1); */
					
					$api_request = $URLConfig->apm_central."/upload/".$api_file;
					// $api_request = "http://localhost/senoapm/upload/".$api_file;
					
					// exit($api_request);
					
					echo "Loading API at: ".$api_request."...<br>\n";
					
					$html = http_load($api_request, 0, $row1);
					
					// if(!$html) exit();
					
					echo $html."<br>\n";
					
					flush();
					// ob_flush();
					
					if(isJson($html)){	//valid json response
						$ar_result = json_decode($html);
						if($ar_result->status==1){
							if($row["table_name"]=='tb_monitor_journey_detail'){
								if($row1["has_screenshot"]){
									$url = $URLConfig->apm_central.'/upload/uploadScreenshot';
									// $url = 'http://localhost/senoapm/upload/uploadScreenshot';
									$html = upload_screenshot($url, $row["table_name"], $row1["id"], '../../'.$screenshot_path_rel."/".$row1["id_ori"].".".$screenshot_ext);
									
									echo $html."<br>\n";
									
									flush();
									// ob_flush();
								}
							}
							
							$db->db_query("DELETE FROM tb_log_update WHERE id = ".$row['id']);
						}else{
							if(!(strpos($ar_result->message,'Duplicate entry')===false)){
								$db->db_query("DELETE FROM tb_log_update WHERE id = ".$row['id']);
							}
						}
					}
				}
			}
		}
	}
}

function isJson($string) {
   json_decode($string);
   return json_last_error() === JSON_ERROR_NONE;
}

function get_setting(&$db, $name, $default=''){
	// get setting $name
	$sql = "select value from tb_setting where name = '$name'";
	$result = $db->db_query($sql);
	
	if($result['num_rows']>0){
		return $result["rows"][0]["value"];
	}else{
		return $default;
	}
}

function makeCurlFile($file){
    $mime = mime_content_type($file);
    $info = pathinfo($file);
    $name = $info['basename'];
    $output = new CURLFile($file, $mime, $name);
    return $output;
}

function upload_screenshot($url, $table, $id, $file_path)
{
	// $file = makeCurlFile($file_path);
	$file = makeCurlFile($file_path); 
	$array_post = array('table' => $table, 'id' => $id, 'file' => $file);
	$array_post['token'] = 'tug0eKy1kW5T88rez2tYXfI3F39g5M4I';
	
	$ch = curl_init();
	$timeout = 120;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	
	/* if($array_post)
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
	} */
	
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $array_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return $file_contents;
}

function http_load($url, $maxlength=0, $array_post=array())
{
	// return file_get_contents($url);
	$ch = curl_init();
	$timeout = 60;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	
	/* if($array_post)
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
	} */
	
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $array_post);
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
