<?php

require_once("dbconnecti.php");

$db = new DBConnecti();

$sql = "SELECT 	a.*, b.phone_number, c.`name` AS location_name, d.`name` AS operator_name,
				g.`name` AS journey_detail_name, h.`name` AS journey_name
		FROM 	tb_error AS a
				INNER JOIN `tb_location_device` AS b ON a.`location_device_id` = b.`id`
				INNER JOIN `tb_location` AS c ON b.`location_id` = c.`id`
				INNER JOIN `tb_operator` AS d ON b.`operator_id` = d.`id`
				LEFT JOIN `tb_monitor_journey_detail` AS e ON a.`monitor_journey_detail_id` = e.`id`
				LEFT JOIN `tb_monitor_journey` AS f ON e.`monitor_journey_id` = f.`id`
				LEFT JOIN `tb_journey_detail` AS g ON e.`journey_detail_id` = g.`id`
				LEFT JOIN `tb_journey` AS h ON f.`journey_id` = h.`id`
		WHERE 	a.status_email <= 0
		ORDER 	BY id ASC";

$result_error = $db->db_query($sql);

if($result_error['num_rows']>0){
	$rows_error = $result_error['rows'];
	foreach($rows_error as $row_error){
		if($row_error['status']=='1'){
			if($row_error['status_email']==-1){
				if(sendEmail($db, $row_error, "0")) $db->db_query("UPDATE tb_error SET status_email = status_email+1 WHERE id = ".$row_error['id']);
			}
			if(sendEmail($db, $row_error, "1")) $db->db_query("UPDATE tb_error SET status_email = status_email+1 WHERE id = ".$row_error['id']);
		}else{
			if(sendEmail($db, $row_error, "0")) $db->db_query("UPDATE tb_error SET status_email = status_email+1 WHERE id = ".$row_error['id']);
		}
	}
}

function sendEmail(&$db, $row_error, $status){
	// get recipient
	$ar_address = array();
	$result_recipient = $db->db_query("SELECT * FROM tb_setting WHERE `name` = 'notify_email_level_".$row_error['level']."'");
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
	
	/* foreach($ar_address as $address){
		if(trim($address)) $mail->AddAddress($address);
	}*/
	
	$mail->AddAddress("arism.awar@gmail.com");
	
	// $mail->Subject = $row_error['title'];
	if($status=='1'){	//recovered
		$subject = "{journey_detail_name} Page is working fine : ".date('d-M-Y H:i:s', strtotime($row_error['recover_datetime']));
	}else{
		$subject = "Level {level}: Error On {journey_detail_name} : ".date('d-M-Y H:i:s', strtotime($row_error['error_datetime']));
	}
	$mail->IsHTML(true);
	
	if($status=='1'){	//recovered
		$body = "
		<p>{journey_detail_name} is working fine</p>
		<p>Application Name: {application_name}</p>
		<p>{recover_datetime}</p>
		<p>Error Age: {aging}</p>
		";
		$aging = dateDifference($row_error['error_datetime'], $row_error['recover_datetime']);
		$body = str_replace("{aging}", $aging, $body);
	}else{
		$body = "
		<p>Level {level}</p>
		<p>Error On: {journey_detail_name}</p>
		<p>Error: {description}</p>
		<p>Application Name: {application_name}</p>
		<p>Phone Number: {phone_number}</p>
		<p>Location: {location_name}</p>
		<p>Telco: {operator_name}</p>
		<p>Transaction Name: {journey_name}</p>
		<p>Date : {error_datetime}</p>
		";
	}
	
	$body = str_replace("{recover_datetime}", date('d-M-Y H:i:s', strtotime($row_error['recover_datetime'])), $body);
	$body = str_replace("{error_datetime}", date('d-M-Y H:i:s', strtotime($row_error['error_datetime'])), $body);
	
	foreach($row_error as $key=>$val){
		$body = str_replace("{".$key."}", $val, $body);
		$subject = str_replace("{".$key."}", $val, $subject);
	}
	$application_name = str_replace(' ','_', $row_error['journey_name']);
	$application_name .= '_'.str_replace(' ','_', $row_error['location_name']);
	$application_name .= '_'.str_replace(' ','_', $row_error['operator_name']);
	$application_name = str_replace('/','_', $application_name);
	$body = str_replace("{application_name}", $application_name, $body);
	
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $row_error['description'];
	
	if($status=='0'){	//error
		if($row_error['has_screenshot']){
			if($row_error['monitor_journey_detail_id']){
				$path = "monitor_journey_detail/".$row_error['monitor_journey_detail_id'];
			}else{
				$path = "error/".$row_error['id'];
			}
			$mail->AddAttachment('public_html/realussdmon/userfiles/screenshot/'.$path.".jpg", 'Screenshot.jpg', 'base64', 'images/jpeg');
		}
	}
	
	return $mail->Send();
}

function dateDifference($date_1 , $date_2 , $differenceFormat = '#%y# Years #%m# Months #%d# Days #%h# Hours #%i# Minutes #%s# Seconds' )
{
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);
   
	$interval = date_diff($datetime1, $datetime2);
   
	$output = $interval->format($differenceFormat);
	$output = str_replace('#0# Years ', '', $output);
	$output = str_replace('#0# Months ', '', $output);
	$output = str_replace('#0# Days ', '', $output);
	$output = str_replace('#0# Hours ', '', $output);
	$output = str_replace('#0# Minutes ', '', $output);
	$output = str_replace('#0# Seconds', '', $output);
	
	$output = str_replace('#1# Years ', '#1# Year ', $output);
	$output = str_replace('#1# Months ', '#1# Month ', $output);
	$output = str_replace('#1# Days ', '#1# Day ', $output);
	$output = str_replace('#1# Hours ', '#1# Hour ', $output);
	$output = str_replace('#1# Minutes ', '#1# Minute ', $output);
	$output = str_replace('#1# Seconds', '#1# Second', $output);
	
	$output = str_replace('#', '', $output);
	
	return $output;
   
}

?>