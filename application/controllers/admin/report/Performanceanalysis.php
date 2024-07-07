<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PerformanceAnalysis extends Admin_Controller {
	
	function __construct(){
		parent::__construct(__FILE__, 'performance analysis', 'report/performanceanalysis');
		
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
		
		//filter_journey_id
		$filter_journey_id_old = $this->session->userdata($this->controller.'_'.'filter_journey_id');
		if(isset($_POST['filter_journey_id'])){
			$filter_journey_id = $this->input->post('filter_journey_id');
			if($filter_journey_id!=$filter_journey_id_old){
				$this->session->set_userdata($this->controller.'_'.'filter_journey_id', $filter_journey_id);
			}
		}
		else{
			$filter_journey_id = $this->session->userdata($this->controller.'_'.'filter_journey_id');
		}
		$filter_journey_id = $filter_journey_id ? $filter_journey_id : get_val("select min(id) from tb_journey where published");
		$this->session->set_userdata($this->controller.'_'.'filter_journey_id', $filter_journey_id);
		
		//filter_journey_detail_id
		$filter_journey_detail_id_old = $this->session->userdata($this->controller.'_'.'filter_journey_detail_id');
		if(isset($_POST['filter_journey_detail_id'])){
			$filter_journey_detail_id = $this->input->post('filter_journey_detail_id');
			if($filter_journey_detail_id!=$filter_journey_detail_id_old){
				$this->session->set_userdata($this->controller.'_'.'filter_journey_detail_id', $filter_journey_detail_id);
			}
		}
		else{
			$filter_journey_detail_id = $this->session->userdata($this->controller.'_'.'filter_journey_detail_id');
		}
		$filter_journey_detail_id = $filter_journey_detail_id ? $filter_journey_detail_id : get_val("select min(id) from tb_journey_detail where journey_id = '$filter_journey_id' and published");
		$this->session->set_userdata($this->controller.'_'.'filter_journey_detail_id', $filter_journey_detail_id);
		
		if(!get_val("select id from tb_journey_detail where journey_id = '$filter_journey_id' and id = '$filter_journey_detail_id'")){
			$filter_journey_detail_id = get_val("select min(id) from tb_journey_detail where journey_id = '$filter_journey_id' and published");
			$this->session->set_userdata($this->controller.'_'.'filter_journey_detail_id', $filter_journey_detail_id);
		}
		
		// load data
		// $rows = $this->model->get_paged_list($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_detail_id, 'daily')->result();
		
		$rows = (object)array();
		$data['array_daily'] = $this->_rows2array($rows);
		
		// $rows = $this->model->get_paged_list($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_detail_id, 'frequency')->result();
		$data['array_frequency'] = $this->_rows2array($rows, true);
		
		// $rows = $this->model->get_paged_list($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_detail_id, 'hourly')->result();
		$data['array_hourly'] = $this->_rows2array($rows);
		
		$this->view['toptitle'] = ucwords($this->title);
		
		$sql = "select id, name from tb_location where published order by name";
		$array_data = get_array($sql);
		$html['filter_location_id'] = html_select('filter_location_id', $array_data, set_value('filter_location_id', $filter_location_id), 'Location', 'form-control');
		$html['export_location_id'] = html_select_multiple('export_location_id', $array_data, '', '', 'form-control select2');
		
		$sql = "select id, name from tb_operator where published order by name";
		$array_data = get_array($sql);
		$html['filter_operator_id'] = html_select('filter_operator_id', $array_data, set_value('filter_operator_id', $filter_operator_id), 'Operator', 'form-control');
		$html['export_operator_id'] = html_select_multiple('export_operator_id', $array_data, '', '', 'form-control select2');
		
		$array_data = get_array("select id, name from tb_journey where published");
		
		$html['filter_journey_id'] = html_select('filter_journey_id', $array_data, set_value('filter_journey_id', $filter_journey_id), '', 'form-control input-sm');
		
		$array_data = get_array("select id, name from tb_journey_detail where journey_id = '$filter_journey_id' AND published");
		
		$html['filter_journey_detail_id'] = html_select('filter_journey_detail_id', $array_data, set_value('filter_journey_detail_id', $filter_journey_detail_id), '', 'form-control input-sm');
		
		$data['html'] = $html;
		
		$data['offset'] = 0;
		
		// load view
		$this->load->view('main', $data);
	}
	
	function _rows2array($rows, $percent = false){
		$array = array();
		$array[] = array('label', 'value');

		$total = 0;		
		if($percent){
			foreach($rows as $row){
				$total += $row->value;
			}
		}
		
		foreach($rows as $row){
			$array[] = array($row->label, ($percent ? round($row->value*100/$total,2) : floatval($row->value)));
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
		
		//filter_journey_id
		$filter_journey_id_old = $this->session->userdata($this->controller.'_'.'filter_journey_id');
		if(isset($_POST['filter_journey_id'])){
			$filter_journey_id = $this->input->post('filter_journey_id');
			if($filter_journey_id!=$filter_journey_id_old){
				$this->session->set_userdata($this->controller.'_'.'filter_journey_id', $filter_journey_id);
			}
		}
		else{
			$filter_journey_id = $this->session->userdata($this->controller.'_'.'filter_journey_id');
		}
		$filter_journey_id = $filter_journey_id ? $filter_journey_id : get_val("select min(id) from tb_journey where published");
		$this->session->set_userdata($this->controller.'_'.'filter_journey_id', $filter_journey_id);
		
		//filter_journey_detail_id
		$filter_journey_detail_id_old = $this->session->userdata($this->controller.'_'.'filter_journey_detail_id');
		if(isset($_POST['filter_journey_detail_id'])){
			$filter_journey_detail_id = $this->input->post('filter_journey_detail_id');
			if($filter_journey_detail_id!=$filter_journey_detail_id_old){
				$this->session->set_userdata($this->controller.'_'.'filter_journey_detail_id', $filter_journey_detail_id);
			}
		}
		else{
			$filter_journey_detail_id = $this->session->userdata($this->controller.'_'.'filter_journey_detail_id');
		}
		$filter_journey_detail_id = $filter_journey_detail_id ? $filter_journey_detail_id : get_val("select min(id) from tb_journey_detail where journey_id = '$filter_journey_id' and published");
		$this->session->set_userdata($this->controller.'_'.'filter_journey_detail_id', $filter_journey_detail_id);
		
		if(!get_val("select id from tb_journey_detail where journey_id = '$filter_journey_id' and id = '$filter_journey_detail_id'")){
			$filter_journey_detail_id = get_val("select min(id) from tb_journey_detail where journey_id = '$filter_journey_id' and published");
			$this->session->set_userdata($this->controller.'_'.'filter_journey_detail_id', $filter_journey_detail_id);
		}
		
		// load data
		$rows = $this->model->get_paged_list($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_detail_id, $type)->result();
		echo json_encode($this->_rows2array($rows));
	}
	
	function getjourneydetaillist($filter_journey_id){
		$array_data = get_array("select id, name from tb_journey_detail where journey_id = '$filter_journey_id' AND published");
		echo json_encode($array_data);
	}
}

?>