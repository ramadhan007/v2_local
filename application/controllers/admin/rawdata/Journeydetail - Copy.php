<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JourneyDetail extends CI_Controller {

	//controller main properties
	var $controller = "admin/rawdata/journeydetail";
	var	$title = "journey detail raw data";
	
	// num of records per page
	var $limit = 8;
	var $num_links = 10;
	
	var	$view = array();
	var	$user = array();
	
	function __construct(){
		parent::__construct();
		
		// load library
		$this->load->model('rawdata/journeydetailModel','model',TRUE);
		
		$this->view['doctitle'] = ucwords($this->title);
		
		//check login
		if($this->user['logged_in']){
			if($this->user['usertype']>3) redirect('admin/area');
		}
		else{
			redirect('admin/login');
		}
		
		//reset filter_date_start & end jika komputer ganti tanggal
		$current_date = $this->session->userdata($this->controller.'_current_date');
		if($current_date){
			if($current_date!=date('d-m-Y')){	//tanggal baru, reset filter_date
				$this->session->set_userdata($this->controller.'_filter_date_start');
				$this->session->unset_userdata($this->controller.'_filter_date_end');
				
				$this->session->set_userdata($this->controller.'_current_date', date('d-m-Y'));
			}
		}else{	//session habis
			$this->session->set_userdata($this->controller.'_current_date', date('d-m-Y'));
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
		
		//filter cari
		if(isset($_POST['filter_cari'])){
			$filter_cari = $this->input->post('filter_cari');
			$this->session->set_userdata($this->controller.'_filter_cari', $filter_cari);
			$offset=0;
		}
		else{
			$filter_cari = $this->session->userdata($this->controller.'_filter_cari');
		}
		
		//filter_date
		$filter_date_old = $this->session->userdata($this->controller.'_filter_date');
		if(isset($_POST['filter_date'])){
			$filter_date = $this->input->post('filter_date');
			if($filter_date!=$filter_date_old){
				$this->session->set_userdata($this->controller.'_filter_date', $filter_date);
				$offset=0;
			}
		}
		else{
			$filter_date = $this->session->userdata($this->controller.'_filter_date');
		}
		if(!$filter_date){
			$filter_date = date('d-m-Y');
			$this->session->set_userdata($this->controller.'_filter_date', $filter_date);
			$offset=0;
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata($this->controller.'_filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata($this->controller.'_filter_location_id', $filter_location_id);
				$offset=0;
			}
		}
		else{
			$filter_location_id = $this->session->userdata($this->controller.'_filter_location_id');
		}
		
		//filter_operator_id
		$filter_operator_id_old = $this->session->userdata($this->controller.'_filter_operator_id');
		if(isset($_POST['filter_operator_id'])){
			$filter_operator_id = $this->input->post('filter_operator_id');
			if($filter_operator_id!=$filter_operator_id_old){
				$this->session->set_userdata($this->controller.'_filter_operator_id', $filter_operator_id);
				$offset=0;
			}
		}
		else{
			$filter_operator_id = $this->session->userdata($this->controller.'_filter_operator_id');
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
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_location_id, $filter_operator_id)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($filter_location_id, $filter_operator_id);
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
		
		//filter cari
		$filter_cari_old = $this->session->userdata($this->controller.'_filter_cari');
		if(isset($_POST['filter_cari'])){
			$filter_cari = $this->input->post('filter_cari');
			if($filter_cari!=$filter_cari_old){
				$this->session->set_userdata($this->controller.'_filter_cari', $filter_cari);
				$offset=0;
			}
		}
		else{
			$filter_cari = $this->session->userdata($this->controller.'_filter_cari');
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata($this->controller.'_filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata($this->controller.'_filter_location_id', $filter_location_id);
				$offset=0;
			}
		}
		else{
			$filter_location_id = $this->session->userdata($this->controller.'_filter_location_id');
		}
		
		//filter_operator_id
		$filter_operator_id_old = $this->session->userdata($this->controller.'_filter_operator_id');
		if(isset($_POST['filter_operator_id'])){
			$filter_operator_id = $this->input->post('filter_operator_id');
			if($filter_operator_id!=$filter_operator_id_old){
				$this->session->set_userdata($this->controller.'_filter_operator_id', $filter_operator_id);
				$offset=0;
			}
		}
		else{
			$filter_operator_id = $this->session->userdata($this->controller.'_filter_operator_id');
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
			$rows = $this->model->get_paged_list($this->limit, $offset, $filter_location_id, $filter_operator_id)->result();
			$data['rows'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody', $data, true);
			echo str_replace("'","\'",$tbody);
		}
		elseif($param=='pagin'){
			// generate pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url($this->controller.'/index/');
			$config['total_rows'] = $this->model->count_all($filter_location_id, $filter_operator_id);
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
			$numrows = count($this->model->get_paged_count($this->limit, $offset, $filter_location_id, $filter_operator_id)->result());
			$totalrows = $this->model->count_all($filter_location_id, $filter_operator_id);
			if($numrows){
				echo "Showing ".($offset+1)." to ".($offset+$numrows)." of ".$totalrows." entries";
			}else{
				echo "No record";
			}
		}
		else{
			echo "";
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
	
	function downloadxls($location_ids, $operator_ids, $export_date_start, $export_date_end){
		$location_ids = urldecode($location_ids);
		$operator_ids = urldecode($operator_ids);
		$export_date_start = date_dmy2mysql($export_date_start);
		$export_date_end = date_dmy2mysql($export_date_end);
		
		$ar_query = array();
		$ar_judulsheet = array();
		
		$namafile = "raw_journey_detail_".($export_date_start==$export_date_end ? $export_date_start : $export_date_start."_".$export_date_end);
		
		$ar_judulsheet[0] = "Sheet1";
		$ar_query[0] = 	"SELECT	e.id AS device_id, f.name AS location_name, g.name AS operator_name, d.name AS journey_name, c.name AS journey_detail_name,
					b.`cellid`, b.`location_lat`, b.`location_lng`, a.`network_type`, a.`signal_level`, a.`response_time`, a.monitor_datetime, a.status, a.message
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
			//prepare main array
			$array = array();
			
			//get column header
			$el_array = array();
			foreach($rows[0] as $key=>$val){
				$el_array[] = $key;
			}
			$array[] = $el_array;
			
			//get all rows
			foreach($rows as $row){
				$el_array = array();
				foreach($row as $key=>$val){
					$el_array[] = str_replace("\n"," ",$val);
				}
				$array[] = $el_array;
			}
		}
		
		$this->load->library('phpexcel');
		
		$cm = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
		PHPExcel_Settings::setCacheStorageMethod($cm);
		
		// Set properties dokument excel
		$this->phpexcel->getProperties()->setCreator("RealUSSDMon");
		$this->phpexcel->getProperties()->setLastModifiedBy("RealUSSDMon");
		$this->phpexcel->getProperties()->setTitle("Journey Detail Raw Data");
		$this->phpexcel->getProperties()->setSubject("Journey Detail Raw Data");
		$this->phpexcel->getProperties()->setDescription("This document is generated using PHP classes.");
		
		//border style
		$border_style= array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '766f6e')),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '766f6e')),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '766f6e')),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '766f6e')),
			)
		);
		
		// print_r($ar_query);
		
		$n = 0;
		foreach($ar_query as $query){
			// load query, library
			$judulsheet = $ar_judulsheet[$n];
			$rows = get_rows($query);
			
			//create sheet
			if($n) $this->phpexcel->createSheet($n);
			
			//print kepala kolom
			$i = 0;
			foreach($rows[0] as $field=>$value){
				$i++;
				$sheet = $this->phpexcel->setActiveSheetIndex($n);	//get sheet aktif
				// $sheet->setCellValue($this->phpexcel->num2char($i)."1", $field);	//cell data
				$sheet->getCell($this->phpexcel->num2char($i)."1")->setValueExplicit($field, PHPExcel_Cell_DataType::TYPE_STRING);
				$sheet->getStyle($this->phpexcel->num2char($i)."1")->applyFromArray($border_style); //border
				$sheet->getStyle($this->phpexcel->num2char($i)."1")->getFont()->setBold(true);	//bold
			}
			
			//print data
			$i = 1;
			foreach($rows as $row){
				$i++;
				$j = 0;
				foreach($row as $field=>$value){
					$j++;
					$celldata = $value;
					$celldata = str_replace(chr(133),"....",$celldata);
					$celldata = str_replace(chr(145),chr(39),$celldata);
					$celldata = str_replace(chr(146),chr(39),$celldata);
					$celldata = str_replace(chr(147),chr(34),$celldata);
					$celldata = str_replace(chr(148),chr(34),$celldata);
					$celldata = str_replace("\n",chr(10),$celldata);
					for($k=128; $k<=255; $k++)
					{
						$celldata = str_replace(chr($k),"",$celldata);
					}
					
					$sheet = $this->phpexcel->setActiveSheetIndex($n);	//sheet aktif = n
					// $sheet->setCellValue($this->phpexcel->num2char($j).($i), $celldata);	//cell data
					$sheet->getCell($this->phpexcel->num2char($j).($i))->setValueExplicit($celldata, PHPExcel_Cell_DataType::TYPE_STRING);
					$sheet->getStyle($this->phpexcel->num2char($j).($i))->applyFromArray($border_style);	//border
				}
			}
			
			// Beri nama sheet
			$this->phpexcel->getActiveSheet()->setTitle($judulsheet);
			$n++;
		}
		
		$this->phpexcel->setActiveSheetIndex(0);
		
		// Save Excel 2007 file
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // Excel 2007
		// header('Content-Type: application/vnd.ms-excel');	// Excel 2003
		header('Content-Disposition: attachment;filename="'.$namafile.".xlsx".'"');	// set xls atau xlsx
		header('Cache-Control: max-age=0');
	
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007'); //Excel 2007
		// $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5'); //Excel 2003
		$objWriter->save('php://output');
	}
	
	function _array_to_xls_download($array, $filename = "export.xls", $delimiter=";") {
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		header("Content-Type: application/vnd.ms-excel;");
		header("Pragma: no-cache");
		header("Expires: 0");
	
		// open the "output" stream
		// see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
		$f = fopen('php://output', 'w');
	
		foreach ($array as $line) {
			fputcsv($f, $line, $delimiter);
		}
		fclose($f);
	}
	
	function _array_to_xlsx_download($array, $filename = "export.xlsx", $delimiter=";") {
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Pragma: no-cache");
		header("Expires: 0");
	
		// open the "output" stream
		// see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
		$f = fopen('php://output', 'w');
	
		foreach ($array as $line) {
			fputcsv($f, $line, $delimiter);
		}
		fclose($f);
	}
}

?>