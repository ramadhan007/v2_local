<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'menu', 'menu', true);
		
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
			case 'editchild':
				$cid = $this->input->post('cid');
				redirect($this->config->item("admin").'/menuitem/index/0/'.$cid[0]);
				break;
			default:
				$this->_show($offset);
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
		
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [List]';
		
		// load view
		$this->load->view('main', $data);
	}
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Add]';
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
			$row = $this->_get_post_data(TRUE);
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
		$this->form_validation->set_rules('title','Title','trim|required');
		$this->form_validation->set_rules('alias','Alias','trim');
		$this->form_validation->set_rules('usertype','Level Akses','required');
		$this->form_validation->set_rules('published','Published','trim|required');
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
		
		//tipe user
		$this->load->model('userModel');
		$array_data = $this->userModel->get_list_usertype($this->user['usertype']);
		$html['usertype'] = html_select('usertype', $array_data, set_value('usertype', ($row ? $row->usertype : '')));
		
		//published
		$array_data = array(
			'0' => array('value' => '1', 'text' => 'Ya'),
			'1' => array('value' => '0', 'text' => 'No'),
			);
		$html['published'] = html_radio('published', $array_data, set_value('published', ($row ? $row->published : '1')), '', FALSE);
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$id = $editmode ? $this->input->post('id') : '';
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		$alias = $alias ?  get_unique_slug('tb_menu','alias',$alias,$id) :  get_unique_slug('tb_menu','alias',$title,$id);
		
		$row = array('title' => $title,
				'alias' => $alias,
				'usertype' => $this->input->post('usertype'),
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