<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PerformanceanalysisModel extends CI_Model {

	function __construct(){
		parent::__construct();
	}
	
	function get_paged_list($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_detail_id, $type = ''){
		
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		if($type=='daily'){
			$sql = "
				SELECT	date_format(b.`monitor_date`,'%d-%b') as label, ROUND(AVG(a.`response_time`),2) AS value
				FROM 	`tb_monitor_journey_detail` AS a 
						INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
						INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
						INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
						INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
						INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
						INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
				{where}
				GROUP 	BY b.`monitor_date`
				ORDER 	BY b.`monitor_date`
				";
		}elseif($type=='frequency'){
			$array_data = get_list_item("performance_analysis_response_time");
			
			$ordering = "";
			$label = "";
			$i = 0;
			foreach($array_data as $data){
				$ordering = ($ordering
					? str_replace('{ordering}',"IF(a.`response_time`>=".$data['value_min']." AND a.`response_time`<=".$data['value_max'].",".$i.",{ordering})",$ordering)
					: "IF(a.`response_time`>=".$data['value_min']." AND a.`response_time`<=".$data['value_max'].",".$i.",{ordering})");
				$label = ($label
					? str_replace('{label}',"IF(a.`response_time`>=".$data['value_min']." AND a.`response_time`<=".$data['value_max'].",'".$data['text']."',{label})",$label)
					: "IF(a.`response_time`>=".$data['value_min']." AND a.`response_time`<=".$data['value_max'].",'".$data['text']."',{label})");
				$i++;
			}
			$ordering = str_replace('{ordering}',$i,$ordering);
			$label = str_replace('{label}',"'Outliers'",$label);
			
			$sql = "
				SELECT	$ordering as ordering, $label as label, count(*) as value
				FROM 	`tb_monitor_journey_detail` AS a 
						INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
						INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
						INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
						INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
						INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
						INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
				{where}
				GROUP	BY $ordering, $label
				ORDER	BY $ordering
				";
		}elseif($type=='hourly'){
			$sql = "
				SELECT	DATE_FORMAT(b.`monitor_datetime`,'%H')+1 as label, ROUND(AVG(a.`response_time`),2) AS value
				FROM 	`tb_monitor_journey_detail` AS a 
						INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`) 
						INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id` 
						INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
						INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
						INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
						INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
				{where}
				GROUP 	BY DATE_FORMAT(b.`monitor_datetime`,'%H')+1
				ORDER 	BY DATE_FORMAT(b.`monitor_datetime`,'%H')+1
				";
		}
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$cond .= ($journey_detail_id ? " AND c.id = '$journey_detail_id'" : "");
		
		$sql  = str_replace('{where}', $cond, $sql);
		
		// echo $sql; exit();
		
		return $this->db->query($sql);
	}
}

?>