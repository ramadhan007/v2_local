<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

chdir(dirname(__FILE__));

header( 'Content-type: text/html; charset=utf-8' );

if(date('H:i', time())!='00:00'){
	
	require_once("dbconnecti.php");
  require_once("../../config/urlconfig.php");

	$db = new DBConnecti();
  $URLConfig = new URLConfig();
	
	// get setting
	$sql = "select value from tb_local_setting where name = 'apm_client_id'";
	$result = $db->db_query($sql);
	
	if($result['num_rows']>0){
		$apm_client_id = $result["rows"][0]["value"];
	}else{
		$apm_client_id = "0";
	}
	
	if($apm_client_id){
		
		$ada_update = true;
		
		while($ada_update){
		
			$api_request = $URLConfig->apm_central."/api/getNewUpdate";
			$data = array(
					'token'=>'tug0eKy1kW5T88rez2tYXfI3F39g5M4I',
					'acid'=>$apm_client_id,
					'length'=>100
				);
			$html = http_load($api_request, 0, $data);
			
			$log_update_id_delete = 0;
			
			if(substr($html,0,strlen('{"status":1'))=='{"status":1'){
				$ar_html = json_decode($html);
				$rows_update = $ar_html->result;
				
				$ada_update = false;
				
				if(count($rows_update)){
					
					echo "Processing updates...<br>\n";
					
					flush();
					// ob_flush();
					
					$ada_update = true;
				
					foreach($rows_update as $row_update){
						
						if($row_update->action=='delete'){
							$sql = "delete from $row_update->table_name where id = '$row_update->record_id'";
							$db->db_query($sql);
							echo $sql."<br>\n";
							$log_update_id_delete = $row_update->id;
						}else{
							$data = array(
									'token'=>'tug0eKy1kW5T88rez2tYXfI3F39g5M4I',
									"acid" => $apm_client_id,
									"table_name" => $row_update->table_name,
									"record_id" => $row_update->record_id,
									"log_update_id_delete" => $log_update_id_delete,
								);
							
							$api_request = $URLConfig->apm_central."/api/getTableRecord";
							$html = http_load($api_request, 0, $data);
							// echo $html."<br>\n";
							if(substr($html,0,strlen('{"status":1'))=='{"status":1'){
								$ar_html = json_decode($html);
								if(count($ar_html->result)){
									$row = $ar_html->result[0];
									
									// cek local record
									$result = $db->db_query("select * from ".$row_update->table_name." where id = '".$row_update->record_id."' limit 0,1");
									if($result['num_rows']>0){
										//update
										$sql = make_query_update($row, $row_update->table_name, 'id', $row_update->record_id);
										$db->db_query($sql);
									}else{
										//insert
										$sql = make_query_insert($row, $row_update->table_name);
										$db->db_query($sql);
									}
									
									echo $sql."<br>\n";
									
								}else{
									echo "New record has been deleted!"."<br>\n";
								}
								
								$log_update_id_delete = $row_update->id;
								
								flush();
								// ob_flush();
								
							}
						}
					}
					
					if($ada_update){
						$data = array(
								'token'=>'tug0eKy1kW5T88rez2tYXfI3F39g5M4I',
								"acid" => $apm_client_id,
								"log_update_id_delete" => $log_update_id_delete,
							);
						$api_request = $URLConfig->apm_central."/api/deleteUpdate";
						$html = http_load($api_request, 0, $data);
						echo $html."<br>\n";
					}
				}else{
					echo "No new update";
				}
			}
		}
	}
}

function make_query_update($rs_source, $table_name, $id_field_name, $id_field_value){
	$str_col_vals = "";
	foreach($rs_source as $key=>$val){
		if($key!=$id_field_name){
			if(is_null($val)){
				$str_value = "NULL";
			}
			else{
				$str_value = str_replace("'",chr(92)."'",$val);
				// $str_value = str_replace('"',chr(92).'"',$str_value);
				$str_value = str_replace(chr(145),chr(92).chr(145),$str_value);
				$str_value = str_replace(chr(146),chr(92).chr(146),$str_value);
				$str_value = str_replace(chr(13).chr(10),chr(92)."r".chr(92)."n",$str_value);
				$str_value = "'$str_value'";
			}
			$str_col_vals = $str_col_vals.($str_col_vals ? ", " : "")."`$key`=$str_value";
		}
	}
	return "update $table_name set $str_col_vals where $id_field_name = '$id_field_value'";
}

function make_query_insert($rs_source, $table_name){
	$str_columns = "";
	$str_values = "";
	foreach($rs_source as $key=>$val){
		$str_columns = $str_columns.($str_columns ? ", " : "")."`$key`";
		if(is_null($val)){
			$str_value = "NULL";
		}
		else{
			$str_value = str_replace("'",chr(92)."'",$val);
			// $str_value = str_replace('"',chr(92).'"',$str_value);
			$str_value = str_replace(chr(145),chr(92).chr(145),$str_value);
			$str_value = str_replace(chr(146),chr(92).chr(146),$str_value);
			$str_value = str_replace(chr(13).chr(10),chr(92)."r".chr(92)."n",$str_value);
			$str_value = "'$str_value'";
		}
		$str_values = $str_values.($str_values ? ", " : "").$str_value;
	}
	return "insert into $table_name ($str_columns) values ($str_values)";
}

function http_load($url, $maxlength=0, $array_post=array())
{
	// return file_get_contents($url);
	$ch = curl_init();
	$timeout = 0;
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
