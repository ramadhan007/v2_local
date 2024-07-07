<?php

require_once("dbconnecti.php");

if ($_SERVER["REQUEST_METHOD"] <> "POST") 
{
	echo "Calling methode is illegal";
	exit;
}

$id = $_REQUEST['id'];

//check for space (to prevent SQL injection)
if(strpos(trim($email),' '))
{
	echo "Hehehe, I know what you're trying to do man. SQL Injection? Not a chance.";
	exit;
}

$strsql = "SELECT count(*)	FROM tb_article_comment WHERE article_id = '$id';";
return get_val($strsql);
?>