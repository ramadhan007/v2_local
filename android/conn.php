<?php

require_once("dbconnecti.php");

//get class
$db = new DBConnecti();

//get & decrypt posted params
$sql = "SHOW GLOBAL VARIABLES LIKE '%max_connections%'";
$result = $db->db_query($sql);
echo $result['rows'][0]["Variable_name"]." : ".$result['rows'][0]["Value"];

echo "<br>";

$sql = "SHOW STATUS LIKE '%connected%'";
$result = $db->db_query($sql);
echo $result['rows'][0]["Variable_name"]." : ".$result['rows'][0]["Value"];

?>