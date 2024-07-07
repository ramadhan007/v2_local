<?php

date_default_timezone_set("Asia/Jakarta");

require_once("dbconnecti.php");

$starttime = microtime(true);

$retval = array();

$db = new DBConnecti();

$sql = "SELECT	* from tb_setting where `name` = 'autodelete_after'";

$result = $db->db_query($sql);

if($result){
	if($result["num_rows"]){
		$interval = $result["rows"][0]["value"];
		$unit = $result["rows"][0]["unit"];
		
		if($interval){
			// get to delete datetime
			$date=date_create(date("Y-m-d"));
			date_add($date, date_interval_create_from_date_string("-$interval $unit"));
			$date_delete = date_format($date, "Y-m-d");
			
			$body = "RealUSSDMon Auto Delete Log:<br />";
			
			$result = $db->db_query("SELECT	MIN(monitor_date) as min_date FROM tb_monitor_journey");
			
			if($result){
				if($result["num_rows"]){
					$min_date = $result["rows"][0]["min_date"];
					if(strtotime($min_date)<=strtotime($date_delete)){
						$body = "<br /><strong>Deletion</strong>: ".$min_date."<br />";
						
						$sql = "DELETE
							FROM 	tb_monitor_journey
							WHERE	monitor_date = '$min_date';";
				
						$body .= "<br /><br />";
						// $body .= "Result:<br />".$sql;
						$body .= "<strong>Result</strong>:<br />".json_encode($db->db_query($sql));
						
						$endtime = microtime(true);
						$timediff = $endtime - $starttime;
						
						$body .= "<br /><br />";
						$body .= "<strong>Elapsed Time</strong>:<br />".secondsToTime($timediff);
						echo "Mail Status: ".sendEmail($db, "RealUSSDMon Auto Delete Log ".date('d-M-Y H:i:s'), $body);
					}else{
						$body .= "<br /><br />";
						$body .= "<strong>Result</strong>:<br />No deletion was performed, data retention has already been met.";
						echo "Mail Status: ".sendEmail($db, "RealUSSDMon Auto Delete Log ".date('d-M-Y H:i:s'), $body);
					}
				}
			}
			
		}
	}
}

function sendEmail($db, $subject, $body){
	// get recipient
	$ar_address = array();
	$result_recipient = $db->db_query("SELECT * FROM tb_setting WHERE `name` = 'autodelete_notify'");
	// print_r($result_recipient);
	if($result_recipient['num_rows']>0){
		$ar_address = explode(';', str_replace(' ', '', $result_recipient['rows'][0]['value']));
	}
	
	// get recipient
	// $ar_address = array("arism.awar@gmail.com", "riza.agus.a@gmail.com");
	require_once("phpmailer/phpmailer.php");
	
	$sender_email = "mailer@senosoft.net";
	$sender_name = "Mailer Senosoft";
	$smtp_host = "mail.senosoft.net";
	$smtp_port = 465;
	$smtp_user = "mailer@senosoft.net";
	$smtp_pass = '$seno4r15%';
	
	/* $sender_email = "notification.realdataidapm@globalonesolusindo.com";
	$sender_name = "RDI Intermediate Server";
	$smtp_host = "smtp.gmail.com";
	$smtp_port = 465;
	$smtp_user = "notification.realdataidapm@globalonesolusindo.com";
	$smtp_pass = 'Real2019'; */
	
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
	
	foreach($ar_address as $address){
		if(trim($address)) $mail->AddAddress($address);
	}
	
	$mail->IsHTML(true);
	
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $body;
	
	return $mail->Send();
}

function secondsToTime($s)
{
    $h = floor($s / 3600);
    $s -= $h * 3600;
    $m = floor($s / 60);
    $s -= $m * 60;
	$ms = $s - floor($s);
	$s = floor($s);
    return $h.':'.sprintf('%02d', $m).':'.sprintf('%02d', $s).".".substr(sprintf('%0.3F', $ms),2,3);
	// return $s;
}

?>