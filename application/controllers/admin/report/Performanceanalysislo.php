<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PerformanceAnalysisLo extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'performance analysis location and operator', 'report/performanceanalysislo');
		
		//reset filter_monitor_date_start & end jika komputer ganti tanggal
		$current_date = $this->session->userdata('current_date');
		if($current_date){
			if($current_date!=date('d-m-Y')){	//tanggal baru, reset filter_monitor_date
				$this->session->unset_userdata('filter_monitor_date_start');
				$this->session->unset_userdata('filter_monitor_date_end');
				
				$this->session->set_userdata('current_date', date('d-m-Y'));
			}
		}else{	//session habis
			$this->session->set_userdata('current_date', date('d-m-Y'));
		}
	}
	
	function index()
	{
		if(!$this->continue) return;
		$this->_show();
	}
	
	function _show(){	
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb(site_url($this->controller.'/index'),ucwords($this->title),true);
		
		//filter_monitor_date_start
		$filter_monitor_date_start_old = $this->session->userdata('filter_monitor_date_start');
		if(isset($_POST['filter_monitor_date_start'])){
			$filter_monitor_date_start = $this->input->post('filter_monitor_date_start');
			if($filter_monitor_date_start!=$filter_monitor_date_start_old){
				$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
			}
		}
		else{
			$filter_monitor_date_start = $this->session->userdata('filter_monitor_date_start');
		}
		if(!$filter_monitor_date_start){
			$filter_monitor_date_start = date('d-m-Y 00:00');
			$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
		}
		
		//filter_monitor_date_end
		$filter_monitor_date_end_old = $this->session->userdata('filter_monitor_date_end');
		if(isset($_POST['filter_monitor_date_end'])){
			$filter_monitor_date_end = $this->input->post('filter_monitor_date_end');
			if($filter_monitor_date_end!=$filter_monitor_date_end_old){
				$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
			}
		}
		else{
			$filter_monitor_date_end = $this->session->userdata('filter_monitor_date_end');
		}
		if(!$filter_monitor_date_end){
			$filter_monitor_date_end = date('d-m-Y 23:59');
			$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata('filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata('filter_location_id', $filter_location_id);
			}
		}
		else{
			$filter_location_id = $this->session->userdata('filter_location_id');
		}
		
		//filter_operator_id
		$filter_operator_id_old = $this->session->userdata('filter_operator_id');
		if(isset($_POST['filter_operator_id'])){
			$filter_operator_id = $this->input->post('filter_operator_id');
			if($filter_operator_id!=$filter_operator_id_old){
				$this->session->set_userdata('filter_operator_id', $filter_operator_id);
			}
		}
		else{
			$filter_operator_id = $this->session->userdata('filter_operator_id');
		}
		
		// load data
		// $rows = $this->model->get_paged_list($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, 'location')->result();
		$rows = array();
		$data['rows_location'] = $rows;
		$data['array_location'] = $this->_rows2array($rows);
		$data['array_color'] = array();
		
		// $rows = $this->model->get_paged_list($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, 'operator')->result();
		$data['rows_operator'] = $rows;
		$data['array_operator'] = $this->_rows2array($rows);
		
		$this->view['toptitle'] = ucwords($this->title);
		
		$sql = "select id, name from tb_location where published order by name";
		$array_data = get_array($sql);
		$html['filter_location_id'] = html_select('filter_location_id', $array_data, set_value('filter_location_id', $filter_location_id), 'Location', 'form-control');
		$html['export_location_id'] = html_select_multiple('export_location_id', $array_data, '', '', 'form-control select2');
		
		$sql = "select id, name from tb_operator where published order by name";
		$array_data = get_array($sql);
		$html['filter_operator_id'] = html_select('filter_operator_id', $array_data, set_value('filter_operator_id', $filter_operator_id), 'Operator', 'form-control');
		$html['export_operator_id'] = html_select_multiple('export_operator_id', $array_data, '', '', 'form-control select2');
		
		$data['html'] = $html;
		
		$data['offset'] = 0;
		
		// load view
		$this->load->view('main', $data);
	}
	
	function _rows2array($rows){
		$array = array();
		
		if(count($rows)){
			$row = $rows[0];
			
			$array1 = array();
			foreach($row as $key=>$val){
				$array1[] = $key;
			}
			$array[] = $array1;
			
			foreach($rows as $row){
				$array1 = array();
				$i=0;
				foreach($row as $key=>$val){
					$array1[] = $i ? floatval($val) : $val;
					$i++;
				}
				$array[] = $array1;
			}
		}else{
			$array[] = array('label', 'value');
			$array[] = array('', 0);
		}
		return $array;
	}
	
	function listcontent($type=''){	
		//filter_monitor_date_start
		$filter_monitor_date_start_old = $this->session->userdata('filter_monitor_date_start');
		if(isset($_POST['filter_monitor_date_start'])){
			$filter_monitor_date_start = $this->input->post('filter_monitor_date_start');
			if($filter_monitor_date_start!=$filter_monitor_date_start_old){
				$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
			}
		}
		else{
			$filter_monitor_date_start = $this->session->userdata('filter_monitor_date_start');
		}
		if(!$filter_monitor_date_start){
			$filter_monitor_date_start = date('d-m-Y 00:00');
			$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
		}
		
		//filter_monitor_date_end
		$filter_monitor_date_end_old = $this->session->userdata('filter_monitor_date_end');
		if(isset($_POST['filter_monitor_date_end'])){
			$filter_monitor_date_end = $this->input->post('filter_monitor_date_end');
			if($filter_monitor_date_end!=$filter_monitor_date_end_old){
				$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
			}
		}
		else{
			$filter_monitor_date_end = $this->session->userdata('filter_monitor_date_end');
		}
		if(!$filter_monitor_date_end){
			$filter_monitor_date_end = date('d-m-Y 23:59');
			$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata('filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata('filter_location_id', $filter_location_id);
			}
		}
		else{
			$filter_location_id = $this->session->userdata('filter_location_id');
		}
		
		//filter_operator_id
		$filter_operator_id_old = $this->session->userdata('filter_operator_id');
		if(isset($_POST['filter_operator_id'])){
			$filter_operator_id = $this->input->post('filter_operator_id');
			if($filter_operator_id!=$filter_operator_id_old){
				$this->session->set_userdata('filter_operator_id', $filter_operator_id);
			}
		}
		else{
			$filter_operator_id = $this->session->userdata('filter_operator_id');
		}
		
		// load data
		$rows = $this->model->get_paged_list($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $type)->result();
		$data['rows_'.$type] = $rows;
		$data['array_'.$type] = $this->_rows2array($rows);
		if($type=='location'){
			$data['array_color'] = $this->_get_color_operator($rows);
		}
		$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/tbl_legend_'.$type, $data, true);
		// echo str_replace("'","\'",$tbody);
		echo $tbody;
	}
	
	function _get_color_operator($rows){
		$main_chart_name = get_val("select value from tb_setting where name = 'main_chart_name'");
		$main_chart_color = get_val("select value from tb_setting where name = 'main_chart_color'");
		$ar_ref_color = get_array_color();
		$ar_color = array();
		$i = 0;
		$j = 0;
		foreach($rows[0] as $key=>$val){
			if($j>0){
				if($key==$main_chart_name){
					$ar_color[] = $main_chart_color;
				}else{
					while($ar_ref_color[$i] == $main_chart_color){
						$i++;
					}
					$ar_color[] = $ar_ref_color[$i];
					$i++;
				}
			}
			$j++;
		}
		return $ar_color;
	}
	
	function getjourneydetaillist($filter_journey_id){
		$array_data = get_array("select id, name from tb_journey_detail where journey_id = '$filter_journey_id' AND published");
		echo json_encode($array_data);
	}
}

?>