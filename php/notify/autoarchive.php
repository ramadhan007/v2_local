<?php

require_once("dbconnecti.php");

$db_src = new DBConnecti();

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon_archive',
	'pass' => '$seno4r15%',
);

$db_dest = new DBConnecti($params);



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

// echo json_encode($db->db_query("select * from tb_monitor_journey_detail"));

?>