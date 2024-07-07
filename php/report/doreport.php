<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("dbconnecti.php");

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon_report',
	'pass' => '$seno4r15%',
);

$db = new DBConnecti($params);

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon',
	'pass' => '$seno4r15%',
);

$db_src = new DBConnecti($params);

$sql = "select * from tb_report_date";
$result = $db->query($sql);
if(!$result['status']) exit(json_encode($result));
if(!$result['rows'][0]['completed'] && !$result['rows'][0]['is_running'] && !$result['rows'][0]['force_stop']){	//if not completed and not running and not force_stop

	$ids = json_decode($result['rows'][0]['ids']);
	$date_start = $result['rows'][0]['date_start'];
	$date_end = $result['rows'][0]['date_end'];

	$sql = "update tb_report_date set started = 1, is_running = 1".(!$result['rows'][0]['started'] ? ", time_start = now()" : "");
	$result = $db->query($sql);
	if(!$result['status']) exit(json_encode($result));
	
	$id_cur = array();
	$ada_record = array();
	$completed = true;
	foreach($ids as $key=>$val){
		$ada_record[$key] = false;
		$table = "tb_".$key;
		// echo $key;
		// var_dump($val);
		
		$sql = "SELECT	*
				FROM 	`$table`
				WHERE	(id >= ".$val->id_min." AND id <= ".$val->id_max.") AND id > ".$val->id_cur."
				LIMIT 	0,1000";
				
		$result = $db_src->query($sql);
		if($result['status'] && $result['num_rows']){
			$ada_record[$key] = true;
			$db->open();
			foreach($result['rows'] as $row){
				$sql = make_query_insert($row, "tb_report_".$key);
				$db->query($sql);
				if(!($row['id'] % 100)){
					$val->id_cur = $row['id'];
					$str_ids = json_encode((object)$ids, JSON_PRETTY_PRINT);
					$sql = "update tb_report_date set ids = '".fix_db_value($str_ids)."', time_now = now()";
					$db->query($sql);
				}
			}
			$db->close();
			$val->id_cur = $row['id'];
		}
		
		$completed = ($completed && ($val->id_cur==$val->id_max));
		
		$str_ids = json_encode((object)$ids, JSON_PRETTY_PRINT);
		$sql = "update tb_report_date set ids = '".fix_db_value($str_ids)."', time_finish = time_now()";
		$db->query($sql);
	}
	
	$str_ids = json_encode((object)$ids, JSON_PRETTY_PRINT);
	$sql = "update tb_report_date set is_running = 0".($completed ? ", completed = 1, ids = '".fix_db_value($str_ids)."', time_finish = now()" : "");
	$result = $db->query($sql);
	if(!$result['status']) exit(json_encode($result));
	if($completed){
		sendEmail($date_start, $date_end);
	}
}

function sendEmail($date_start, $date_end){
	require_once("../notify/phpmailer/phpmailer.php");
	
	$sender_email = "notification.realdataidapm@globalonesolusindo.com";
	$sender_name = "Real Data APM Reports";
	$smtp_host = "smtp.gmail.com";
	$smtp_port = 465;
	$smtp_user = "notification.realdataidapm@globalonesolusindo.com";
	$smtp_pass = 'Real2019';
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = $smtp_host;
	$mail->Port = $smtp_port;
	$mail->SMTPSecure = 'ssl';
	$mail->SMTPAuth = '1';
	$mail->Username = $smtp_user;
	$mail->Password = $smtp_pass;
	
	$mail->From = $sender_email;
	$mail->FromName = $sender_name;
	$mail->Sender = $sender_email;
	$mail->AddReplyTo($sender_email, $sender_name);
	
	$mail->AddAddress("arism.awar@gmail.com");
	$mail->AddAddress("riza.agus.a@gmail.com");
	$mail->AddAddress("riza.agus.a@senosoft.net");
	
	$subject = "Reporting Task Finished: $date_start to $date_end";
	
	$mail->IsHTML(true);
	
	$body = "Yeay... Selesai Mas Bro!!";
	
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $body;
	
	return $mail->Send();
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
			$str_value = "'".fix_db_value($val)."'";
		}
		$str_values = $str_values.($str_values ? ", " : "").$str_value;
	}
	return "insert into $table_name ($str_columns) values ($str_values)";
}

function fix_db_value($val){
	$str_value = str_replace("'",chr(92)."'",$val);
	$str_value = str_replace(chr(145),chr(92).chr(145),$str_value);
	$str_value = str_replace(chr(146),chr(92).chr(146),$str_value);
	$str_value = str_replace(chr(13).chr(10),chr(92)."r".chr(92)."n",$str_value);
	return $str_value;
}

?>