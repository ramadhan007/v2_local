<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AvailabilityAnalysis extends Admin_Controller {
	function __construct(){
		parent::__construct(__FILE__, 'availability analysis', 'report/availabilityanalysis');
		
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
		
		//filter_schedule_page
		$filter_schedule_page_old = $this->session->userdata('filter_schedule_page');
		if(isset($_POST['filter_schedule_page'])){
			$filter_schedule_page = $this->input->post('filter_schedule_page');
			if($filter_schedule_page!=$filter_schedule_page_old){
				$this->session->set_userdata('filter_schedule_page', $filter_schedule_page);
			}
		}
		else{
			$filter_schedule_page = $this->session->userdata('filter_schedule_page');
		}
		
		//filter_schedule_error
		$filter_schedule_error_old = $this->session->userdata('filter_schedule_error');
		if(isset($_POST['filter_schedule_error'])){
			$filter_schedule_error = $this->input->post('filter_schedule_error');
			if($filter_schedule_error!=$filter_schedule_error_old){
				$this->session->set_userdata('filter_schedule_error', $filter_schedule_error);
			}
		}
		else{
			$filter_schedule_error = $this->session->userdata('filter_schedule_error');
		}
		
		// load data
		// $rows = $this->model->get_paged_list_page($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_schedule_page)->result();
		$rows = array();
		$data['rows_page'] = $rows;
		
		// $rows = $this->model->get_paged_list_error($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_schedule_error)->result();
		$rows = array();
		$data['rows_error'] = $rows;
		
		$this->view['toptitle'] = ucwords($this->title);
		
		$sql = "select id, name from tb_location where published order by name";
		$array_data = get_array($sql);
		$html['filter_location_id'] = html_select('filter_location_id', $array_data, set_value('filter_location_id', $filter_location_id), 'Location', 'form-control');
		$html['export_location_id'] = html_select_multiple('export_location_id', $array_data, '', '', 'form-control select2');
		
		$sql = "select id, name from tb_operator where published order by name";
		$array_data = get_array($sql);
		$html['filter_operator_id'] = html_select('filter_operator_id', $array_data, set_value('filter_operator_id', $filter_operator_id), 'Operator', 'form-control');
		$html['export_operator_id'] = html_select_multiple('export_operator_id', $array_data, '', '', 'form-control select2');
		
		$array_data = array(
			0 => array('value' => '1', 'text' => 'Scheduled'),
			1 => array('value' => '0', 'text' => 'Unscheduled'),
		);
		
		$html['filter_schedule_page'] = html_select('filter_schedule_page', $array_data, set_value('filter_schedule_page', $filter_schedule_page), '(All)', 'form-control input-sm');
		
		$html['filter_schedule_error'] = html_select('filter_schedule_error', $array_data, set_value('filter_schedule_error', $filter_schedule_error), '(All)', 'form-control input-sm');
		
		$data['html'] = $html;
		
		$data['offset'] = 0;
		
		// load view
		$this->load->view('main', $data);
	}
	
	function listcontent($param=''){	
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
		
		//filter_schedule_page
		$filter_schedule_page_old = $this->session->userdata('filter_schedule_page');
		if(isset($_POST['filter_schedule_page'])){
			$filter_schedule_page = $this->input->post('filter_schedule_page');
			if($filter_schedule_page!=$filter_schedule_page_old){
				$this->session->set_userdata('filter_schedule_page', $filter_schedule_page);
			}
		}
		else{
			$filter_schedule_page = $this->session->userdata('filter_schedule_page');
		}
		
		//filter_schedule_error
		$filter_schedule_error_old = $this->session->userdata('filter_schedule_error');
		if(isset($_POST['filter_schedule_error'])){
			$filter_schedule_error = $this->input->post('filter_schedule_error');
			if($filter_schedule_error!=$filter_schedule_error_old){
				$this->session->set_userdata('filter_schedule_error', $filter_schedule_error);
			}
		}
		else{
			$filter_schedule_error = $this->session->userdata('filter_schedule_error');
		}
		
		$data['offset'] = 0;
		
		if($param=='page'){
			$rows = $this->model->get_paged_list_page($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_schedule_page)->result();
			$data['rows_page'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody_page', $data, true);
			echo str_replace("'","\'",$tbody);
		}
		elseif($param=='error'){
			$rows = $this->model->get_paged_list_error($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_schedule_error)->result();
			$data['rows_error'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody_error', $data, true);
			echo str_replace("'","\'",$tbody);
		}
		else{
			echo "";
		}
	}
}

?>