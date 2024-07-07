<?php

require_once("dbconfig.php");
require_once("dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] <> "POST") 
{
	echo "Calling methode is illegal";
	exit;
}

$email = $_REQUEST['email'];

//check for space (to prevent SQL injection)
if(strpos(trim($email),' '))
{
	echo "Hehehe, I know what you're trying to do man. SQL Injection? Not a chance.";
	exit;
}

$strsql = "SELECT id FROM tb_user WHERE email = '$email';";
$hasil = getrs($strsql);
if($hasil && mysql_num_rows($hasil))
{
	echo "true";
}
else
{
	echo "false";
}
?>