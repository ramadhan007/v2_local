<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PerformanceanalysisLoModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function get_paged_list($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $type = ''){
		
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";
		
		if($type=='location'){
			$array_data = get_rows_array("select id, name from tb_operator where published order by id");
			
			$select = "DATE_FORMAT(monitor_datetime,'%d-%b') as label";
			foreach($array_data as $data){
				$select .= ", ROUND(AVG(IF(operator_id='".$data['id']."',response_time,0)),2) AS '".$data['name']."'";
			}
			
			$sql = "
				SELECT	$select
				FROM 	
				(SELECT	b.`id` AS monitor_journey_id, f.id AS location_id, f.`name` AS location_name,
						g.id AS operator_id, g.`name` AS operator_name, d.id AS journey_id, d.name AS journey_name,
						SUM(a.`response_time`) AS response_time, b.`monitor_datetime`
				FROM 	`tb_monitor_journey_detail` AS a 
						INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
						INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
						INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
						INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
						INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
				{where}
				GROUP 	BY b.id) AS a
				GROUP 	BY DATE_FORMAT(monitor_datetime,'%d-%b')
				ORDER 	BY DATE_FORMAT(monitor_datetime,'%d-%b')
				";
				
			$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
				
		}elseif($type=='operator'){
			$array_data = get_rows_array("select id, name from tb_location where published order by id");
			
			$select = "DATE_FORMAT(monitor_datetime,'%d-%b') as label";
			foreach($array_data as $data){
				$select .= ", ROUND(AVG(IF(location_id='".$data['id']."',response_time,0)),2) AS '".$data['name']."'";
			}
			
			$sql = "
				SELECT	$select
				FROM 	
				(SELECT	b.`id` AS monitor_journey_id, f.id AS location_id, f.`name` AS location_name,
						g.id AS operator_id, g.`name` AS operator_name, d.id AS journey_id, d.name AS journey_name,
						SUM(a.`response_time`) AS response_time, b.`monitor_datetime`
				FROM 	`tb_monitor_journey_detail` AS a 
						INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
						INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
						INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
						INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
						INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
				{where}
				GROUP 	BY b.id) AS a
				GROUP 	BY DATE_FORMAT(monitor_datetime,'%d-%b')
				ORDER 	BY DATE_FORMAT(monitor_datetime,'%d-%b')
				";
				
			$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		}
		
		$sql  = str_replace('{where}', $cond, $sql);
		
		return $this->db->query($sql);
	}
}

?>