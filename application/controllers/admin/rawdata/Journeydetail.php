<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JourneyDetail extends Admin_Controller {

	var $num_links = 10;
	
	function __construct(){
		parent::__construct(__FILE__, 'Journey Detail Raw Data', 'rawdata/journeydetail');
		
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
	
	function _get_offset()
	{
		$offset = $this->session->userdata($this->controller.'_offset');
		return $offset ? $offset : "0";
	}
	
	function _get_index_offset()
	{
		return site_url($this->controller.'/index/'.$this->_get_offset());
	}
	
	function index($offset = '')
	{
		if(!$this->continue) return;
		//check task
		$this->task = $this->input->post('task');
		
		switch($this->task)
		{
			case 'add':
				$this->_add();
				break;
			case 'edit':
				$cid = $this->input->post('cid');
				$this->edit($cid[0]);
				break;
			case 'delete':
				$cid = $this->input->post('cid');
				$this->_delete($cid);
				break;
			default:
				$this->_show($offset);
		}
	}
	
	function _show($offset = 0){	
		// offset
		$uri_segment = 4;
		if($offset==''){
			$offset = $this->session->userdata($this->controller.'_offset');
			$offset = $offset!='' ? $offset : 0;
		}
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb($this->_get_index_offset(),ucwords($this->title),true);
		
		//filter_monitor_date_start
		$filter_monitor_date_start_old = $this->session->userdata('filter_monitor_date_start');
		if(isset($_POST['filter_monitor_date_start'])){
			$filter_monitor_date_start = $this->input->post('filter_monitor_date_start');
			if($filter_monitor_date_start!=$filter_monitor_date_start_old){
				$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
				$offset=0;
			}
		}
		else{
			$filter_monitor_date_start = $this->session->userdata('filter_monitor_date_start');
		}
		if(!$filter_monitor_date_start){
			$filter_monitor_date_start = date('d-m-Y 00:00');
			$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
			$offset=0;
		}
		
		//filter_monitor_date_end
		$filter_monitor_date_end_old = $this->session->userdata('filter_monitor_date_end');
		if(isset($_POST['filter_monitor_date_end'])){
			$filter_monitor_date_end = $this->input->post('filter_monitor_date_end');
			if($filter_monitor_date_end!=$filter_monitor_date_end_old){
				$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
				$offset=0;
			}
		}
		else{
			$filter_monitor_date_end = $this->session->userdata('filter_monitor_date_end');
		}
		if(!$filter_monitor_date_end){
			$filter_monitor_date_end = date('d-m-Y 23:59');
			$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
			$offset=0;
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata('filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata('filter_location_id', $filter_location_id);
				$offset=0;
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
				$offset=0;
			}
		}
		else{
			$filter_operator_id = $this->session->userdata('filter_operator_id');
		}
		
		//filter_journey_detail_id
		$filter_journey_detail_id_old = $this->session->userdata('filter_journey_detail_id');
		if(isset($_POST['filter_journey_detail_id'])){
			$filter_journey_detail_id = $this->input->post('filter_journey_detail_id');
			if($filter_journey_detail_id!=$filter_journey_detail_id_old){
				$this->session->set_userdata('filter_journey_detail_id', $filter_journey_detail_id);
				$offset=0;
			}
		}
		else{
			$filter_journey_detail_id = $this->session->userdata('filter_journey_detail_id');
		}
		
		//filter_journey_id
		$filter_journey_id_old = $this->session->userdata('filter_journey_id');
		if(isset($_POST['filter_journey_id'])){
			$filter_journey_id = $this->input->post('filter_journey_id');
			if($filter_journey_id!=$filter_journey_id_old){
				$this->session->set_userdata('filter_journey_id', $filter_journey_id);
				$offset=0;
				
				$filter_journey_detail_id = '';
				$this->session->set_userdata('filter_journey_detail_id', $filter_journey_detail_id);
				
			}
		}
		else{
			$filter_journey_id = $this->session->userdata('filter_journey_id');
		}
		
		//filter_status
		$filter_status_old = $this->session->userdata('filter_status');
		if(isset($_POST['filter_status'])){
			$filter_status = $this->input->post('filter_status');
			if($filter_status!==$filter_status_old){
				$this->session->set_userdata('filter_status', $filter_status);
				$offset=0;
			}
		}
		else{
			$filter_status = $this->session->userdata('filter_status');
		}
		
		//filter limit
		$filter_limit_old = $this->session->userdata($this->controller.'_filter_limit');
		if(isset($_POST['filter_limit'])){
			$filter_limit = $this->input->post('filter_limit');
			if($filter_limit!=$filter_limit_old){
				$offset=0;
			}
		}
		else{
			$filter_limit = $this->session->userdata($this->controller.'_filter_limit');
		}
		$this->limit = $filter_limit ? $filter_limit : $this->limit;
        $this->session->set_userdata($this->controller.'_filter_limit', $this->limit);
		
		// save offset
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		// load data
		// $rows = $this->model->get_paged_list($this->limit, $offset, $filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id)->result();
		
		$rows = array();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_id, $filter_journey_detail_id, $filter_status);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['cur_page'] = $offset;
		$config['uri_segment'] = $uri_segment;
		$config['num_links'] = $this->num_links;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->view['toptitle'] = ucwords($this->title);
		$data['numrows'] = count($rows);
		
		//filter_limit
		$html['filter_limit'] = get_filter_limit($this->limit);
		
		$sql = "select id, name from tb_location where published order by name";
		$array_data = get_array($sql);
		$html['filter_location_id'] = html_select('filter_location_id', $array_data, set_value('filter_location_id', $filter_location_id), 'Location', 'form-control');
		$html['export_location_id'] = html_select_multiple('export_location_id', $array_data, '', '', 'form-control select2');
		
		$sql = "select id, name from tb_operator where published order by name";
		$array_data = get_array($sql);
		$html['filter_operator_id'] = html_select('filter_operator_id', $array_data, set_value('filter_operator_id', $filter_operator_id), 'Operator', 'form-control');
		$html['export_operator_id'] = html_select_multiple('export_operator_id', $array_data, '', '', 'form-control select2');
		
		$sql = "select id, name from tb_journey where published order by name";
		$array_data = get_array($sql);
		$html['filter_journey_id'] = html_select('filter_journey_id', $array_data, set_value('filter_journey_id', $filter_journey_id), 'Journey', 'form-control');
		$html['export_journey_id'] = html_select_multiple('export_journey_id', $array_data, '', '', 'form-control select2');
		
		if($filter_journey_id){
			$sql = "select id, name from tb_journey_detail where journey_id = '$filter_journey_id' and published order by name";
		}else{
			$sql = "select id, name from tb_journey_detail where FALSE";
		}
		$array_data = get_array($sql);
		$html['filter_journey_detail_id'] = html_select('filter_journey_detail_id', $array_data, set_value('filter_journey_detail_id', $filter_journey_detail_id), 'Journey Detail', 'form-control');
		$html['export_journey_detail_id'] = html_select_multiple('export_journey_detail_id', $array_data, '', '', 'form-control select2');
		
		$array_data = array(
				'0' => array('value' => '0', 'text' => 'Success'),
				'1' => array('value' => '1', 'text' => 'UI Fail'),
				'2' => array('value' => '2', 'text' => 'OTP Fail'),
				'3' => array('value' => '3', 'text' => 'APM Error'),
			);
		$html['filter_status'] = html_select('filter_status', $array_data, set_value('filter_status', $filter_status), 'Status', 'form-control');
		$html['export_status'] = html_select_multiple('export_status', $array_data, '', '', 'form-control select2');
		
		$data['html'] = $html;
		
		get_val("SELECT fn_need_refresh('".$this->user['id']."')");
		
		// load view
		$this->load->view('main', $data);
	}
	
	function listcontent($param='', $offset=''){	
		// offset
		$uri_segment = 4;
		if($offset==''){
			$offset = $this->session->userdata($this->controller.'_offset');
			$offset = $offset!='' ? $offset : 0;
		}
		
		//filter_monitor_date_start
		$filter_monitor_date_start_old = $this->session->userdata('filter_monitor_date_start');
		if(isset($_POST['filter_monitor_date_start'])){
			$filter_monitor_date_start = $this->input->post('filter_monitor_date_start');
			if($filter_monitor_date_start!=$filter_monitor_date_start_old){
				$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
				$offset=0;
			}
		}
		else{
			$filter_monitor_date_start = $this->session->userdata('filter_monitor_date_start');
		}
		if(!$filter_monitor_date_start){
			$filter_monitor_date_start = date('d-m-Y 00:00');
			$this->session->set_userdata('filter_monitor_date_start', $filter_monitor_date_start);
			$offset=0;
		}
		
		//filter_monitor_date_end
		$filter_monitor_date_end_old = $this->session->userdata('filter_monitor_date_end');
		if(isset($_POST['filter_monitor_date_end'])){
			$filter_monitor_date_end = $this->input->post('filter_monitor_date_end');
			if($filter_monitor_date_end!=$filter_monitor_date_end_old){
				$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
				$offset=0;
			}
		}
		else{
			$filter_monitor_date_end = $this->session->userdata('filter_monitor_date_end');
		}
		if(!$filter_monitor_date_end){
			$filter_monitor_date_end = date('d-m-Y 23:59');
			$this->session->set_userdata('filter_monitor_date_end', $filter_monitor_date_end);
			$offset=0;
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata('filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata('filter_location_id', $filter_location_id);
				$offset=0;
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
				$offset=0;
			}
		}
		else{
			$filter_operator_id = $this->session->userdata('filter_operator_id');
		}
		
		//filter_journey_detail_id
		$filter_journey_detail_id_old = $this->session->userdata('filter_journey_detail_id');
		if(isset($_POST['filter_journey_detail_id'])){
			$filter_journey_detail_id = $this->input->post('filter_journey_detail_id');
			if($filter_journey_detail_id!=$filter_journey_detail_id_old){
				$this->session->set_userdata('filter_journey_detail_id', $filter_journey_detail_id);
				$offset=0;
			}
		}
		else{
			$filter_journey_detail_id = $this->session->userdata('filter_journey_detail_id');
		}
		
		//filter_journey_id
		$filter_journey_id_old = $this->session->userdata('filter_journey_id');
		if(isset($_POST['filter_journey_id'])){
			$filter_journey_id = $this->input->post('filter_journey_id');
			if($filter_journey_id!=$filter_journey_id_old){
				$this->session->set_userdata('filter_journey_id', $filter_journey_id);
				$offset=0;
				
				$filter_journey_detail_id = '';
				$this->session->set_userdata('filter_journey_detail_id', $filter_journey_detail_id);
				
			}
		}
		else{
			$filter_journey_id = $this->session->userdata('filter_journey_id');
		}
		
		//filter_status
		$filter_status_old = $this->session->userdata('filter_status');
		if(isset($_POST['filter_status'])){
			$filter_status = $this->input->post('filter_status');
			if($filter_status!==$filter_status_old){
				$this->session->set_userdata('filter_status', $filter_status);
				$offset=0;
			}
		}
		else{
			$filter_status = $this->session->userdata('filter_status');
		}
		
		//filter limit
		$filter_limit_old = $this->session->userdata($this->controller.'_filter_limit');
		if(isset($_POST['filter_limit'])){
			$filter_limit = $this->input->post('filter_limit');
			if($filter_limit!=$filter_limit_old){
				$offset=0;
			}
		}
		else{
			$filter_limit = $this->session->userdata($this->controller.'_filter_limit');
		}
		$this->limit = $filter_limit ? $filter_limit : $this->limit;
        $this->session->set_userdata($this->controller.'_filter_limit', $this->limit);
		
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		if($param=='tbody'){
			$rows = $this->model->get_paged_list($this->limit, $offset, $filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_id, $filter_journey_detail_id, $filter_status)->result();
			$data['rows'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody', $data, true);
			echo str_replace("'","\'",$tbody);
		}
		elseif($param=='pagin'){
			// generate pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url($this->controller.'/index/');
			$config['total_rows'] = $this->model->count_all($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_id, $filter_journey_detail_id, $filter_status);
			$data['total_rows'] = $config['total_rows'];
			$config['per_page'] = $this->limit;
			$config['cur_page'] = $offset;
			$config['uri_segment'] = $uri_segment;
			$config['num_links'] = $this->num_links;
			
			//initialize pagination
			$this->pagination->initialize($config);
			echo $this->pagination->create_links();
		}
		elseif($param=='paginfo'){
			$numrows = count($this->model->get_paged_count($this->limit, $offset, $filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_id, $filter_journey_detail_id, $filter_status)->result());
			$totalrows = $this->model->count_all($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_id, $filter_journey_detail_id, $filter_status);
			if($numrows){
				echo "Showing ".($offset+1)." to ".($offset+$numrows)." of ".$totalrows." entries";
			}else{
				echo "No record";
			}
		}
		else{
			$ret_array = array();
			$rows = $this->model->get_paged_list($this->limit, $offset, $filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_id, $filter_journey_detail_id, $filter_status)->result();
			$data['rows'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody', $data, true);
			$ret_array['tbody'] = str_replace("'","\'",$tbody);
			
			// generate pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url($this->controller.'/index/');
			$config['total_rows'] = $this->model->count_all($filter_monitor_date_start, $filter_monitor_date_end, $filter_location_id, $filter_operator_id, $filter_journey_id, $filter_journey_detail_id, $filter_status);
			$data['total_rows'] = $config['total_rows'];
			$config['per_page'] = $this->limit;
			$config['cur_page'] = $offset;
			$config['uri_segment'] = $uri_segment;
			$config['num_links'] = $this->num_links;
			
			//initialize pagination
			$this->pagination->initialize($config);
			$ret_array['pagin'] = $this->pagination->create_links();
			
			$numrows = count($rows);
			$totalrows = $data['total_rows'];
			if($numrows){
				$ret_array['paginfo'] = "Showing ".($offset+1)." to ".($offset+$numrows)." of ".$totalrows." entries";
			}else{
				$ret_array['paginfo'] = "No record";
			}
			echo json_encode($ret_array);
		}
		
		get_val("SELECT fn_need_refresh('".$this->user['id']."')");
	}
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$this->view['toptitle'] = ucwords($this->title).' - Tambah';
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_add','Add',false);
	
		// load view
		$this->load->view('main', $data);
	}
	
	function addData(){
		//set validation rules
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			//get view data
			$data = $this->_get_view_data();	//must be called here, to retrieve the validated value
			
			// template variables
			$this->view['toptitle'] = ucwords($this->title).' - Tambah';
			$this->view['content'] = $this->controller.'/edit';
			
			// reload view
			$this->load->view('main', $data);
		}
		else
		{
			// save data
			$row = $this->_get_post_data();
			$id = $this->model->save($row);
			
			// redirect to list page
			redirect($this->controller.'/index/'.$this->_get_offset());
		}
	}
	
	function edit($id){
		// save $id as session for next use
		$this->session->set_userdata($this->controller.'_id', $id);
		
		// prefill form values
		$row = $this->model->get_by_id($id);
		
		// get view data
		$data = $this->_get_view_data(TRUE, $row);
		$data['row'] = $row;
		
		// template variables
		$this->view['toptitle'] = ucwords($this->title).' - Edit';
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function updateData(){
		$id = $this->input->post('id');
		
		// set validation properties
		$this->_set_rules(TRUE);
		
		// check name changed
		$str = $this->input->post('name');
		if($str!=$this->model->get_field_by_id($id, 'name')){
			$this->_set_rules_name(TRUE);
		}
		else{
			$this->_set_rules_name(FALSE);
		}
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			//get view data
			$data = $this->_get_view_data(TRUE);
			
			// template variables
			$this->view['toptitle'] = ucwords($this->title).' - Edit';
			$this->view['content'] = $this->controller.'/edit';
		
			// load view
			$this->load->view('main', $data);
		}
		else
		{
			// save data
			$row = $this->_get_post_data();
			$this->model->update($id,$row);
			
			// redirect to list page
			redirect($this->controller.'/index/'.$this->_get_offset());
		}
	}
	
	function _delete($id){
		// delete data
		$this->model->delete($id);
		
		// redirect to person list page
		redirect($this->controller.'/index/'.$this->_get_offset());
	}
	
	// validation rules
	function _set_rules($editmode=FALSE){
		if($editmode){
			
		}else{
			$this->form_validation->set_rules('name','Nama','trim|required|is_unique[tb_journey.name]');
		}
	}
	
	function _set_rules_name($changed=FALSE){
		if($changed){
			$this->form_validation->set_rules('name','Nama','trim|required|is_unique[tb_journey.name]');
		}else{
			$this->form_validation->set_rules('name','Nama','trim|required');
		}
	}
	
	// date_validation callback
	function valid_date($str)
	{
		if(!preg_match("/^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$/", $str))
		{
			$this->form_validation->set_message('valid_date', 'date format is not valid. dd-mm-yyyy');
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function _get_html($row=array())
	{
		//prepare select/radio html
		$html = array();
		
		//published
		$array_data = get_list_item('published');
		$html['published'] = html_select('published', $array_data, set_value('published', ($row ? $row->published : '1')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data(){
		$row = array(
				'name' => $this->input->post('name'),
				'published' => $this->input->post('published'),
			);
		return $row;
	}
	
	function _get_view_data($editmode=FALSE, $row=array())
	{
		// set common properties
		if($editmode)
		{
			$label = "Edit ";
			$method = "updateData";
		}
		else
		{
			$label = "Add ";
			$method = "addData";
		}
		
		$data['title'] = $label.$this->title;
		$data['message'] = '';
		$data['action'] = site_url($this->controller.'/'.$method.'/'.$this->_get_offset());
		
		//set link_back link
		$data['link_back'] = $this->_get_index_offset();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
	
	function checknewdata(){
		echo get_val("SELECT fn_need_refresh('".$this->user['id']."')");
	}
	
	function getclass(){
		$ids = $this->input->post('ids');
		
		$sql = "
			SELECT	id, fn_get_list_item_range_class('signal_level', `signal_level`) AS signal_level_class,
					fn_get_list_item_range_class('response_time', `response_time`) AS response_time_class
			FROM 	tb_monitor_journey_detail
			WHERE	id IN ($ids)
		";
		
		echo json_encode(get_rows($sql));
	}
	
	function checkdownloadxls($location_ids, $operator_ids, $export_date_start, $export_date_end){
		$session_name = $this->config->item('sess_cookie_name');
		$_SESSION[$session_name."_".$this->controller."_download_progress_valuenow"] = 0;
		
		$location_ids = str_replace(" ", ",", urldecode($location_ids));
		$operator_ids = str_replace(" ", ",", urldecode($operator_ids));
		$export_date_start = date_dmy2mysql($export_date_start);
		$export_date_end = date_dmy2mysql($export_date_end);
		
		$ar_query = array();
		
		$ar_query[0] = 	"SELECT COUNT(a.id)
			FROM (SELECT	a.id
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.monitor_date = b.monitor_date AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id`
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id`
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id`
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
			WHERE	f.id IN ($location_ids) AND g.`id` IN ($operator_ids)
					AND b.`monitor_date` BETWEEN '$export_date_start' AND '$export_date_end' limit 0,1) as a";
		
		echo get_val($ar_query[0], false);
	}
	
	function downloadxls($location_ids, $operator_ids, $export_date_start, $export_date_end){
		$location_ids = str_replace(" ", ",", urldecode($location_ids));
		$operator_ids = str_replace(" ", ",", urldecode($operator_ids));
		$export_date_start = date_dmy2mysql($export_date_start);
		$export_date_end = date_dmy2mysql($export_date_end);
		
		$ar_query = array();
		$ar_judulsheet = array();
		
		$namafile = "raw_journey_detail_".($export_date_start==$export_date_end ? $export_date_start : $export_date_start."_".$export_date_end);
		
		$array = get_list_item("response_time");
		$rt_state = get_query_range($array, "a.response_time");
		
		$array = get_list_item("response_time_nvt");
		$nvt_result = get_query_range($array, "@nvt_response_time");
		
		$array = get_list_item("signal_level");
		$signal_level = get_query_range($array, "a.signal_level");
		
		$ar_judulsheet[0] = "Sheet1";
		$ar_query[0] = 	"SELECT	e.id AS device_id, f.name AS location_name, g.name AS operator_name, d.name AS journey_name, c.name AS journey_detail_name,
					if(a.network_type='3G', mod(a.`cellid`,65536), a.`cellid`) as cellid, b.`location_lat`, b.`location_lng`, a.`network_type`, a.`signal_level`, a.signal_quality, a.ber, a.`response_time`, ($rt_state) as rt_state,
					a.monitor_datetime,
					(case a.status
						when '3' then 'Wrong PIN'
						when '2' then 'Miss Page'
						when '1' then 'General Error'
						else 'Success'
					end) as status,
					(case a.scheduled when 1 then 'Scheduled' else 'Unscheduled' end) as error_type, a.message,
					@nvt_response_time := round(fn_get_monitor_journey_nvt_response_time(b.`id`),3) AS nvt_response_time,
					($nvt_result) as nvt_result,
					round(fn_get_monitor_journey_nvt_signal_level(b.`id`),0) AS nvt_signal_level,
					($signal_level) as signal_state
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.monitor_date = b.monitor_date AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id`
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id`
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id`
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
			WHERE	f.id IN ($location_ids) AND g.`id` IN ($operator_ids)
					AND b.`monitor_date` BETWEEN '$export_date_start' AND '$export_date_end'
			ORDER	by b.monitor_datetime desc, a.monitor_datetime desc";
			
			
		$rows = get_rows($ar_query[0]);
		
		if(count($rows)){
			$this->load->library('excelwriter');
			$this->excelwriter->setAuthor('RealUSSDMon');
			
			//get column header
			$header = array();
			foreach($rows[0] as $key=>$val){
				$header[$key] = 'string';
			}
			$this->excelwriter->writeSheetHeader('Sheet1', $header);
			
			$session_name = $this->config->item('sess_cookie_name');
			
			unset($_SESSION[$session_name."_".$this->controller."_download_progress_reported"]);
			$_SESSION[$session_name."_".$this->controller."_download_progress_valuenow"] = 0;
			$_SESSION[$session_name."_".$this->controller."_download_progress_valuemin"] = 0;
			$_SESSION[$session_name."_".$this->controller."_download_progress_valuemax"] = count($rows);
			$i = 0;
			
			//get all rows
			foreach($rows as $row){
				$array = array();
				foreach($row as $key=>$val){
					$array[] = str_replace("\n"," ",$val);
				}
				$this->excelwriter->writeSheetRow('Sheet1', $array);
				
				//progress
				if($i) session_start();
				$i++;
				$_SESSION[$session_name."_".$this->controller."_download_progress_valuenow"] = $i;
				session_write_close();
			}
			
			session_start();
			$_SESSION[$session_name."_".$this->controller."_download_progress_valuenow"] = count($rows);
			session_write_close();
			
			// download
			$this->excelwriter->setFileName($namafile.".xlsx");
			$this->excelwriter->writeToStdOut();
		}else{
			echo "No data";
		}
	}
	
	function qrydownload($location_ids, $operator_ids, $export_date_start, $export_date_end){
		$location_ids = str_replace(" ", ",", urldecode($location_ids));
		$operator_ids = str_replace(" ", ",", urldecode($operator_ids));
		$export_date_start = date_dmy2mysql($export_date_start);
		$export_date_end = date_dmy2mysql($export_date_end);
		
		$ar_query = array();
		$ar_judulsheet = array();
		
		$namafile = "raw_journey_detail_".($export_date_start==$export_date_end ? $export_date_start : $export_date_start."_".$export_date_end);
		
		$array = get_list_item("response_time");
		$rt_state = get_query_range($array, "a.response_time");
		
		$array = get_list_item("response_time_nvt");
		$nvt_result = get_query_range($array, "@nvt_response_time");
		
		$array = get_list_item("signal_level");
		$signal_level = get_query_range($array, "a.signal_level");
		
		$ar_judulsheet[0] = "Sheet1";
		$ar_query[0] = 	"SELECT	e.id AS device_id, f.name AS location_name, g.name AS operator_name, d.name AS journey_name, c.name AS journey_detail_name,
					if(a.network_type='3G', mod(a.`cellid`,65536), a.`cellid`) as cellid, b.`location_lat`, b.`location_lng`, a.`network_type`, a.`signal_level`, a.`response_time`, ($rt_state) as rt_state,
					a.monitor_datetime,
					(case a.status
						when '3' then 'Wrong PIN'
						when '2' then 'Miss Page'
						when '1' then 'General Error'
						else 'Success'
					end) as status,
					(case a.scheduled when 1 then 'Scheduled' else 'Unscheduled' end) as error_type, a.message,
					@nvt_response_time := round(fn_get_monitor_journey_nvt_response_time(b.`id`),3) AS nvt_response_time,
					($nvt_result) as nvt_result,
					round(fn_get_monitor_journey_nvt_signal_level(b.`id`),0) AS nvt_signal_level,
					($signal_level) as signal_state
			FROM 	`tb_monitor_journey_detail` AS a
					INNER JOIN `tb_monitor_journey` AS b ON (a.device_id = b.device_id AND a.monitor_date = b.monitor_date AND a.`monitor_journey_id` = b.`id`)
					INNER JOIN `tb_journey_detail` AS c ON a.`journey_detail_id` = c.`id`
					INNER JOIN `tb_journey` AS d ON b.`journey_id` = d.`id`
					INNER JOIN `tb_device` AS e ON b.`device_id` = e.`id`
					INNER JOIN `tb_location` AS f ON e.`location_id` = f.`id`
					INNER JOIN `tb_operator` AS g ON e.`operator_id` = g.`id`
			WHERE	f.id IN ($location_ids) AND g.`id` IN ($operator_ids)
					AND b.`monitor_date` BETWEEN '$export_date_start' AND '$export_date_end'
			ORDER	by b.monitor_datetime desc, a.monitor_datetime desc";
			
		echo $ar_query[0];
	}
	
	function updatefilterjourney($filter_journey_id = ''){
		$this->session->set_userdata('filter_journey_id', $filter_journey_id);
		$this->session->set_userdata($this->controller.'_offset', 0);
		
		exit("TRUE");
	}
}

?>