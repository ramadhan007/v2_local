<?php

require_once("../config/dbconfig.php");

function dbconnect()
{
	$config = new DBConfig();
	$username=$config->user;
	$password=$config->pass;
	$database=$config->db;
	mysql_connect($config->host,$username,$password) or die( "");
	@mysql_select_db($database) or die( "Unable to select database");
}

function dbclose()
{
	mysql_close();
}

function getrs($strsql)
{
	dbconnect();
	$hasil = mysql_query($strsql);
	dbclose();
	return $hasil;
}
?>