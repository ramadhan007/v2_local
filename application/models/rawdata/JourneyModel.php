<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JourneyModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function count_all($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id = '', $journey_detail_id = '', $status = ''){		
		$sql = "
			SELECT	b.id
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.monitor_date = b.monitor_date AND a.`monitor_journey_id` = b.`id`)
						";
		
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		// $cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10))";		
		
		$cond .= ($location_id ? " AND fn_get_location_id_by_device_id(b.`device_id`) = '$location_id'" : "");
		$cond .= ($operator_id ? " AND fn_get_operator_id_by_device_id(b.`device_id`) = '$operator_id'" : "");
		$cond .= ($journey_id ? " AND b.journey_id = '$journey_id'" : "");
		$cond .= ($status !== '' ? " AND a.status = '$status'" : "");
		$sql .= $cond;
		$sql .= " GROUP BY b.`id`";
		
		$sql = "select count(*) from ($sql) as a";
		
		return get_val($sql);
	}
	
	function get_paged_list($limit=10, $offset=0, $monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id = '', $journey_detail_id = '', $status = ''){
		$sql = "
			SELECT	b.`id`, fn_get_location_name_by_device_id(b.`device_id`) AS location_name,
					fn_get_operator_name_by_device_id(b.`device_id`) AS operator_name,
					fn_get_journey_name(b.`journey_id`) AS journey_name,
					MAX(IF(a.network_type='3G', MOD(a.`cellid`,65536), a.`cellid`)) AS cellid, b.`location_lat`, b.`location_lng`,
					MAX(a.`network_type`) AS network_type,
					ROUND(AVG(IF(a.signal_level<99,a.signal_level,NULL)),0) AS signal_level, 
					ROUND(AVG(IF(a.signal_quality<99,a.signal_quality,NULL)),0) AS signal_quality,
					ROUND(AVG(IF(a.ber<99,a.ber,NULL)),0) AS ber,
					ROUND(SUM(a.`response_time`),3) AS response_time, ROUND(AVG(a.latency),0) as latency, ROUND(AVG(packet_loss),0) as packet_loss,
					b.`monitor_datetime`, MAX(a.status) AS `status`, MAX(a.message) AS message, MAX(a.id) AS screenshot_id, COUNT(a.id) AS detail_count, IF(MAX(a.status)>0,fn_get_journey_detail_name(fn_get_monitor_journey_last_detail(b.id)),'') AS error_journey_detail_name,
					ROUND(fn_get_monitor_journey_nvt_response_time(b.`id`),3) AS nvt_response_time,
					ROUND(fn_get_monitor_journey_nvt_signal_level(b.`id`),0) AS nvt_signal_level,
					round(fn_get_monitor_journey_nvt_latency(b.`id`),0) AS nvt_latency,
					fn_get_monitor_journey_nvt_count(b.`id`) AS nvt_count
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`)
			";
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		// $cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10))";
		
		$cond .= ($location_id ? " AND fn_get_location_id_by_device_id(b.`device_id`) = '$location_id'" : "");
		$cond .= ($operator_id ? " AND fn_get_operator_id_by_device_id(b.`device_id`) = '$operator_id'" : "");
		$cond .= ($journey_id ? " AND b.journey_id = '$journey_id'" : "");
		$cond .= ($status !== '' ? " AND a.status = '$status'" : "");
		$sql .= $cond;
		
		$sql .= " GROUP 	BY b.id, b.`location_lat`, b.`location_lng`";
		$sql .= " ORDER BY 1 DESC";
			// ".($limit=='all' ? '' : "LIMIT $offset, $limit");
			
		$sql .= ($limit=='all' ? '' : " LIMIT $offset, $limit");
			
		// echo "<!-- $sql -->";
		
		return $this->db->query($sql);
	}
	
	function get_paged_count($limit=10, $offset=0, $monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id = '', $journey_detail_id = '', $status = ''){
		$sql = "
			SELECT	b.id
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.monitor_date = b.monitor_date AND a.`monitor_journey_id` = b.`id`)
			";
		
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		// $cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10))";		
		
		$cond .= ($location_id ? " AND fn_get_location_id_by_device_id(b.`device_id`) = '$location_id'" : "");
		$cond .= ($operator_id ? " AND fn_get_operator_id_by_device_id(b.`device_id`) = '$operator_id'" : "");
		$cond .= ($journey_id ? " AND b.journey_id = '$journey_id'" : "");
		$cond .= ($status !== '' ? " AND a.status = '$status'" : "");
		$sql .= $cond;
		
		$sql .= " GROUP BY b.`id`";
		$sql .= ($limit=='all' ? '' : " LIMIT $offset, $limit");
		$sql = "select count(*) from ($sql) as a";
		
		return $this->db->query($sql);
	}
}

?>