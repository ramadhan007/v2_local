<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CorrelationModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function get_paged_list($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id){
		$array = get_list_item("response_time");
		$rt_state = get_query_range($array, "a.response_time", "value");
		
		$array = get_list_item("response_time_nvt");
		$nvt_result = get_query_range($array, "@nvt_response_time", "value");
		
		$array = get_list_item("signal_level");
		$signal_level = get_query_range($array, "a.signal_level", "value");
		
		$sql = "
			SELECT	a.rt_state, a.nvt_result,
					a.signal_state, a.network_condition_val,
					IF(network_condition_val<24,'Considered Good',IF(network_condition_val<54,'Fair','Potentially Poor')) AS network_condition,
					IF(network_condition_val<54, 'Unlikely Cell-Networks Issue', 'Potentially degrade Performance') AS description,
					IFNULL(b.num,0) AS num, IF(a.network_condition_val>=54,IFNULL(b.num,0),0) AS num_poor
			FROM 	(SELECT	*, (rt_state_index*nvt_result_index*signal_state_index) AS network_condition_val
					FROM 	(SELECT 	a.val AS rt_state_index, a.`text` AS rt_state
							FROM 	tb_list_item AS a
									INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
							WHERE 	b.tag = 'response_time'
							ORDER BY a.val ASC) AS a
							INNER JOIN (SELECT 	a.val AS nvt_result_index, a.`text` AS nvt_result
							FROM 	tb_list_item AS a
									INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
							WHERE 	b.tag = 'response_time_nvt'
							ORDER BY a.val ASC) AS b
							INNER JOIN (SELECT 	a.val AS signal_state_index, a.`text` AS signal_state
							FROM 	tb_list_item AS a
									INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
							WHERE 	b.tag = 'signal_level'
							ORDER BY a.val ASC) AS c
					ORDER 	BY rt_state_index, nvt_result_index, signal_state_index) AS a
			LEFT JOIN (SELECT	rt_state_index, signal_state_index, nvt_result_index,
					COUNT(*) AS num
			FROM 
			(SELECT	a.id, a.`response_time`,
					($rt_state) AS rt_state_index,
					a.signal_level,
					($signal_level) AS signal_state_index,
					@nvt_response_time := ROUND(fn_get_monitor_journey_nvt_response_time(b.`id`),3) AS nvt_response_time,
					($nvt_result) AS nvt_result_index
			FROM 	`tb_monitor_journey_detail` AS a 
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
			{where}
			ORDER 	BY b.monitor_datetime DESC, a.monitor_datetime DESC) AS a
			GROUP 	BY rt_state_index, nvt_result_index, signal_state_index) AS b ON (a.rt_state_index = b.rt_state_index AND a.nvt_result_index = b.nvt_result_index AND a.signal_state_index = b.signal_state_index)
			ORDER 	BY a.rt_state_index, a.nvt_result_index, a.signal_state_index";

		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$cond .= ($journey_id? " AND d.id = '$journey_id'" : "");
		$sql  = str_replace('{where}', $cond, $sql);
		
		// echo $sql; exit();
		
		return $this->db->query($sql);
	}
	
	function get_paged_list_event($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id){
		$array = get_list_item("response_time");
		$rt_state = get_query_range($array, "a.response_time", "value");
		
		$array = get_list_item("response_time_nvt");
		$nvt_result = get_query_range($array, "@nvt_response_time", "value");
		
		$array = get_list_item("signal_level");
		$signal_level = get_query_range($array, "a.signal_level", "value");
		
		$sql = "
			SELECT	monitor_date AS label, COUNT(*) AS value
			FROM 
			(SELECT	a.id, b.monitor_date, a.`response_time`,
					(CASE WHEN a.response_time BETWEEN 0 AND 2.5 THEN '1' WHEN a.response_time BETWEEN 2.50001 AND 3.5 THEN '2' WHEN a.response_time BETWEEN 3.50001 AND 999999 THEN '3' END) AS rt_state_index,
					a.signal_level,
					(CASE WHEN a.signal_level BETWEEN -85 AND 999999 THEN '2' WHEN a.signal_level BETWEEN -99.99999 AND -85.00001 THEN '4' WHEN a.signal_level BETWEEN -999999 AND -100 THEN '6' END) AS signal_state_index,
					@nvt_response_time := ROUND(fn_get_monitor_journey_nvt_response_time(b.`id`),3) AS nvt_response_time,
					(CASE WHEN @nvt_response_time BETWEEN 0 AND 1.7 THEN '3' WHEN @nvt_response_time BETWEEN 1.70001 AND 2.0 THEN '6' WHEN @nvt_response_time BETWEEN 2.00001 AND 999999 THEN '9' END) AS nvt_result_index
			FROM 	`tb_monitor_journey_detail` AS a 
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
			{where}
			ORDER 	BY b.monitor_datetime DESC, a.monitor_datetime DESC) AS a
			WHERE	(rt_state_index*nvt_result_index*signal_state_index) >= 54
			GROUP 	BY monitor_date";

		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$cond .= ($journey_id? " AND d.id = '$journey_id'" : "");
		$sql  = str_replace('{where}', $cond, $sql);
		
		// echo $sql; exit();
		
		return $this->db->query($sql);
	}
	
	function get_paged_list_error($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id){
		$array = get_list_item("response_time");
		$rt_state = get_query_range($array, "a.response_time", "value");
		
		$array = get_list_item("response_time_nvt");
		$nvt_result = get_query_range($array, "@nvt_response_time", "value");
		
		$array = get_list_item("signal_level");
		$signal_level = get_query_range($array, "a.signal_level", "value");
		
		$sql = "
			SELECT	error AS label, COUNT(*) AS value
			FROM 
			(SELECT	a.id, IF(a.status,a.message,'Success') AS error, a.`response_time`,
					(CASE WHEN a.response_time BETWEEN 0 AND 2.5 THEN '1' WHEN a.response_time BETWEEN 2.50001 AND 3.5 THEN '2' WHEN a.response_time BETWEEN 3.50001 AND 999999 THEN '3' END) AS rt_state_index,
					a.signal_level,
					(CASE WHEN a.signal_level BETWEEN -85 AND 999999 THEN '2' WHEN a.signal_level BETWEEN -99.99999 AND -85.00001 THEN '4' WHEN a.signal_level BETWEEN -999999 AND -100 THEN '6' END) AS signal_state_index,
					@nvt_response_time := ROUND(fn_get_monitor_journey_nvt_response_time(b.`id`),3) AS nvt_response_time,
					(CASE WHEN @nvt_response_time BETWEEN 0 AND 1.7 THEN '3' WHEN @nvt_response_time BETWEEN 1.70001 AND 2.0 THEN '6' WHEN @nvt_response_time BETWEEN 2.00001 AND 999999 THEN '9' END) AS nvt_result_index
			FROM 	`tb_monitor_journey_detail` AS a 
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
			{where}
			ORDER 	BY b.monitor_datetime DESC, a.monitor_datetime DESC) AS a
			WHERE	(rt_state_index*nvt_result_index*signal_state_index) >= 54
			GROUP 	BY error
			ORDER 	BY COUNT(*) desc";

		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$cond .= ($journey_id? " AND d.id = '$journey_id'" : "");
		$sql  = str_replace('{where}', $cond, $sql);
		
		// echo $sql; exit();
		
		return $this->db->query($sql);
	}
	
}

?>