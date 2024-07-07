<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$token = $this->input->get("token");
		if(!$token) $token = $this->input->post("token");
		if($token!='tug0eKy1kW5T88rez2tYXfI3F39g5M4I'){
			echo json_encode(array("status"=>"0", "message"=>"Invalid token"));
		}
		
		$this->db->query("SET TIME_ZONE = '+7:00'");
		
		/* $response['status'] = 0;
		$response['result'] = '';
		$response['message'] = 'Migration process...';
		
		echo json_encode($response); exit(); */
		
	}
	
	public function index()
	{
		$row = get_row("select now()");
		print_r($row);
		$row = get_row("select now()");
		print_r($row);
	}
	
	function _fix_sql_string($val){
		$str_value = str_replace("'",chr(92)."'",$val);
		$str_value = str_replace(chr(145),chr(92).chr(145),$str_value);
		$str_value = str_replace(chr(146),chr(92).chr(146),$str_value);
		$str_value = str_replace(chr(13).chr(10),chr(92)."r".chr(92)."n",$str_value);
		return $str_value;
	}
	
	public function uploadJourney(){	
		$device_id = $_REQUEST['device_id'];
		$journey_id = $_REQUEST['journey_id'];
		$location_lat = $_REQUEST['location_lat'];
		$location_lng = $_REQUEST['location_lng'];
		$monitor_datetime = $_REQUEST['monitor_datetime'];
		$sql = "SELECT fn_insert_monitor_journey('$device_id','$journey_id','$location_lat','$location_lng','$monitor_datetime') AS id";
		
		// exit($sql);
		
		$this->_write_log($device_id, $sql);
		
		$this->db->db_debug = false;
		$rows = $this->db->query($sql);
		$error = $this->db->error();
		
		if(!$error['code']){
			$result = $rows->result();
			$this->_write_log($device_id, json_encode($result));
			
			$response['status'] = 1;
			$response['result'] = $rows->result();
			$response['message'] = '';
		}else{
			$this->_write_log($device_id, json_encode($error));
			
			$response['status'] = 0;
			$response['result'] = '';
			$response['message'] = $error['message'];
		}
		
		echo json_encode($response);
	}
	
	public function uploadJourneyDetail(){
		$device_id = $_REQUEST['device_id'];
		$monitor_journey_id = $_REQUEST['monitor_journey_id'];
		$journey_detail_id = $_REQUEST['journey_detail_id'];
		$network_type = $_REQUEST['network_type'];
		if(strlen($network_type)>10) $network_type = substr($network_type,0,10);
		$cellid = $_REQUEST['cellid'];
		$signal_level = $_REQUEST['signal_level'];
		$signal_quality = $_REQUEST['signal_quality'];
		$ber = $_REQUEST['ber'];
		$response_time = $_REQUEST['response_time'];
		$latency = $_REQUEST['latency'];
		$packet_loss = $_REQUEST['packet_loss'];
		$status = $_REQUEST['status'];
		$message = $this->_fix_sql_string($_REQUEST['message']);
		$monitor_datetime = $_REQUEST['monitor_datetime'];
		$repeat_no = $_REQUEST['repeat_no'];
		
		$sql = "SELECT fn_insert_monitor_journey_detail('$device_id','$monitor_journey_id','$journey_detail_id','$network_type','$cellid','$signal_level','$signal_quality','$ber','$response_time','$latency','$packet_loss','$status','$message','$monitor_datetime','$repeat_no') AS id";
		
		$this->_write_log($device_id, $sql);
		
		$this->db->db_debug = false;
		$rows = $this->db->query($sql);
		$error = $this->db->error();
		
		if(!$error['code']){
			$result = $rows->result();
			$this->_write_log($device_id, json_encode($result));
			
			$response['status'] = 1;
			$response['result'] = $rows->result();
			$response['message'] = '';
		}else{
			$this->_write_log($device_id, json_encode($error));
			
			$response['status'] = 0;
			$response['result'] = '';
			$response['message'] = $error['message'];
		}
		
		echo json_encode($response);
	}
	
	public function uploadJourneyNvt(){
		$device_id = $_REQUEST['device_id'];
		//get & decrypt posted params
		$monitor_journey_id = $_REQUEST['monitor_journey_id'];
		// $journey_detail_id = $_REQUEST['journey_detail_id'];
		$network_type = $_REQUEST['network_type'];
		if(strlen($network_type)>10) $network_type = substr($network_type,0,10);
		$cellid = $_REQUEST['cellid'];
		$signal_level = $_REQUEST['signal_level'];
		$signal_quality = $_REQUEST['signal_quality'];
		$ber = $_REQUEST['ber'];
		$response_time = $_REQUEST['response_time'];
		$latency = $_REQUEST['latency'];
		$packet_loss = $_REQUEST['packet_loss'];
		$status = $_REQUEST['status'];
		$message = $this->_fix_sql_string($_REQUEST['message']);
		$monitor_datetime = $_REQUEST['monitor_datetime'];
		$repeat_no = $_REQUEST['repeat_no'];
		
		$sql = "SELECT fn_insert_monitor_journey_nvt('$device_id', '$monitor_journey_id','$network_type','$cellid','$signal_level','$signal_quality','$ber','$response_time','$latency','$packet_loss','$status','$message','$monitor_datetime','$repeat_no') AS id";
		
		$this->_write_log($device_id, $sql);
		
		$this->db->db_debug = false;
		$rows = $this->db->query($sql);
		$error = $this->db->error();
		
		if(!$error['code']){
			$result = $rows->result();
			$this->_write_log($device_id, json_encode($result));
			
			$response['status'] = 1;
			$response['result'] = $rows->result();
			$response['message'] = '';
		}else{
			$this->_write_log($device_id, json_encode($error));
			
			$response['status'] = 0;
			$response['result'] = '';
			$response['message'] = $error['message'];
		}
		
		echo json_encode($response);
	}
	
	public function uploadScreenshot()
	{
		$screenshot_path_rel = get_val("select value from tb_setting where name = 'screenshot_path_rel'");
		
		//upload config
		$config['upload_path'] = $screenshot_path_rel;
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '10240';
		$config['encrypt_name'] = FALSE;
		$config['overwrite'] = TRUE;
		
		$response  = array();
		
		$ext = get_val("select value from tb_setting where name = 'screenshot_ext'");
		
		$table = $this->input->post('table');
		$id = $this->input->post('id');
		// $this->upload_config['upload_path'] .= "/".$table;
		$config['file_name'] = $id.".".$ext;
		
		$this->load->helper('senofile');
		$this->load->library('upload', $config);
		
		if(isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])){
			// $this->upload->initialize($this->upload_config);
			prepDir($config['upload_path']);
			if (!$this->upload->do_upload('file')){	//upload gagal
				$response['status'] = 0;
				$response['message'] = $this->upload->display_errors();
			}else{	// upload sukses
			
				run_query("update ".$table." set has_screenshot = '1' where id = $id;");
				
				$response['status'] = 1;
				$response['message'] = 'File uploaded successfully!';
			}
		}
		else{
			$response['status'] = 0;
			$response['message'] = 'File is missing';
		}
		
		echo json_encode($response);
	}
	
	public function uploadScreenshotTest()
	{
		//upload config
		$config['upload_path'] = "userfiles/test";
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = '10240';
		$config['encrypt_name'] = TRUE;
		$config['overwrite'] = TRUE;
		
		$response  = array();
		
		$this->load->helper('senofile');
		$this->load->library('upload', $config);
		
		if(isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])){
			// $this->upload->initialize($this->upload_config);
			prepDir($config['upload_path']);
			if (!$this->upload->do_upload('file')){	//upload gagal
				$response['status'] = 0;
				$response['message'] = $this->upload->display_errors();
			}else{	// upload sukses
			
				$response['status'] = 1;
				$response['message'] = 'File uploaded successfully!';
			}
		}
		else{
			$response['status'] = 0;
			$response['message'] = 'File is missing';
		}
		
		echo json_encode($response);
	}
	
	function _write_log($device_id, $str_log){
		// create folder LOGS
		$this->load->helper('senofile');
		$dirname = "LOGS/UPLOAD/$device_id";
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