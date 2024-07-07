<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$token = isset($_REQUEST["token"]) ? $_REQUEST["token"] : "";
		if($token!='tug0eKy1kW5T88rez2tYXfI3F39g5M4I'){
			echo json_encode(array("status"=>"0", "message"=>"Invalid token"));
			exit();
		}
	}
	
	public function index(){
	}
	
	public function getNewUpdate(){
		$apm_client_id = isset($_REQUEST['acid']) ? $_REQUEST['acid'] : 0;
		if($apm_client_id){
			$length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 100;
			$sql = "select id, table_name, record_id, action from tb_log_update where apm_client_id = '$apm_client_id' limit 0,$length";
			
			$this->_write_log($apm_client_id, $sql);
			
			$this->db->db_debug = false;
			$rows = $this->db->query($sql);
			$error = $this->db->error();
			
			if(!$error['code']){
				$result = $rows->result();
				$this->_write_log($apm_client_id, json_encode($result));
				
				$response['status'] = 1;
				$response['result'] = $rows->result();
				$response['message'] = '';
			}else{
				$this->_write_log($apm_client_id, json_encode($error));
				
				$response['status'] = 0;
				$response['result'] = '';
				$response['message'] = $error['message'];
			}
		}else{
			$response['status'] = 0;
			$response['result'] = '';
			$response['message'] = "Invalid APM Client ID";
		}
		
		echo json_encode($response);
	}
	
	public function getTableRecord(){
		$apm_client_id = isset($_REQUEST['acid']) ? $_REQUEST['acid'] : 0;
		if($apm_client_id){
			$table_name = $_REQUEST['table_name'];
			$record_id = $_REQUEST['record_id'];
			$log_update_id_delete = isset($_REQUEST['log_update_id_delete']) ? $_REQUEST['log_update_id_delete'] : 0;
			
			//delete_log_update
			if($log_update_id_delete){
				$sql = "delete from tb_log_update where apm_client_id = '$apm_client_id' and id = '$log_update_id_delete'";
			
				$this->_write_log($apm_client_id, $sql);
				
				$this->db->db_debug = false;
				$query = $this->db->query($sql);
				$error = $this->db->error();
				
				if($error['code']){
					$this->_write_log($apm_client_id, "Delete success");
				}else{
					$this->_write_log($apm_client_id, json_encode($error));
				}
			}
			
			$sql = "select * from ".$table_name." where id = '$record_id'";
			
			$this->_write_log($apm_client_id, $sql);
			
			$this->db->db_debug = false;
			$rows = $this->db->query($sql);
			$error = $this->db->error();
			
			if(!$error['code']){
				$result = $rows->result();
				$this->_write_log($apm_client_id, json_encode($result));
				
				$response['status'] = 1;
				$response['result'] = $rows->result();
				$response['message'] = '';
			}else{
				$this->_write_log($apm_client_id, json_encode($error));
				
				$response['status'] = 0;
				$response['result'] = '';
				$response['message'] = $error['message'];
			}
		}else{
			$response['status'] = 0;
			$response['result'] = '';
			$response['message'] = "Invalid APM Client ID";
		}
		
		echo json_encode($response);
	}
	
	public function deleteUpdate(){
		$apm_client_id = isset($_REQUEST['acid']) ? $_REQUEST['acid'] : 0;
		if($apm_client_id){
			$log_update_id_delete = isset($_REQUEST['log_update_id_delete']) ? $_REQUEST['log_update_id_delete'] : 0;
			
			//delete_log_update
			if($log_update_id_delete){
				$sql = "delete from tb_log_update where apm_client_id = '$apm_client_id' and id = '$log_update_id_delete'";
			
				$this->_write_log($apm_client_id, $sql);
				
				$this->db->db_debug = false;
				$query = $this->db->query($sql);
				$error = $this->db->error();
				
				if(!$error['code']){
					$this->_write_log($apm_client_id, "Delete success");
					$response['status'] = 1;
					$response['result'] = '';
					$response['message'] = "Delete success";
				}else{
					$this->_write_log($apm_client_id, json_encode($error));
					$response['status'] = 0;
					$response['result'] = '';
					$response['message'] = $error["message"];
				}
			}
			
		}else{
			$response['status'] = 0;
			$response['result'] = '';
			$response['message'] = "Invalid APM Client ID";
		}
		
		echo json_encode($response);
	}
	
	function _fix_sql_string($val){
		$str_value = str_replace("'",chr(92)."'",$val);
		$str_value = str_replace(chr(145),chr(92).chr(145),$str_value);
		$str_value = str_replace(chr(146),chr(92).chr(146),$str_value);
		$str_value = str_replace(chr(13).chr(10),chr(92)."r".chr(92)."n",$str_value);
		return $str_value;
	}
	
	function _write_log($device_id, $str_log){
		// create folder LOGS
		$this->load->helper('senofile');
		$dirname = "LOGS/DOWNLOAD/$device_id";
		$filename = $dirname."/LOG ".date('Y-m-d').".txt";
		prepDir($dirname);
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