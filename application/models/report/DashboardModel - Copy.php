<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DashboardModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function get_paged_list($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = ''){
		$sql = "
			SELECT	journey_id, journey_name,
					(SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(response_time ORDER BY response_time SEPARATOR '|'),'|',COUNT(*)/2),'|',-1)+0) AS response_time_median,
					ROUND(AVG(IF(STATUS=0,response_time,NULL)),2) AS response_time_avg,
					round((sum(num_page_in_ux_index)/sum(num_page_success))*100,2) AS ux_index,
					ROUND((SUM(IF(STATUS=0 AND NOT scheduled,response_time,0))/SUM(IF(NOT scheduled,response_time,0)))*100,2) AS it_availability,
					ROUND((SUM(IF(STATUS=0,response_time,0))/SUM(response_time))*100,2) AS eu_availability,
					ROUND(MIN(IF(STATUS=0,response_time,NULL)) + (MAX(IF(STATUS=0,response_time,NULL)) - MIN(IF(STATUS=0,response_time,NULL)))*0.9,2) AS nineth_percentile,
					SUM(1) AS total_journey,
					SUM(IF(STATUS=0,1,0)) AS total_success,
					SUM(IF(STATUS=0,0,1)) AS total_error,
					fc_count_journey_detail(journey_id) AS num_page,
					ROUND((SUM(IF(nvt_signal_level>-85,1,0))/SUM(1))*100,2) AS nvt_signal_level,
					ROUND((SUM(IF(nvt_response_time<2,1,0))/SUM(1))*100,2) AS nvt_response_time,
					ROUND((SUM(nvt_success)/SUM(nvt_count))*100,2) AS nvt_access
			FROM 	
			(SELECT	b.`id` AS monitor_journey_id, d.id AS journey_id, d.name AS journey_name,
					MAX(a.`network_type`) AS network_type, MAX(a.`signal_level`) AS signal_level, 
					ROUND(SUM(a.`response_time`),3) AS response_time, b.`monitor_datetime`,
					MAX(a.status) AS `status`, MAX(a.message) AS message,
					SUM(1) AS num_page,
					SUM(IF(a.status=0,1,0)) AS num_page_success,
					SUM(IF(NOT a.status,IF(a.response_time<=5,1,0),0)) AS num_page_in_ux_index,
					SUM(IF(NOT a.status,IF(a.response_time>5,1,0),0)) AS num_page_out_ux_index,
					fn_get_monitor_journey_nvt_signal_level(monitor_journey_id) AS nvt_signal_level,
					fn_get_monitor_journey_nvt_response_time(monitor_journey_id) AS nvt_response_time,
					fn_get_monitor_journey_nvt_success(monitor_journey_id) AS nvt_success,
					fn_get_monitor_journey_nvt_count(monitor_journey_id) AS nvt_count,
					MAX(a.scheduled) as scheduled
			FROM 	`tb_monitor_journey_detail` AS a 
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.monitor_date = b.monitor_date AND a.`monitor_journey_id` = b.`id`) 
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
			{where}
			GROUP 	BY b.id, d.id, d.name) AS a
			GROUP 	BY journey_id, journey_name
			";
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$sql  = str_replace('{where}', $cond, $sql);
		
		return $this->db->query($sql);
	}
	
	function get_paged_list_detail($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id = ''){
		$sql = "
			SELECT	journey_id, journey_name, journey_detail_id, journey_detail_name,
					(SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(response_time ORDER BY response_time SEPARATOR '|'),'|',COUNT(*)/2),'|',-1)+0) AS response_time_median,
					ROUND(AVG(IF(STATUS=0,response_time,NULL)),2) AS response_time_avg,
					round((sum(num_page_in_ux_index)/sum(num_page_success))*100,2) AS ux_index,
					ROUND((SUM(IF(STATUS=0 AND NOT scheduled,response_time,0))/SUM(IF(NOT scheduled,response_time,0)))*100,2) AS it_availability,
					ROUND((SUM(IF(STATUS=0,response_time,0))/SUM(response_time))*100,2) AS eu_availability,
					ROUND(MIN(IF(STATUS=0,response_time,NULL)) + (MAX(IF(STATUS=0,response_time,NULL)) - MIN(IF(STATUS=0,response_time,NULL)))*0.9,2) AS nineth_percentile,
					SUM(1) AS total_execute, 
					SUM(IF(STATUS=0,1,0)) AS total_success, 
					SUM(IF(STATUS=0,0,1)) AS total_error
			FROM 	(SELECT	b.`id` AS monitor_journey_id, d.id AS journey_id, d.name AS journey_name,
					a.`id` AS monitor_journey_detail_id, c.id AS journey_detail_id, c.name AS journey_detail_name,
					MAX(a.`network_type`) AS network_type, 
					MAX(a.`signal_level`) AS signal_level, ROUND(SUM(a.`response_time`),3) AS response_time, a.`monitor_datetime`, 
					MAX(a.status) AS `status`, MAX(a.message) AS message,
					SUM(1) AS num_page, 
					SUM(IF(a.status=0,1,0)) AS num_page_success,
					SUM(IF(NOT a.status,IF(a.response_time<=5,1,0),0)) AS num_page_in_ux_index, 
					SUM(IF(NOT a.status,IF(a.response_time>5,1,0),0)) AS num_page_out_ux_index,
					MAX(a.scheduled) as  scheduled
			FROM 	`tb_monitor_journey_detail` AS a 
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.monitor_date = b.monitor_date AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id`
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
			{where}
			GROUP 	BY b.id, d.id, d.name, a.id, c.`id`, c.`name`) AS a 
			GROUP 	BY journey_id, journey_name, journey_detail_id, journey_detail_name
			ORDER 	BY journey_id, journey_detail_id
			";
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$cond .= ($journey_id ? " AND d.id = '$journey_id'" : "");
		$sql  = str_replace('{where}', $cond, $sql);
		
		return $this->db->query($sql);
	}
}

?>