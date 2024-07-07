<?php

class DBConnecti {
	
	var $host;
	var $user;
	var $db;
	var $pass;
	
	var $mysqli;
	var $connected = false;
	
	public function __construct($params = array())
	{
		$this->host = $params['host'];
		$this->user = $params['user'];
		$this->db = $params['db'];
		$this->pass = $params['pass'];
	}
	
	public function open(){
		$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);
		
		if (mysqli_connect_errno()) {
			$this->connected = false;
		}else{
			$this->connected = true;
		}
	}
	
	public function close(){
		if($this->connected){
			$this->mysqli->close();
			$this->connected = false;
		}
	}
	
	public function query($query){
		// connect database
		$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);
		
		// check connection
		if (mysqli_connect_errno()) {
			$error = "DB Connect Error: ".mysqli_connect_error();
			return array('status'=>0,'affected_rows'=>0,'num_rows'=>0,'num_cols'=>0,'rows'=>array(),'error'=>$error);
		}
		
		// do query
		$result = $this->mysqli->query($query);
		if($result===false){
			$error = "Query Error: ".$this->mysqli->error;
			if(!$this->connected) $this->mysqli->close();
			return array('status'=>0,'affected_rows'=>0,'num_rows'=>0,'num_cols'=>0,'rows'=>array(),'error'=>$error);
		}
		else{
			$affected_rows = $this->mysqli->affected_rows;
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
			if(!$this->connected) $this->mysqli->close();
			return array('status'=>1,'affected_rows'=>$affected_rows,'num_rows'=>$num_rows,'num_cols'=>$num_cols,'rows'=>$rows,'error'=>"");
		}
	}
}

?>