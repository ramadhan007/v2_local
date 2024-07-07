<?php

require_once("simplecrypt.php");
require_once("dbconnecti.php");

//get class
$crypt = new SimpleCrypt();
$db = new DBConnecti();

//get & decrypt posted params
$sql = "select 'Connection Ok'";

echo json_encode($db->db_query($sql));

?>