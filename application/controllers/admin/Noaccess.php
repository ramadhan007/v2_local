<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noaccess extends CI_Controller {

	//controller main properties
	var $controller = "admin/noaccess";
	
	var	$view = array();
	var	$user = array();
	
	function __construct(){
		parent::__construct();
		
		// load library
		$this->load->model('testModel','',TRUE);
		$this->model = new testModel;
		
		$this->view['doctitle'] = 'Tidak Mempunyai Akses';
		
		//check login
		if(!$this->user['logged_in']) redirect('admin/login');
	}
	
	function index()
	{
		$this->view['content'] = $this->controller.'/list';
	}
	
	function _show($offset = 0){	
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb($this->_get_index_offset(),'Manage '.ucwords($this->title),false);
		
		//filter cari
		if(isset($_POST['filter_cari']))
		{
			$filter_cari = $this->input->post('filter_cari');
			$this->session->set_userdata($this->controller.'_filter_cari', $filter_cari);
			$offset=0;
		}
		else
		{
			$filter_cari = $this->session->userdata($this->controller.'_filter_cari');
		}
		
		// load data
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($filter_cari);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title);
		$data['numrows'] = count($rows);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function _num2charxls($angka)
	{
		$strcode = '';
		$hasil = 1;
		$angka = $angka-1;
		$i = 1;
		while($hasil>0)
		{
			$hasil = intval($angka/26);
			$sisa = $angka%26;
			$strcode = chr($sisa + 65).$strcode;
			$angka = $hasil-1;
		}
		return $strcode;
	}
	
	function excel()
	{
		//yang perlu diisi
		$query = "select * from tb_menu_item";
		$namafile = "email.xlsx";
		$judulsheet = "Email";
		
		// load query, library
		$rows = get_rows($query);
		$this->load->library('phpexcel');
		
		// Set properties dokument excel
		$this->phpexcel->getProperties()->setCreator("Aris Munawar");
		$this->phpexcel->getProperties()->setLastModifiedBy("Riswo Ferdinand");
		$this->phpexcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
		$this->phpexcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
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
		
		//print kepala kolom
		$i = 0;
		foreach($rows[0] as $field=>$value){
			$i++;
			$sheet = $this->phpexcel->setActiveSheetIndex(0);	//get sheet aktif
			$sheet->setCellValue($this->_num2charxls($i)."1", $field);	//cell data
			$sheet->getStyle($this->_num2charxls($i)."1")->applyFromArray($border_style); //border
			$sheet->getStyle($this->_num2charxls($i)."1")->getFont()->setBold(true);	//bold
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
				
				$sheet = $this->phpexcel->setActiveSheetIndex(0);	//sheet aktif
				$sheet->setCellValue($this->_num2charxls($j).($i), $celldata);	//cell data
				$sheet->getStyle($this->_num2charxls($j).($i))->applyFromArray($border_style);	//border
			}
		}
		
		// Beri nama sheet
		$this->phpexcel->getActiveSheet()->setTitle($judulsheet);
		
		// Save Excel 2007 file
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$namafile.'"');
		header('Cache-Control: max-age=0');
	
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Add]';
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
			$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Add]';
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
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Edit]';
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function updateData(){
		// set validation properties
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			echo set_value('setting_name[]', '');
			echo "<br>";
			echo set_value('setting_val[]', '');
			exit();
			
			//get view data
			$data = $this->_get_view_data(TRUE);
			
			// template variables
			$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Edit]';
			$this->view['content'] = $this->controller.'/edit';
		
			// load view
			$this->load->view('main', $data);
		}
		else
		{
			// save data
			$id = $this->input->post('id');
			$row = $this->_get_post_data();
			$this->model->update($id,$row);
			
			// redirect to list page
			redirect($this->controller.'/index/'.$this->_get_offset());
		}
	}
	
	function view($id){
		// set common properties
		$data['title'] = 'Detail '.$this->title;
		$data['link_back'] = $this->_get_index_offset();
		
		// get record details
		$data['row'] = $this->model->get_by_id($id)->row();
		
		// template variables
		$this->view['content'] = $this->controller.'/view';
		set_breadcrumb($this->controller.'_view',$data['title'],false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function _delete($id){
		// delete data
		$this->model->delete($id);
		
		// redirect to person list page
		redirect($this->controller.'/index/'.$this->_get_offset());
	}
	
	// validation rules
	function _set_rules(){
		$this->form_validation->set_rules('name','Nama Test','trim|required');
		$this->form_validation->set_rules('remarks','Keterangan','');
		$this->form_validation->set_rules('published','Ditampilkan','trim|required');
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
		$array_data = array(
			'0' => array('value' => '1', 'text' => 'Ya'),
			'1' => array('value' => '0', 'text' => 'Tidak'),
			);
		$html['published'] = html_radio('published', $array_data, set_value('published', ($row ? $row->published : '1')), '', FALSE);
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data(){
		$ar_setting_name = $this->input->post('setting_name');
		$ar_setting_val = $this->input->post('setting_val');
		$ar_setting = array();
		for($i=0; $i<count($ar_setting_name); $i++){
			$ar_setting[$ar_setting_name[$i]] = $ar_setting_val[$i];
		}
		$setting = json_encode($ar_setting);
		
		$row = array('name' => $this->input->post('name'),
				'remarks' => $this->input->post('remarks'),
				'published' => $this->input->post('published')
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
}

?>
