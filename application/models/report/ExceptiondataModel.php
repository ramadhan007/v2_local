<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ExceptiondataModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function count_all($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = ''){
		
		$sql = "
			SELECT	count(a.id)
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id`
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
					INNER JOIN tb_error AS h ON h.`monitor_journey_detail_id` = a.`id`
			";
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE a.status and (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime between '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$sql .= $cond;
		
		return get_val($sql);
	}
	
	function get_paged_list($limit=10, $offset=0, $monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = ''){
		$sql = "
			SELECT	a.id, f.name AS location_name, g.name AS operator_name,
					REPLACE(REPLACE(CONCAT(d.name,'_',f.name,'_',g.name),' ','_'),'/','_') AS application_name,
					c.name AS page_name,
					b.`monitor_date`, DATE_FORMAT(a.monitor_datetime,'%H:%i:%S') AS time_start,
					DATE_FORMAT(h.`recover_datetime`, '%H:%i:%S') AS time_end, TIMEDIFF(h.`recover_datetime`, a.monitor_datetime) AS error_duration,
					IF(a.scheduled,'Scheduled','Unscheduled') AS error_type,
					a.message
			FROM 	`tb_monitor_journey_detail` AS a 
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
					INNER JOIN tb_error AS h ON h.`monitor_journey_detail_id` = a.`id`
			";
		
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE a.status AND (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime between '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$sql .= $cond;
		
		$sql .= " ORDER BY b.monitor_datetime desc, a.monitor_datetime DESC
			".($limit=='all' ? '' : "LIMIT $offset, $limit");
			
		// echo $sql;
		
		return $this->db->query($sql);
	}
	
	function get_paged_count($limit=10, $offset=0, $monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = ''){
		$sql = "
			SELECT	a.id
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id`
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
					INNER JOIN tb_error AS h ON h.`monitor_journey_detail_id` = a.`id`
			";
		
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE a.status AND (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime between '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$sql .= $cond;
		
		$sql .= ($limit=='all' ? '' : "LIMIT $offset, $limit");
		
		return $this->db->query($sql);
	}
}

?>