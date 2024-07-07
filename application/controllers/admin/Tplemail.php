<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tplemail extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'template email', 'tplemail');
	}
	
	function _get_index()
	{
		return site_url($this->controller.'/index');
	}
	
	function index()
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
				$this->_show();
		}
	}
	
	function _show($offset = ''){
		// offset
		$uri_segment = 4;
		if($offset==''){
			$offset = $this->session->userdata($this->controller.'_offset');
			$offset = $offset!='' ? $offset : 0;
		}
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb($this->_get_index(),ucwords($this->title),true);
		
		//filter cari
		if(isset($_POST['filter_cari'])){
			$filter_cari = $this->input->post('filter_cari');
			$this->session->set_userdata($this->controller.'_filter_cari', $filter_cari);
			$offset=0;
		}
		else{
			$filter_cari = $this->session->userdata($this->controller.'_filter_cari');
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
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($filter_cari);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['cur_page'] = $offset;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title);
		$data['numrows'] = count($rows);
		
		//filter_limit
		$html['filter_limit'] = get_filter_limit($this->limit);
		$data['html'] = $html;
		
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
			$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari)->result();
			$data['rows'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody', $data, true);
			echo str_replace("'","\'",$tbody);
		}
		elseif($param=='pagin'){
			// generate pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url($this->controller.'/index/');
			$config['total_rows'] = $this->model->count_all($filter_cari);
			$data['total_rows'] = $config['total_rows'];
			$config['per_page'] = $this->limit;
			$config['cur_page'] = $offset;
			$config['uri_segment'] = $uri_segment;
			
			//initialize pagination
			$this->pagination->initialize($config);
			echo $this->pagination->create_links();
		}
		elseif($param=='paginfo'){
			$numrows = count($this->model->get_paged_list($this->limit, $offset, $filter_cari)->result());
			$totalrows = $this->model->count_all($filter_cari);
			if($numrows){
				echo "Menampilkan ".($offset+1)." sampai ".($offset+$numrows)." dari ".$totalrows." entri";
			}else{
				echo "Tidak ada entri";
			}
		}
		else{
			echo "";
		}
	}
	
	function _clear_upload_error(){
		$this->session->unset_userdata($this->controller.'_upload_error_file_logo');
	}
	
	function _add($recall = false){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//unset file error message
		if(!$recall) $this->_clear_upload_error();
		
		//get view data		
		$data = $this->_get_view_data();
		
		//set empty picture
		$data['logo'] = "";
		
		// template variables
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Add]';
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_add','Add',false);
	
		// load view
		$this->load->view('main', $data);
	}
	
	function addData(){
		//unset file error message
		$this->_clear_upload_error();
		
		//set validation rules
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			$this->_add(true);
		}
		else
		{
			// save data
			$row = $this->_get_post_data();
			$id = $this->model->save($row);
			
			// redirect to list page
			redirect($this->controller.'/index');
		}
	}
	
	function edit($id, $recall = false){
		// save $id as session for next use
		$this->session->set_userdata($this->controller.'_id', $id);
		
		//unset file error message
		if(!$recall) $this->_clear_upload_error();
		
		// prefill form values
		$row = $this->model->get_by_id($id);
		$row = fix_base_url($row);
		
		//get view data
		if($recall){
			$data = $this->_get_view_data(TRUE);
		}
		else{
			$data = $this->_get_view_data(TRUE, $row);
			$data['row'] = $row;
		}
		// $data['logo'] = $row->logo;
		
		$row->allow_edit = true;
		$view = ($row->allow_edit ? 'edit' : 'view');
		$label = ($row->allow_edit ? 'Edit' : 'Lihat');
		
		// template variables
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' - '.$label;
		$this->view['content'] = $this->controller.'/'.$view;
		set_breadcrumb($this->controller.'_'.$view,$label,false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function updateData(){
		//unset file error message
		$this->_clear_upload_error();
		
		// get edited id
		$id = $this->input->post('id');
		
		// set validation properties
		$this->_set_rules(TRUE);
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			$this->edit($id, true);
		}
		else
		{
			// save data
			$row = $this->_get_post_data(true);
			$this->model->update($id,$row);
			
			// redirect to list page
			redirect($this->controller.'/index');
		}
	}
	
	function _delete($id){
		// delete data
		$this->model->delete($id);
	}
	
	// validation rules
	function _set_rules($editmode=false){
		$this->form_validation->set_rules('category','Tag Template','trim|required');
		if($editmode){
			$this->form_validation->set_rules('tag','Tag Template','trim|required');
		}
		else{
			$this->form_validation->set_rules('tag','Tag Template','trim|required|is_unique[tb_tpl_email.tag]');
		}
		$this->form_validation->set_rules('subject','Email Subject','trim|required');
		$this->form_validation->set_rules('body','Email Body','trim');
		$this->form_validation->set_rules('body_whatsapp','Whatsapp Body','trim');
		$this->form_validation->set_rules('body_sms','SMS Body','trim');
		
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
		
		//category
		$array_data = array(
			'0' => array('value' => 'user', 'text' => 'User'),
			'1' => array('value' => 'booking', 'text' => 'Booking'),
			);
		$html['category'] = html_select('category', $array_data, set_value('category', ($row ? $row->category : '')), '');
		
		//body
		$html['body'] = html_ckeditor('body', set_value('body', ($row ? $row->body : '')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode = false){
		$login_data = $this->session->userdata('login_data_admin');
		$date_field = "date_".($editmode ? "update" : "insert");
		$date_value = date('Y-m-d H:i:s');
		$user_field = "user_".($editmode ? "update" : "insert");
		$user_value = $login_data['user']->id;
		
		$row = array(
				'category' => $this->input->post('category'),
				'tag' => $this->input->post('tag'),
				'subject' => $this->input->post('subject'),
				'body' => $this->input->post('body'),
				'body_whatsapp' => $this->input->post('body_whatsapp'),
				'body_sms' => $this->input->post('body_sms'),
				$date_field => $date_value,
				$user_field => $user_value,
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
		$data['action'] = site_url($this->controller.'/'.$method);
		
		//set link_back link
		$data['link_back'] = $this->_get_index();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>
