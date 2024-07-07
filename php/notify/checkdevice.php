<?php

require_once("dbconnecti.php");

$db = new DBConnecti();

$db->db_query("call sp_device_check();");

?>