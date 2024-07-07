<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JourneyDetailModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function count_all($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id = '', $journey_detail_id = '', $status = ''){
		
		$sql = "
			SELECT	count(a.id)
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
		$cond .= ($journey_detail_id ? " AND a.journey_detail_id = '$journey_detail_id'" : "");
		$cond .= ($status !== '' ? " AND a.status = '$status'" : "");
		$sql .= $cond;
		
		return get_val($sql);
	}
	
	function get_paged_list($limit=10, $offset=0, $monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id = '', $journey_detail_id = '', $status = ''){
		$sql = "
			SELECT	a.id, fn_get_location_name_by_device_id(b.`device_id`) AS location_name,
				fn_get_operator_name_by_device_id(b.`device_id`) AS operator_name,
				fn_get_journey_name(b.`journey_id`) AS journey_name, `fn_get_journey_detail_name`(a.journey_detail_id) AS journey_detail_name,
				if(a.network_type='3G', mod(a.`cellid`,65536), a.`cellid`) as cellid, b.`location_lat`, b.`location_lng`, a.`network_type`, a.`signal_level`, a.signal_quality, a.ber,
				ROUND(a.`response_time`,3) as response_time,
				ROUND(a.latency, 0) as latency, a.packet_loss, 
				a.monitor_datetime, a.status, a.message,
				round(fn_get_monitor_journey_nvt_response_time(b.`id`),3) AS nvt_response_time,
				round(fn_get_monitor_journey_nvt_signal_level(b.`id`),0) AS nvt_signal_level,
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
		$cond .= ($journey_detail_id ? " AND a.journey_detail_id = '$journey_detail_id'" : "");
		$cond .= ($status !== '' ? " AND a.status = '$status'" : "");
		$sql .= $cond;
		
		$sql .= " ORDER BY 1 DESC";
			// ".($limit=='all' ? '' : "LIMIT $offset, $limit");
			
		$sql .= ($limit=='all' ? '' : " LIMIT $offset, $limit");
			
		// echo "<!-- $sql -->";
		
		return $this->db->query($sql);
	}
	
	function get_paged_count($limit=10, $offset=0, $monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_id = '', $journey_detail_id = '', $status = ''){
		$sql = "
			SELECT	a.id
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
		$cond .= ($journey_detail_id ? " AND a.journey_detail_id = '$journey_detail_id'" : "");
		$cond .= ($status !== '' ? " AND a.status = '$status'" : "");
		$sql .= $cond;
		
		$sql .= ($limit=='all' ? '' : " LIMIT $offset, $limit");
		
		return $this->db->query($sql);
	}
}

?>