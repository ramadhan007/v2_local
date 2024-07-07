<?php

date_default_timezone_set('Asia/Jakarta');

require_once("simplecrypt.php");
require_once("dbconnecti.php");

//get class
$crypt = new SimpleCrypt();
$db = new DBConnecti();

//get & decrypt posted params
$sql = $crypt->Decrypt($_POST['sql']);

$sql = fix_sql($sql);

$device_id = get_device_id($db, $sql);

$db->write_log($device_id, $sql);

$result = $db->db_query($sql);
	
$db->write_log($device_id, json_encode($result));
	
echo json_encode($result);

function get_device_id($db, $str){
	$str_id = "WOW";
	if(strpos($str,"fn_insert_monitor_journey(")!==FALSE){
		$str = str_replace("( '", "('", $str);
		$ar = explode("'", $str);
		$str_id = $ar[1];
	}elseif(strpos($str,"fn_insert_monitor_journey_detail(")!==FALSE || strpos($str,"fn_insert_monitor_journey_nvt(")!==FALSE){
		$ar = explode("'", $str);
		$monitor_journey_id = $ar[1];
		$monitor_journey_id;
		
		$sql = "SELECT	`device_id`
				FROM 	`tb_monitor_journey`
				WHERE	id = $monitor_journey_id";
		
		$result = $db->db_query($sql);
		$row = $result['rows'];
		
		$str_id = $row[0]['device_id'];
	}elseif(strpos($str,"tb_device")!==FALSE){
		$str = strtolower($str);
		$str_id = http_get_after($str, "where id = ");
		$str_id = str_replace("'","",$str_id);
		$str_id = str_replace(";","",$str_id);
		$str_id;
	}
	return $str_id;
}

function fix_sql($str){
	if(strpos($str, "('")!==FALSE && strpos($str,"')")!==FALSE){
		$str = str_replace("', '", "',", $str);
		$ar = explode("','", $str);
		
		for($i=0;$i<count($ar); $i++){
			if($i==0){
				if(strpos($ar[$i],"('")!==FALSE){
					$str_intro = http_get_before($ar[$i], "('");
					$str_temp = http_get_after($ar[$i], "('");
					$str_temp = str_replace("'", "''", $str_temp);
					$str_temp = $str_intro."('".$str_temp;
				}else{
					$str_temp = str_replace("'", "''", $ar[$i]);
				}
				$ar[$i] = $str_temp;
			}
			elseif($i==count($ar)-1){
				if(strpos($ar[$i],"')")!==FALSE){
					$str_intro = http_get_after($ar[$i], "')");
					$str_temp = http_get_before($ar[$i], "')");
					$str_temp = str_replace("'", "''", $str_temp);
					$str_temp = $str_temp."')".$str_intro;
				}else{
					$str_temp = str_replace("'", "''", $ar[$i]);
				}
				$ar[$i] = $str_temp;
			}
			else{
				$ar[$i] = str_replace("'", "''", $ar[$i]);
			}
		}
		
		return implode("','", $ar);
	}else{
		return $str;
	}
}

function http_get_after($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,$i+strlen($strcari));
	return $strtmp;
}

function http_get_before($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,0,$i);
	return $strtmp;
}

function http_get_between($strtext, $strawal, $strakhir)
{
	return http_get_before(http_get_after($strtext, $strawal), $strakhir);
}

?>