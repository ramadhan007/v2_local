<?php

require_once("dbconnecti.php");

$db = new DBConnecti();

$db->db_query("UPDATE 	`tb_location_device_log`
	SET 	`status_end` = DATE_ADD(DATE_ADD(CURDATE(), INTERVAL -1 DAY), INTERVAL 24*60*60 - 1 SECOND)
	WHERE	`log_date` = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
			AND ISNULL(`status_end`)");

$db->db_query("INSERT INTO `tb_location_device_log` (`location_device_id`, log_date, `status`, `status_start`)
	SELECT id, CURDATE(), status_final, NOW() FROM `tb_location_device` WHERE published");

?>