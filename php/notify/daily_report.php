<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require_once("dbconnecti.php");

$id = $_GET['id'];

$db = new DBConnecti();

$sql = "SELECT	a.*, b.`phone_number`, c.`name` AS location_name, d.`name` AS operator_name
		FROM 	tb_report_daily AS a
				INNER JOIN `tb_location_device` AS b ON a.`location_device_id` = b.`id`
				INNER JOIN `tb_location` AS c ON b.`location_id` = c.`id`
				INNER JOIN `tb_operator` AS d ON b.`operator_id` = d.`id`
		WHERE 	a.id = '$id' AND NOT a.`status`";

$result = $db->db_query($sql);

if($result['num_rows']>0){
	$rows = $result['rows'];
	foreach($rows as $row){
		// sendEmail($db, $row);
		if(sendEmail($db, $row)) $db->db_query("UPDATE tb_report_daily SET status = 1 WHERE id = ".$row['id']);
	}
}

function sendEmail(&$db, $row){
	// get recipient
	$ar_address = array();
	$result_recipient = $db->db_query("SELECT * FROM tb_setting WHERE `name` = 'notify_email_report'");
	if($result_recipient['num_rows']>0){
		$ar_address = explode(';', str_replace(' ', '', $result_recipient['rows'][0]['value']));
	}
	
	require_once("phpmailer/phpmailer.php");
	
	/*$sender_email = "notification@realdataidapm.com";
	$sender_name = "Real Data APM Reports";
	$smtp_host = "mail.realdataidapm.com";
	$smtp_port = 587;
	$smtp_user = "notification@realdataidapm.com";
	$smtp_pass = '$Rdi2019%';*/
	
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
	
	foreach($ar_address as $address){
		if(trim($address)) $mail->AddAddress($address);
	}
	
	/* $mail->AddAddress("arism.awar@gmail.com");
	$mail->AddAddress("riza.agus.a@gmail.com"); */
	
	$mail->IsHTML(true);
	
	$subject = "Daily Report {location_name} {operator_name} {report_date}";
	$body = "
		<p>Device ID: {location_device_id}</p>
		<p>Phone Number: {phone_number}</p>
		<p>Location: {location_name}</p>
		<p>Telco: {operator_name}</p>
		<p>Date: {report_date}</p>
		";
	
	foreach($row as $key=>$val){
		$body = str_replace("{".$key."}", $val, $body);
		$subject = str_replace("{".$key."}", $val, $subject);
	}
	
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = strip_tags($body);
	
	$sql = "SELECT	a.*, b.`name` AS journey_name
		FROM 	`tb_report_daily_detail` AS a
				INNER JOIN `tb_journey` AS b ON a.`journey_id` = b.`id`
		WHERE	a.`report_daily_id` = '".$row['id']."'";
	
	$result = $db->db_query($sql);
	
	$ar_attach = array();
	if($result['num_rows']>0){
		$rows_detail = $result['rows'];
		foreach($rows_detail as $row_detail){
			$filename = "Daily Report ".$row['location_name']." "
				.$row['operator_name']." ".$row_detail['journey_name']." "
				.$row['report_date'].".pdf";
			$filename = str_replace('/','_',str_replace(' ','_', $filename));
			$mail->AddAttachment('../../userfiles/report_daily/'.$row_detail['filename'], $filename, 'base64', 'application/pdf');
			$ar_attach[] = '../../userfiles/report_daily/'.$row_detail['filename'];
		}
	}
	
	$mail->SMTPDEbug = 4;
	
	$result = $mail->Send();
	echo "Result: $result<br />";
	echo "Mailer Error: ".$mail->ErrorInfo;
	
	//delete report
	foreach($ar_attach as $attach){
		if(file_exists($attach)) unlink($attach);
	}
	
	return $result;
}
?>