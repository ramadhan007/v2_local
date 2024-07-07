<?php

$token = $_GET['token'];

// $token='vuMy+U)z@Bscqa$.^N';

if($token=='IPib708mKGGeXesYoT'){

	require_once("dbconnecti.php");
	
	//get class
	$db = new DBConnecti();
	
	$sql = "SELECT	a.`id`, b.`device_id` AS device_id, f.`name` AS location_name, g.`name` AS operator_name,
			d.`name` AS journey_name, c.`name` AS journey_detail_name, b.`location_lat`, b.`location_lng`, a.`response_time`,
			b.`monitor_datetime`, IF(a.`status`,'error','success') AS `status`, 
			(CASE a.status WHEN 3 THEN 'Wrong PIN' WHEN 2 THEN 'Wrong Response Page' ELSE a.message END) AS error_type
		FROM 	`tb_monitor_journey_detail` AS a 
			INNER JOIN `tb_monitor_journey` AS b ON a.`monitor_journey_id` = b.`id` 
			INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
			INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
			INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
			INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
			INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
			INNER JOIN `tb_monitor_journey_detail_new` AS h ON a.`id` = h.`id`
		LIMIT	0,10";
		
	$result = $db->db_query($sql);
	$rows = $result['rows'];
	
	$list_id = "";
	foreach($rows as $row){
		// print_r($row);
		$list_id = $list_id.($list_id ? "," : "").$row['id'];
	}
	
	// echo "delete from tb_monitor_journey_detail_new where id in ($list_id)";
	$db->db_query("delete from tb_monitor_journey_detail_new where id in ($list_id)");
	
	echo json_encode($rows);
	
	// print_r($rows);
	// echo json_encode($db->db_query($sql));
}else{
	echo json_encode(array("Invalid Access"));
}

?>