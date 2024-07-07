<?php

require_once("../config/dbconfig.php");

class DBConnecti {
	
	public function db_query($query) {

		//get db config
		$config = new DBConfig();
		$host = $config->host;
		$username = $config->user;
		$password = $config->pass;
		$database = $config->db;
		
		// connect database
		$mysqli = new mysqli($host, $username, $password, $database);
		
		// check connection
		if (mysqli_connect_errno()) {
			$error = "DB Connect Error: ".mysqli_connect_error();
			return array('status'=>0,'affected_rows'=>0,'num_rows'=>0,'num_cols'=>0,'rows'=>array(),'error'=>$error);
		}
		
		// do query
		$result = $mysqli->query($query);
		if($result===false){
			$error = "Query Error: ".$mysqli->error;
			$mysqli->close();
			return array('status'=>0,'affected_rows'=>0,'num_rows'=>0,'num_cols'=>0,'rows'=>array(),'error'=>$error);
		}
		else{
			$affected_rows = $mysqli->affected_rows;
			$num_rows = isset($result->num_rows) ? $result->num_rows : 0;
			$num_cols = 0;
			$rows = array();
			if($num_rows){
				while ($row = $result->fetch_assoc()){
					$rows[] = $row;
				}
				$num_cols = count($rows[0]);
				$result->close();
			}
			$mysqli->close();
			return array('status'=>1,'affected_rows'=>$affected_rows,'num_rows'=>$num_rows,'num_cols'=>$num_cols,'rows'=>$rows,'error'=>"");
		}
	}
	
	public function write_log($device_id, $str_log){
		// create folder LOGS
		$filename = "LOGS/$device_id/LOG ".date('Y-m-d').".txt";
		if(!file_exists('LOGS')) mkdir('LOGS');
		if(!file_exists('LOGS/'.$device_id)) mkdir('LOGS/'.$device_id);
		if(!file_exists($filename)){
			file_put_contents($filename, "Log ".date('l, j F Y')."\n");
		}
		
		$time    = microtime(true);
		$mSecs   =  $time - floor($time);
		$str = date('H:i:s').substr($mSecs,1,6);
		
		file_put_contents($filename, "\n".$str.": ".$str_log, FILE_APPEND);
	}
	
}

?>