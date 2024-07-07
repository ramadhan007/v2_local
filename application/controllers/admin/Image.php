<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends CI_Controller {

	//controller main properties
	var $controller = "admin/image";
	var	$title = "images";
	
	// num of records per page
	var $limit = 8;
	
	var	$view = array();
	var	$user = array();
	
	function __construct(){
		parent::__construct();
		
		// load library
		$this->load->model('imageModel','',TRUE);
		$this->model = new imageModel;
		
		$this->view['doctitle'] = 'Kelola '.ucwords($this->title);
		
		//check login
		if($this->user['logged_in']){
			if(!check_user_access($this->user['id'], $this->controller)){
				$this->session->set_userdata('noaccess_menu', site_url($this->controller));
				redirect('admin/dashboard/noaccess');
			}
		}
		else{
			redirect('admin/login');
		}
	}
	
	function _get_index()
	{
		return site_url($this->controller.'/index');
	}
	
	function setname($name=''){
		$this->session->set_userdata($this->controller.'_name', $name);
		redirect($this->controller);
	}
	
	function index()
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
		$rows = $this->model->get_paged_list($this->user, $this->limit, $offset, $filter_cari)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($this->user, $filter_cari);
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
			$rows = $this->model->get_paged_list($this->user, $this->limit, $offset, $filter_cari)->result();
			$data['rows'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody', $data, true);
			echo str_replace("'","\'",$tbody);
		}
		elseif($param=='pagin'){
			// generate pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url($this->controller.'/index/');
			$config['total_rows'] = $this->model->count_all($this->user, $filter_cari);
			$data['total_rows'] = $config['total_rows'];
			$config['per_page'] = $this->limit;
			$config['cur_page'] = $offset;
			$config['uri_segment'] = $uri_segment;
			
			//initialize pagination
			$this->pagination->initialize($config);
			echo $this->pagination->create_links();
		}
		elseif($param=='paginfo'){
			$numrows = count($this->model->get_paged_list($this->user, $this->limit, $offset, $filter_cari)->result());
			$totalrows = $this->model->count_all($this->user, $filter_cari);
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
			
			if($this->input->post('task')=='new')
			{
				// redirect to form page
				$this->_add();
			}
			else
			{
				// redirect to list page
				redirect($this->controller.'/index');
			}
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
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Edit]';
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function updateData(){
		// get edited id
		$id = $this->input->post('id');
		
		// set validation properties
		$this->_set_rules(TRUE);
		
		if ($this->form_validation->run() == FALSE)
		{
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
			redirect($this->controller.'/index');
		}
	}
	
	function _delete($id){
		// delete data
		$this->model->delete($id);
	}
	
	// validation rules
	function _set_rules($editmode=FALSE){
		if(!$this->session->userdata($this->controller.'_name')) $this->form_validation->set_rules('name','Image Name','trim|required');
		$this->form_validation->set_rules('title','Title','');
		$this->form_validation->set_rules('path','Path','trim|required');
		$this->form_validation->set_rules('description','Description','');
		$this->form_validation->set_rules('link','Link','');
		$this->form_validation->set_rules('show_caption','Show Caption','');
		$this->form_validation->set_rules('published','Published','trim|required');
	}
	
	function _get_html($row=array())
	{
		//prepare select/radio html
		$html = array();
		
		//show caption
		$html['show_caption'] = html_yesno_radio('show_caption', set_value('show_caption', ($row ? $row->show_caption : '0')));
		
		//published
		$html['published'] = html_yesno_radio('published', set_value('published', ($row ? $row->published : '1')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$path = put_base_url($this->input->post('path'));
		$sekolah_id = $this->session->userdata('active_sekolah_id');
		$name = $this->session->userdata($this->controller.'_name') ? $this->session->userdata($this->controller.'_name') : $this->input->post('name');
		$row = array(
				'sekolah_id' => $sekolah_id,
				'name' => $name,
				'title' => $this->input->post('title'),
				'path' => $path,
				'description' => $this->input->post('description'),
				'link' => $this->input->post('link'),
				'show_caption' => $this->input->post('show_caption'),
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
		$data['action'] = site_url($this->controller.'/'.$method);
		
		//set link_back link
		$data['link_back'] = $this->_get_index();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>