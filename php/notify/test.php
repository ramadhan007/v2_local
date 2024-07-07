<?php

function sendEmail(){
	require_once("phpmailer/phpmailer.php");
	
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
	
	$subject = "Ini Adalah Judul";
	
	$mail->IsHTML(true);
	
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
	
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $body;
	
	return $mail->Send();
}

function dateDifference($date_1 , $date_2)
{
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);
   
	$interval = date_diff($datetime1, $datetime2);
	
	$output = formatTime($interval);
	
	return $output;
   
}

function formatTime($interval)
{
	$output = $interval->format('#%y# Years #%m# Months #%d# Days #%h# Hours #%i# Minutes #%s# Seconds');
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

// echo dateDifference('2019-01-08 05:20:15', '2019-01-18 06:35:56');
// echo dateDifference('00:00:00', '06:35:56');
echo "Sending email...<br />";
echo "Result: ".sendEmail();

?>