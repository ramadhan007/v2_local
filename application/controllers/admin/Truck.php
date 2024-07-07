<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Truck extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'truck', 'truck', true);
	}
	
	function _get_index()
	{
		return site_url($this->controller.'/index');
	}
	
	function index2(){
		$rootdir = str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']);
		$currentdir = str_replace("\\","/",getcwd());
		$config['admin'] = 'portal';
		echo($_SERVER['REQUEST_URI'].'/');
		echo("<br />");
		echo(str_replace($rootdir,'',$currentdir));
		echo("<br />");
		echo $request_uri = substr($_SERVER['REQUEST_URI'].'/',-(strlen($_SERVER['REQUEST_URI'].'/')-strlen(str_replace($rootdir,'',$currentdir))));
		echo("<br />");
		$ar_request_uri = explode("/", $request_uri);
		if($ar_request_uri[1]==$config['admin']){
			echo '';
		}
		else{
			echo '.html';
		}
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
		$rows = fix_base_url($rows);
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
			$rows = fix_base_url($rows);
			
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
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$this->view['toptitle'] = $this->view['toptitle'].' [Add]';
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
			$this->view['toptitle'] = $this->view['toptitle'].' [Add]';
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
			redirect($this->controller.'/index');
		}
	}
	
	function edit($id){
		// save $id as session for next use
		$this->session->set_userdata($this->controller.'_id', $id);
		
		// prefill form values
		$row = $this->model->get_by_id($id);
		
		//fix {[base_url]}
		$row = fix_base_url($row);
		
		// get view data
		$data = $this->_get_view_data(TRUE, $row);
		$data['row'] = $row;
		
		// template variables
		$this->view['toptitle'] = $this->view['toptitle'].' [Edit]';
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
			//get view data
			$data = $this->_get_view_data(TRUE);
			
			// template variables
			$this->view['toptitle'] = $this->view['toptitle'].' [Edit]';
			$this->view['content'] = $this->controller.'/edit';
		
			// load view
			$this->load->view('main', $data);
		}
		else
		{
			// save data
			$id = $this->input->post('id');
			$row = $this->_get_post_data(true);
			$this->model->update($id,$row);
			
			// redirect to list page
			redirect($this->controller.'/index');
		}
	}
	
	function _delete($ids){
		// delete data
		$this->model->delete($ids, $this->user['id']);
		
	}
	
	// validation rules
	function _set_rules(){
		$this->form_validation->set_rules('license_plate','License Plate','trim|required');
		$this->form_validation->set_rules('driver','Driver Name','trim');
		$this->form_validation->set_rules('published','Publish','trim|required');
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
		$html['published'] = html_yesno_radio('published', set_value('published', ($row ? $row->published : '1')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=false){
		$id = $editmode ? $this->input->post('id') : '';
		
		$date_field = "date_".($editmode ? "update" : "insert");
		$date_value = date('Y-m-d H:i:s');
		$user_field = "user_".($editmode ? "update" : "insert");
		$user_value = $this->user['id'];
		
		$image = put_base_url($this->input->post('image'));
		$row = array(
				'license_plate' => $this->input->post('license_plate'),
				'driver' => $this->input->post('driver'),
				'published' => $this->input->post('published'),
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