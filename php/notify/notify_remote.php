<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require_once("dbconnecti.php");

$db = new DBConnecti();

$sql = "SELECT 	a.*, b.id as location_device_id, b.application, (CASE b.application WHEN 'eform' THEN 'BTPN e-Form' WHEN 'wow' THEN 'BTPN Bank WOW' ELSE '' END) AS application_name, b.phone_number, c.`name` AS location_name, d.`name` AS operator_name,
				g.`name` AS journey_detail_name, h.`name` AS journey_name,
				ROUND(fn_get_monitor_journey_nvt_response_time(f.`id`),3) AS nvt_response_time,
				fn_get_monitor_journey_nvt_signal_level(f.`id`) AS nvt_signal_level
		FROM 	tb_error AS a
				INNER JOIN `tb_location_device` AS b ON a.`location_device_id` = b.`id`
				INNER JOIN `tb_location` AS c ON b.`location_id` = c.`id`
				INNER JOIN `tb_operator` AS d ON b.`operator_id` = d.`id`
				LEFT JOIN `tb_monitor_journey_detail` AS e ON a.`monitor_journey_detail_id` = e.`id`
				LEFT JOIN `tb_monitor_journey` AS f ON e.`monitor_journey_id` = f.`id`
				LEFT JOIN `tb_journey_detail` AS g ON e.`journey_detail_id` = g.`id`
				LEFT JOIN `tb_journey` AS h ON f.`journey_id` = h.`id`
		WHERE 	a.status_email <= 0
		ORDER 	BY id ASC
		LIMIT 	0,10";

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
	
	foreach($ar_address as $address){
		if(trim($address)) $mail->AddAddress($address);
	}
	
	/* $mail->AddAddress("arism.awar@gmail.com");
	$mail->AddAddress("riza.agus.a@gmail.com"); */
	
	// $mail->Subject = $row_error['title'];
	if($row_error['type']=='4'){
		if($status=='1'){	//recovered
			$subject = "{location_name} {operator_name} is Back Online : ".date('d-M-Y H:i:s', strtotime($row_error['recover_datetime']));
		}else{
			$subject = "{location_name} {operator_name} is Offline : ".date('d-M-Y H:i:s', strtotime($row_error['error_datetime']));
		}
	}else{
		if($status=='1'){	//recovered
			$subject = "{journey_detail_name} Page is working fine : ".date('d-M-Y H:i:s', strtotime($row_error['recover_datetime']));
		}else{
			$subject = "Level {level}: Error On {journey_detail_name} Page : ".date('d-M-Y H:i:s', strtotime($row_error['error_datetime']));
		}
	}
	$mail->IsHTML(true);

	$body_wa = "";

	if($row_error['type']=='4'){
		if($status=='1'){	//recovered
			$body = 
"<p>Uploading process resumed
</p>
<p>Ref : {id}
</p>
<p>Phone Number : {phone_number}
</p>
<p>Location : {location_name}
</p>
<p>Telco : {operator_name}
</p>
<p>Date : {recover_datetime}
</p>
<p>Error Age : {aging}
</p>";
			$aging = dateDifference($row_error['error_datetime'], $row_error['recover_datetime']);
			$body = str_replace("{aging}", $aging, $body);
			$body_wa = get_sentence(1);
		}else{
			$body = 
"<p>Level {level}
</p>
<p>Title : {title}
</p>
<p>Ref : {id}
</p>
<p>Error Description : {description}
</p>
<p>Phone Number : {phone_number}
</p>
<p>Location : {location_name}
</p>
<p>Telco : {operator_name}
</p>
<p>Date : {error_datetime}
</p>";
			$body_wa = get_sentence(0);
		}
	}else{
		if($status=='1'){	//recovered
			$body = 
"<p>{journey_detail_name} Page is working fine
</p>
<p>Ref : {id}
</p>
<p>Application Name : {application_name}
</p>
<p>Phone Number : {phone_number}
</p>
<p>Location : {location_name}
</p>
<p>Telco : {operator_name}
</p>
<p>Transaction Name : {journey_name}
</p>
<p>Date : {recover_datetime}
</p>
<p>Error Age : {aging}
</p>";
			$aging = dateDifference($row_error['error_datetime'], $row_error['recover_datetime']);
			$body = str_replace("{aging}", $aging, $body);
		}else{
			$body = 
"<p>Level {level}
</p>
<p>Error On : {journey_detail_name} Page
</p>
<p>Ref : {id}
</p>
<p>Error : ".($row_error['type']=='2' ? "Unable to get expected page" : "{description}")."
</p>
<p>Error Category : {title}
</p>
<p>Application Name : {application_name}
</p>
<p>Phone Number : {phone_number}
</p>
<p>Location : {location_name}
</p>
<p>Telco : {operator_name}
</p>
<p>Transaction Name : {journey_name}
</p>
<p>NVT Response Time : {nvt_response_time} Secs
</p>
<p>NVT Signal Strength : {nvt_signal_level} dBm
</p>
<p>Date : {error_datetime}
</p>";
		}
	}
	
	$body = str_replace("{recover_datetime}", date('d-M-Y H:i:s', strtotime($row_error['recover_datetime'])), $body);
	$body = str_replace("{error_datetime}", date('d-M-Y H:i:s', strtotime($row_error['error_datetime'])), $body);
	
	foreach($row_error as $key=>$val){
		$body = str_replace("{".$key."}", $val, $body);
		$subject = str_replace("{".$key."}", $val, $subject);
		$body_wa = str_replace("{".$key."}", $val, $body_wa);
	}
	// $application_name = str_replace(' ','_', $row_error['journey_name']);
	// $application_name .= ' / '.str_replace(' ','_', $row_error['location_name']);
	// $application_name = str_replace('/','_', $application_name);
	$application_name = $row_error['application_name'];
	$body = str_replace("{application_name}", $application_name, $body);
	
	$body = 
"<!DOCTYPE html><html><head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
</head>
<body>
".$body;

	$body .= 
"
</body>
</html>";
	
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $row_error['description'];
	
	// echo $body;
	
	if($status=='0'){	//error
		if($row_error['has_screenshot']){
			if($row_error['monitor_journey_detail_id']){
				$path = "monitor_journey_detail/".$row_error['monitor_journey_detail_id'];
			}else{
				$path = "error/".$row_error['id'];
			}
			$mail->AddAttachment('../../userfiles/screenshot/'.$path.".jpg", 'Screenshot.jpg', 'base64', 'images/jpeg');
		}
	}
	
	$mail->SMTPDEbug = 4;
	
	$result = $mail->Send();
	echo "Result: $result<br />";
	echo "Mailer Error: ".$mail->ErrorInfo;
	
	if($row_error['application']=='wow'){
		if($row_error['type']=='4'){
			// if($body_wa!=""){
				// send_notif_wa($body_wa);
			// }
			$db = new DBConnecti();
			if($status=='1'){	//recovered
				$db->db_query("call update_location_device_notif('".$row_error['location_device_id']."', '1')");
			}else{
				$db->db_query("call insert_location_device_notif('".$row_error['location_device_id']."')");
			}
		}
	}
	
	return $result;
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

function send_notif_wa($message){
	$array_post = array(
		'token' => "a8gUD5vEdlSXgG8L2k",
		'recipient' => 'SENOSOFT - TS',
		'recipient_type' => '1',
		'message' => $message,
	);
	http_load('http://42.1.62.186/senowabot/savenotif.php', 0, $array_post);
}

function http_load($url, $maxlength=0, $array_post=array())
{
	$ch = curl_init();
	$timeout = 0;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if($array_post)
	{
		$fields_string = '';
		foreach($array_post as $key=>$value)
		{
			$fields_string .= $key.'='.$value.'&';
		}
		//echo $fields_string; exit;
		rtrim($fields_string, '&');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	if($maxlength>0)
	{
		curl_setopt($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD, $maxlength);
	}
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return $file_contents;
}

function http_get_after($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,$i+strlen($strcari));
	return trim($strtmp);
}

function http_get_before($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,0,$i);
	return trim($strtmp);
}

function http_get_between($strtext, $strawal, $strakhir)
{
	preg_match('@'.$strawal.'(.*?)'.$strakhir.'@si',$strtext,$data);
	if(count($data)>1){
		return $data[1];
	}else{
		return '';
	}
}

function get_sentence($status){
	$ar_sentences_off = array(
			'BOT {location_name} {operator_name} OFF',
			'BOT {location_name} {operator_name} offline',
			'BOT {location_name} {operator_name} terdeteksi OFF',
			'BOT {location_name} {operator_name} terdeteksi offline',
			'BOT {location_name} {operator_name} sepertinya OFF',
			'BOT {location_name} {operator_name} sepertinya offline',
			'BOT {location_name} {operator_name} kayaknya offline',
			'BOT {location_name} {operator_name} OFF, tolong dicek ya!',
			'BOT {location_name} {operator_name} OFF, tolong dicek ya guys!',
			'BOT {location_name} {operator_name} OFF, tolong dicek ya tim!',
			'Hai gaes, sepertinya BOT {location_name} {operator_name} offline nih!',
			'Hai gaes, sepertinya BOT {location_name} {operator_name} offline nih, tolong dicek ya guys',
			'Halo tim, sepertinya BOT {location_name} {operator_name} offline nih!',
			'Halo tim, sepertinya BOT {location_name} {operator_name} offline nih, tolong dicek ya',
			'Oe.. BOT {location_name} {operator_name} offline tuh!',
			'Oe.. BOT {location_name} {operator_name} offline tuh, buruan dicek ya!',
			'Oe.. BOT {location_name} {operator_name} offline tuh, jangan lupa dicek ya!',
		);
		
	$ar_sentences_on = array(
			'BOT {location_name} {operator_name} sudah ON',
			'BOT {location_name} {operator_name} sudah online',
			'Yeay.. BOT {location_name} {operator_name} sudah online',
			'Yeay.. BOT {location_name} {operator_name} sudah online lagi',
			'Yeay.. BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
			'Yes.. BOT {location_name} {operator_name} sudah online',
			'Yes.. BOT {location_name} {operator_name} sudah online lagi',
			'Yes.. BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
			'Mantap! BOT {location_name} {operator_name} sudah online',
			'Mantap! BOT {location_name} {operator_name} sudah online lagi',
			'Mantap! BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
			'Alhamdulillah! BOT {location_name} {operator_name} sudah online',
			'Alhamdulillah! BOT {location_name} {operator_name} sudah online lagi',
			'Alhamdulillah! BOT {location_name} {operator_name} sudah online lagi, thanks tim!',
		);
	
	if($status){
		$ar_sentences = $ar_sentences_on;
	}else{
		$ar_sentences = $ar_sentences_off;
	}
	
	$j = count($ar_sentences);
	$i = rand(1, $j);
	
	return $ar_sentences[$i-1];
	
}

?>