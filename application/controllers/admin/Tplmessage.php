<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tplmessage extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'template pesan', 'tplmessage');
		
		$this->js_list = 'list_old';
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
	
	function index($offset = 0)
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
	
	function _show($offset = 0){	
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb($this->_get_index_offset(),ucwords($this->title),true);
		
		$reset_offset = FALSE;
		
		//filter cari
		if(isset($_POST['filter_cari']))
		{
			$filter_cari = $this->input->post('filter_cari');
			if($filter_cari!=$this->session->userdata($this->controller.'_filter_cari')) $reset_offset = TRUE;
			$this->session->set_userdata($this->controller.'_filter_cari', $filter_cari);
		}
		else
		{
			$filter_cari = $this->session->userdata($this->controller.'_filter_cari');
		}
		
		if($reset_offset) redirect($this->controller.'/index');
		
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
		
		$this->view['toptitle'] = 'Kelola Template Pesan [List]';
		$data['numrows'] = count($rows);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$this->view['toptitle'] = 'Kelola Template Pesan [Add]';
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_add','Tambah',false);
	
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
			$this->view['toptitle'] = 'Kelola Template Pesan [Add]';
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
		$this->view['toptitle'] = 'Kelola Template Pesan [Edit]';
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
			$this->view['toptitle'] = 'Kelola Template Pesan [Edit]';
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
		$data['row'] = $this->model->get_by_id($id);
		
		// template variables
		$this->view['content'] = $this->controller.'/view';
		set_breadcrumb('admintplmessage_view',$data['title'],false);
		
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
		$this->form_validation->set_rules('tag','Tag Template','trim|required');
		$this->form_validation->set_rules('title','Message Title','trim|required');
		$this->form_validation->set_rules('body','Message Body','trim|required');
		
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
		
		//body
		$html['body'] = html_ckeditor('body', set_value('body', ($row ? $row->body : '')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data(){
		$row = array('tag' => $this->input->post('tag'),
				'title' => $this->input->post('title'),
				'body' => $this->input->post('body')
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
