<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articlewidget extends CI_Controller {

	//controller main properties
	var $controller = "admin/articlewidget";
	var	$title = "article widget";
	
	function __construct(){
		parent::__construct();
		
		$this->js_list = 'list_old';
		
		// load model
		$this->load->model('articleWidgetModel','',TRUE);
		$this->model = new articleWidgetModel;
		
		//fill template parameters
		$this->view['doctitle'] = 'Manage '.ucwords($this->title);
		$this->view['icon'] = 'icon-32-message.png';
		$this->view['toptitle'] = '';
		$this->view['content'] = '';
		$this->view['wrapper'] = '';
		
		//check login
		if($this->user['logged_in']){
			if($this->user['usertype']>3) redirect('admin/area');
		}
		else{
			redirect('admin/login');
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
	
	function index($offset = 0, $article_id = '')
	{
		if($article_id!==''){
			$this->load->model('articleModel');
			$article_title = $this->articleModel->get_field_by_id($article_id, 'title');
			
			//save article id & title
			$this->session->set_userdata($this->controller.'_article_id', $article_id);
			$this->session->set_userdata($this->controller.'_article_title', $article_title);
		}
		
		//check task
		$this->task = $this->input->post('task');
		
		switch($this->task)
		{
			case 'add':
				$this->_add();
				break;
			case 'copy':
				$cid = $this->input->post('cid');
				$this->_copy($cid[0]);
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
	
	function _show($offset=0){	
		// offset
		$uri_segment = 4;
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
		
		//article_id
		$article_id = $this->session->userdata($this->controller.'_article_id');
		
		// load data
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari, $article_id)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = rtrim(site_url($this->controller.'/index/'),'.html');
 		$config['total_rows'] = $this->model->count_all($filter_cari, $article_id);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$article_title = $this->session->userdata($this->controller.'_article_title');
		$this->view['toptitle'] = $article_title." - Manage ".ucwords($this->title)." [List]";
		
		// load view
		$this->load->view('main_plain', $data);
	}
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$article_title = $this->session->userdata($this->controller.'_article_title');
		$this->view['toptitle'] = $article_title." - Manage ".ucwords($this->title)." [Add]";
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_add','Add',false);
	
		// load view
		$this->load->view('main_plain', $data);
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
			$article_title = $this->session->userdata($this->controller.'_article_title');
			$this->view['toptitle'] = $article_title." - Manage ".ucwords($this->title)." [Add]";
			$this->view['content'] = $this->controller.'/edit';
			
			// reload view
			$this->load->view('main_plain', $data);
		}
		else
		{
			// save data
			$row = $this->_get_post_data();
			$id = $this->model->save($row);
			
			if($this->input->post('task')=='new')
			{
				// redirect to list page
				$this->_add();
			}
			else
			{
				// redirect to list page
				redirect($this->controller.'/index/'.$this->_get_offset());
			}
		}
	}
	
	function _copy($id){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		// prefill form values
		$row = $this->model->get_by_id($id);
		$row->title = $row->title.' - Copy';
		
		//fix {[base_url]}
		$row = fix_base_url($row);
		
		// get view data
		$data = $this->_get_view_data(false, $row);
		$data['row'] = $row;
		
		// template variables
		$article_title = $this->session->userdata($this->controller.'_article_title');
		$this->view['toptitle'] = $article_title." - Manage ".ucwords($this->title)." [Add]";
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main_plain', $data);
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
		$article_title = $this->session->userdata($this->controller.'_article_title');
		$this->view['toptitle'] = $article_title." - Manage ".ucwords($this->title)." [Edit]";
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main_plain', $data);
	}
	
	function updateData(){
		// set validation properties
		$this->_set_rules(TRUE);
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			//get view data
			$data = $this->_get_view_data(TRUE);
			
			// template variables
			$article_title = $this->session->userdata($this->controller.'_article_title');
			$this->view['toptitle'] = $article_title." - Manage ".ucwords($this->title)." [Edit]";
			$this->view['content'] = $this->controller.'/edit';
		
			// load view
			$this->load->view('main_plain', $data);
		}
		else
		{
			// save data
			$id = $this->input->post('id');
			$row = $this->_get_post_data(TRUE);
			$this->model->update($id,$row);
			
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
		$this->load->view('main_plain', $data);
	}
	
	function _delete($cid){
		// delete data
		$this->model->delete($cid);
		
		// redirect to person list page
		redirect($this->controller.'/index/'.$this->_get_offset());
	}
	
	// validation rules
	function _set_rules($editmode=FALSE){
		$this->form_validation->set_rules('name','Widget Name','trim|required');
		$this->form_validation->set_rules('title','Title','');
		$this->form_validation->set_rules('body','Body','');
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
		
		//published
		$html['published'] = html_yesno_radio('published', set_value('published', ($row ? $row->published : '1')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$body = put_base_url($this->input->post('body'));
		$row = array(
				'article_id' => $this->session->userdata($this->controller.'_article_id'),
				'name' => $this->input->post('name'),
				'title' => $this->input->post('title'),
				'body' => $body,
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
		
		$data['title'] = $label.ucwords($this->title);
		$data['message'] = '';
		$data['action'] = site_url($this->controller.'/'.$method.'/'.$this->_get_offset());
		
		//set link_back link
		$data['link_back'] = $this->_get_index_offset();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>