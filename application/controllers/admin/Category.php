<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

	//controller main properties
	var $controller = "admin/category";
	var	$title = "kategori konten";
	
	function __construct(){
		parent::__construct();
		
		$this->js_list = 'list_old';
		
		// load model
		$this->load->model('categoryModel','',TRUE);
		$this->model = new categoryModel;
		
		//fill template parameters
		$this->view['doctitle'] = 'Kelola '.ucwords($this->title);
		$this->view['icon'] = '';
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
	
	function index($offset = 0)
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
			case 'editchild':
				$cid = $this->input->post('cid');
				redirect('admin/article/index/0/'.$cid[0]);
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
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari);
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
			$row['sekolah_id'] = $this->session->userdata('active_sekolah_id');
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
	function _set_rules($editmode=false){
		$this->form_validation->set_rules('parent_id','','');
		$this->form_validation->set_rules('title','Nama Menu','trim|required');
		$this->form_validation->set_rules('alias','Alias','trim');
		$this->form_validation->set_rules('icon','','');
		$this->form_validation->set_rules('body','','');
		$this->form_validation->set_rules('thumb','','');
		$this->form_validation->set_rules('meta_enable','','');
		$this->form_validation->set_rules('meta_keywords','','');
		$this->form_validation->set_rules('meta_description','','');
		$this->form_validation->set_rules('per_page','Article Per Page','integer');
		$this->form_validation->set_rules('image_per_page','Image Per Page','integer');
		$this->form_validation->set_rules('widget_per_page','Widget Per Page','integer');
		$this->form_validation->set_rules('published','Ditampilkan','trim|required');
		if($editmode) $this->form_validation->set_rules('ordering','Urutan','trim|required');
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
		
		$sekolah_id = $this->session->userdata('active_sekolah_id');
		
		//parent_id
		$array_data = $this->model->get_list($sekolah_id, array(array('value' => '0', 'text' => 'Top')), true);
		$html['parent_id'] = html_select('parent_id', $array_data, set_value('parent_id', ($row ? $row->parent_id : '0')), '', 'form-control');
		
		//body
		$html['body'] = html_ckeditor('body', set_value('body', ($row ? $row->body : '')),'form-control','auto','200px');
		
		//meta_enable
		$html['meta_enable'] = html_yesno_radio('meta_enable', set_value('meta_enable', ($row ? $row->meta_enable : '0')));
		
		//published
		$html['published'] = html_yesno_radio('published', set_value('published', ($row ? $row->published : '1')));
		
		//ordering
		$id = $this->session->userdata($this->controller.'_id');
		if($id){
			$array_data = $this->model->get_list_ordering($sekolah_id, $id);
			$ordering = $this->model->get_ordering($id);
			$html['ordering'] = html_select('ordering', $array_data, set_value('ordering', ($row ? $row->ordering : $ordering)), '', 'form-control');
		}
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$id = $editmode ? $this->input->post('id') : '';
		
		$sekolah_id = $this->session->userdata('active_sekolah_id');
		
		$parent_id = $this->input->post('parent_id');
		if($editmode){
			$old_parent_id = $this->model->get_field_by_id($id,'parent_id');
			if($old_parent_id==$parent_id){
				$new_ordering = $this->input->post('ordering');
				$ordering = $this->model->update_get_ordering($id, $new_ordering);
			}
			else{
				$ordering = $this->model->get_max_ordering($sekolah_id, $parent_id)+1;
			}
		}
		else{
			$ordering = $this->model->get_max_ordering($parent_id)+1;
		}
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		
		$alias = $alias ?  get_join_unique_slug("category",$alias,$id) :  get_join_unique_slug("category",$title,$id);
		
		$body = put_base_url($this->input->post('body'));
		$thumb = put_base_url($this->input->post('thumb'));
		
		$login_data = $this->session->userdata('login_data_admin');
		$date_field = "date_".($editmode ? "update" : "insert");
		$date_value = date('Y-m-d H:i:s');
		$user_field = "user_".($editmode ? "update" : "insert");
		$user_value = $login_data['user']->id;
		
		$row = array('parent_id' => $parent_id,
				'title' => $title,
				'alias' => $alias,
				'icon' => $this->input->post('icon'),
				'body' => $body,
				'thumb' => $thumb,
				'meta_enable' => $this->input->post('meta_enable'),
				'meta_keywords' => $this->input->post('meta_keywords'),
				'meta_description' => $this->input->post('meta_description'),
				'per_page' => intval($this->input->post('per_page')),
				'image_per_page' => intval($this->input->post('image_per_page')),
				'widget_per_page' => intval($this->input->post('widget_per_page')),
				'ordering' => $ordering,
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
		$data['action'] = site_url($this->controller.'/'.$method.'/'.$this->_get_offset());
		
		//set link_back link
		$data['link_back'] = $this->_get_index_offset();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>