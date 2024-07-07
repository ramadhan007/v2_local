<?php

class DBConnecti {
	
	var $host;
	var $user;
	var $db;
	var $pass;
	
	public function __construct($params = array())
	{
		if($params){
			$this->host = $params['host'];
			$this->user = $params['user'];
			$this->db = $params['db'];
			$this->pass = $params['pass'];
		}else{
			//get db config
			require_once("../../config/dbconfig.php");
			$config = new DBConfig();
			$this->host = $config->host;
			$this->user = $config->user;
			$this->db = $config->db;
			$this->pass = $config->pass;
		}
	}
	
	public function db_query($query){
		$host = $this->host;
		$username = $this->user;
		$password = $this->pass;
		$database = $this->db;
		
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
}

?>