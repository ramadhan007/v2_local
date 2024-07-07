<?php

require_once("dbconnecti.php");
require_once("simplecrypt.php");
require_once("senocrypt.php");

//get class
$db = new DBConnecti();
$simplecrypt = new SimpleCrypt();
$senocrypt = new SenoCrypt();

$login = $simplecrypt->Decrypt($_POST['login']);

$result = $db->db_query("select scode, scode_request from tb_user where login = '$login' limit 0,1");
if($result['num_rows']>0){
	$rows = $result['rows'];
	$row = $rows[0];
	if($row["scode_request"]<3){
		$scode = $row["scode"];
		$code = $senocrypt->Decrypt($scode);
		if($code){
			$pesan = "Kode Aktivasi Robbi Rodliyya Anda adalah ".$code;
			if(send_sms($login, $pesan)){
				$db->db_query("update tb_user set scode_request = scode_request+1 where login = '".$login."'");
				$ar_return = array('status' => 1, 'scode' => $scode, 'message' => '');
			}else{
				$ar_return = array('status' => 0, 'scode' => '', 'message' => 'Maaf SMS gagal dikirim ke nomor Anda');
			}
		}
		else{
			$code = rand(1000, 9999);
			$scode = $senocrypt->Decrypt($code);
			$pesan = "Kode Aktivasi Robbi Rodliyya Anda adalah ".$code;
			if(send_sms($login, $pesan)){
				$db->db_query("update tb_user set scode_request = scode_request+1 where login = '".$login."'");
				$ar_return = array('status' => 1, 'scode' => $scode, 'message' => '');
			}else{
				$ar_return = array('status' => 0, 'scode' => '', 'message' => 'Maaf SMS gagal dikirim ke nomor Anda');
			}
		}
	}
	else{
		$ar_return = array('status' => 0, 'scode' => '', 'message' => 'Maksimal pengiriman ulang kode adalah tiga kali');
	}
}
else{
	$ar_return = array('status' => -1, 'scode' => '', 'message' => 'Maaf akun Anda tidak ditemukan, silahkan daftar kembali');
}
echo json_encode($ar_return);

function send_sms($login, $pesan){
	$url = "https://reguler.zenziva.net/apps/smsapi.php?userkey=4ngfid&passkey=seno4r15gsssas&nohp=$login&pesan=".urlencode($pesan);
	$xml = load($url);
	$obj = simplexml_load_string($xml);
	$ar = to_array($obj);
	if($ar['message']['text']=='Success'){
		return true;
	}
	else{
		return false;
	}
}

function load($url, $maxlength=0, $array_post=array())
{
	$ch = curl_init();
	$timeout = 0;
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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

function to_array($data)
{
	if (is_object($data)) $data = get_object_vars($data);
	return (is_array($data)) ? array_map(__FUNCTION__,$data) : $data;
}

?>