<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class KpitrendModel extends CI_Model {

	function __construct(){
		parent::__construct();
		
		$rows = get_rows("SELECT	`name`, `value`
								FROM 	tb_setting
								WHERE	`name` IN ('min_rt_ux_index','max_rt_ux_index','min_rt_ux_index_power','max_rt_ux_index_power')");
								
		foreach($rows as $row){
			if($row->name=='min_rt_ux_index'){
				$this->min_rt_ux_index = $row->value;
			}elseif($row->name=='max_rt_ux_index'){
				$this->max_rt_ux_index = $row->value;
			}elseif($row->name=='min_rt_ux_index_power'){
				$this->min_rt_ux_index_power = $row->value;
			}elseif($row->name=='max_rt_ux_index_power'){
				$this->max_rt_ux_index_power = $row->value;
			}
		}
		
	}
	
	function get_paged_list($monitor_date_start='', $monitor_date_end = '', $location_id='', $operator_id = '', $journey_detail_id = ''){
		$sql = "
			SELECT	DATE_FORMAT(monitor_datetime, '%d %b') AS label,
					ROUND(AVG(IF(STATUS=0,response_time,NULL)),2) AS response_time_avg,
					ROUND(SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(IF(STATUS=0,response_time,NULL) ORDER BY IF(STATUS=0,response_time,NULL) SEPARATOR '|'),'|',SUM(IF(STATUS=0,1,0))*0.9),'|',-1)+0,2) AS nineth_percentile,
					ROUND(IF(
		MOD(SUM(IF(STATUS=0,1,0)),2)=1,
		SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(IF(STATUS=0,response_time,NULL) ORDER BY IF(STATUS=0,response_time,NULL) SEPARATOR '|'),'|',CEIL(SUM(IF(STATUS=0,1,0))/2)),'|',-1)+0,
		((SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(IF(STATUS=0,response_time,NULL) ORDER BY IF(STATUS=0,response_time,NULL) SEPARATOR '|'),'|',SUM(IF(STATUS=0,1,0))/2),'|',-1)+0)
		+
		(SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(IF(STATUS=0,response_time,NULL) ORDER BY IF(STATUS=0,response_time,NULL) SEPARATOR '|'),'|',SUM(IF(STATUS=0,1,0))/2+1),'|',-1)+0))/2
	),2) AS response_time_mean,
					round((( SUM(IF(NOT a.status,IF(a.response_time<".$this->min_rt_ux_index.",1,0),0))*".$this->min_rt_ux_index_power." + SUM(IF(NOT a.status,IF(a.response_time>=".$this->min_rt_ux_index." AND a.response_time<=".$this->max_rt_ux_index.",1,0),0))*".$this->max_rt_ux_index_power." )/SUM(IF(a.status=0,1,0)))*100,2) as ux_index,
					ROUND((SUM(IF(STATUS=0,1,0))/SUM(1))*100,2) AS it_availability, 
					ROUND((SUM(IF(NOT scheduled,IF(STATUS=0,1,0),0))/SUM(IF(NOT scheduled,1,0)))*100,2) AS eu_availability
			FROM 	(SELECT	b.`id` AS monitor_journey_id, d.id AS journey_id, d.name AS journey_name,
					a.`id` AS monitor_journey_detail_id, c.id AS journey_detail_id, c.name AS journey_detail_name,
					MAX(a.`network_type`) AS network_type, 
					MAX(a.`signal_level`) AS signal_level, ROUND(SUM(a.`response_time`),3) AS response_time, a.`monitor_datetime`, 
					MAX(a.status) AS `status`, MAX(a.message) AS message, SUM(1) AS num_page, 
					SUM(IF(a.response_time<=5,1,0)) AS num_page_in_ux_index, 
					SUM(IF(a.response_time>5,1,0)) AS num_page_out_ux_index,
					MAX(a.scheduled) as scheduled
			FROM 	`tb_monitor_journey_detail` AS a 
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id`
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id` 
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id` 
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id` 
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id` 
			{where}
			GROUP 	BY b.id, d.id, d.name, a.id, c.`id`, c.`name`) AS a 
			GROUP 	BY DATE_FORMAT(monitor_datetime, '%Y-%m-%d')
			ORDER 	BY DATE_FORMAT(monitor_datetime, '%Y-%m-%d')
			";
		$monitor_date_start = date_dmyhns2mysql($monitor_date_start.":00", 2);
		$monitor_date_end = date_dmyhns2mysql($monitor_date_end.":00", 2);
		
		$cond = "WHERE (b.monitor_date BETWEEN SUBSTRING('$monitor_date_start',1,10) AND SUBSTRING('$monitor_date_end',1,10)) and (a.monitor_datetime BETWEEN '$monitor_date_start' AND '$monitor_date_end')";		
		$cond .= ($location_id ? " AND f.id = '$location_id'" : "");
		$cond .= ($operator_id ? " AND g.id = '$operator_id'" : "");
		$cond .= ($journey_detail_id ? " AND c.id = '$journey_detail_id'" : "");
		$sql  = str_replace('{where}', $cond, $sql);
		
		return $this->db->query($sql);
	}
}

?>