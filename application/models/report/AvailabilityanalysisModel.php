<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AvailabilityanalysisModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function get_paged_list_page($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $scheduled = ''){
		$sql = "
			SELECT	c.name AS journey_detail_name, round(sum(time_to_sec(TIMEDIFF(a.`recover_datetime`, a.error_datetime)))/60) AS total
			FROM 	`tb_error_event` AS a
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id`
					INNER JOIN `tb_device` AS e ON a.`device_id` = e.`id`
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
			{where}
			GROUP 	BY c.`name`
			ORDER 	BY COUNT(*) DESC
			LIMIT 	0,5
			";
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (a.error_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10))";
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		if($scheduled!='') $cond .= " AND a.scheduled = '$scheduled'";
		
		$sql  = str_replace('{where}', $cond, $sql);
		
		// echo $sql; exit();
		
		return $this->db->query($sql);
	}
	
	function get_paged_list_error($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $scheduled = ''){
		$sql = "
			SELECT	(CASE a.type WHEN 3 THEN 'Wrong PIN' WHEN 2 THEN 'Wrong Response Page' ELSE a.description END) AS message, round(sum(time_to_sec(TIMEDIFF(a.`recover_datetime`, a.error_datetime)))/60) AS total
			FROM 	`tb_error_event` AS a
					INNER JOIN `tb_device` AS e ON a.`device_id` = e.`id`
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
			{where}
			GROUP 	BY (CASE a.type WHEN 3 THEN 'Wrong PIN' WHEN 2 THEN 'Wrong Response Page' ELSE a.description END)
			ORDER 	BY COUNT(*) DESC
			LIMIT 	0,5
			";
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (a.error_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10))";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		if($scheduled!='') $cond .= " AND a.scheduled = '$scheduled'";
		
		$sql  = str_replace('{where}', $cond, $sql);
		
		// echo $sql; exit();
		
		return $this->db->query($sql);
	}
}

?>