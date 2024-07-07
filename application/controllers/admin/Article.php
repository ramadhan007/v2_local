<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {

	//controller main properties
	var $controller = "admin/article";
	var	$title = "artikel";
	
	function __construct(){
		parent::__construct();
		
		$this->js_list = 'list_old';
		
		// load model
		$this->load->model('articleModel','',TRUE);
		$this->model = new articleModel;
		
		//fill template parameters
		$this->view['doctitle'] = 'Kelola '.ucwords($this->title);
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
	
	function _get_parent_offset()
	{
		$offset = $this->session->userdata('admin/category_offset');
		return $offset ? $offset : "0";
	}
	
	function _get_parent_index_offset()
	{
		return site_url('admin/category/index/'.$this->_get_parent_offset());
	}
	
	function setcategory($category_id){
		$this->session->set_userdata($this->controller.'_filter_category_id', $category_id);
		redirect($this->controller);
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
			case 'back':
				redirect($this->_get_parent_index_offset());
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
		
		//filter category
		if(isset($_POST['filter_category_id'])){
			$filter_category_id = $this->input->post('filter_category_id');
			$this->session->set_userdata($this->controller.'_filter_category_id', $filter_category_id);
			$offset=0;
		}
		else{
			$filter_category_id = $this->session->userdata($this->controller.'_filter_category_id');
		}
		
		// load data
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari, $filter_category_id)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($filter_cari, $filter_category_id);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->view['toptitle'] = "Kelola ".ucwords($this->title)." [List]";
		
		//filter category_id
		$this->load->model('categoryModel');
		$array_data = $this->categoryModel->get_list($this->session->userdata('active_sekolah_id'));
		$html['filter_category_id'] = html_select('filter_category_id', $array_data, set_value('filter_category_id', $filter_category_id), '-- Semua Kategori --', 'form-control input-sm');
		$data['html'] = $html;
		
		// load view
		$this->load->view('main', $data);
	}
	
	function search($offset=0, $field_id='')
	{	
		//field id
		if($field_id!=='') $this->session->set_userdata($this->controller.'_field_id', $field_id);
		$data['field_id'] = $this->session->userdata($this->controller.'_field_id');
		
		// offset
		$uri_segment = 4;
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		//template setting
		$this->view['content'] = $this->controller.'/search';
		
		//filter cari
		if(isset($_POST['filter_cari'])){
			$filter_cari = $this->input->post('filter_cari');
			$this->session->set_userdata($this->controller.'_filter_cari', $filter_cari);
			$offset=0;
		}
		else
		{
			$filter_cari = $this->session->userdata($this->controller.'_filter_cari');
		}
		
		//filter category
		if(isset($_POST['filter_category_id'])){
			$filter_category_id = $this->input->post('filter_category_id');
			$this->session->set_userdata($this->controller.'_filter_category_id', $filter_category_id);
			$offset=0;
		}
		else
		{
			$filter_category_id = $this->session->userdata($this->controller.'_filter_category_id');
		}
		
		// load data
		$rows = $this->model->get_paged_list(4, $offset, $filter_cari, $filter_category_id)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/search/');
 		$config['total_rows'] = $this->model->count_all($filter_cari, $filter_category_id);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = 4;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//filter category_id
		$this->load->model('categoryModel');
		$array_data = $this->categoryModel->get_list($this->session->userdata('active_sekolah_id'));
		$html['filter_category_id'] = html_select('filter_category_id', $array_data, set_value('filter_category_id', $filter_category_id), 'All Categories', 'form-control');
		$data['html'] = $html;
		
		// load view
		$this->load->view('main_plain', $data);
	}
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$this->view['toptitle'] = "Kelola ".ucwords($this->title)." [Add]";
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
			$this->view['toptitle'] = ucwords($this->title)." [Add]";
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
	
	function edit($id){
		// save $id as session for next use
		$this->session->set_userdata($this->controller.'_id', $id);
		
		// prefill form values
		$row = $this->model->get_by_id($id);
		
		//image & widget count
		$row->image = $this->model->count_image($id);
		$row->widget = $this->model->count_widget($id);
		
		//fix {[base_url]}
		$row = fix_base_url($row);
		
		// get view data
		$data = $this->_get_view_data(TRUE, $row);
		$data['row'] = $row;
		
		// template variables
		$this->view['toptitle'] = "Kelola ".ucwords($this->title)." [Edit]";
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main', $data);
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
			$this->view['toptitle'] = ucwords($this->title)." [Edit]";
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
	
	function _delete($cid){
		// delete data
		$this->model->delete($cid);
		
		// redirect to person list page
		redirect($this->controller.'/index/'.$this->_get_offset());
	}
	
	// validation rules
	function _set_rules($editmode=FALSE){
		$this->form_validation->set_rules('category_id','Category','');
		$this->form_validation->set_rules('title','Title','trim|required');
		$this->form_validation->set_rules('alias','Alias','trim');
		$this->form_validation->set_rules('body','Body','');
		$this->form_validation->set_rules('meta_keywords','Meta Keywords','');
		$this->form_validation->set_rules('meta_description','Meta Description','');
		$this->form_validation->set_rules('published','Tampil','trim|required');
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
		
		//category_id
		$this->load->model('categoryModel');
		$array_data = $this->categoryModel->get_list($this->session->userdata('active_sekolah_id'));
		$def_category_id = $this->session->userdata($this->controller.'_filter_category_id');
		$html['category_id'] = html_select_multiple('category_id', $array_data, set_value('category_id', ($row ? $row->category_id : $def_category_id)), '', 'form-control');
		
		//body
		$html['body'] = html_ckeditor('body', set_value('body', ($row ? $row->body : '')),'form-control','auto','300px');
		
		//meta enable
		$html['meta_enable'] = html_yesno_radio('meta_enable', set_value('meta_enable', ($row ? $row->meta_enable : '0')));
		
		//published
		$html['published'] = html_yesno_radio('published', set_value('published', ($row ? $row->published : '1')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$id = $editmode ? $this->input->post('id') : '';
		
		$category_id = $this->input->post('category_id');
		$category_id = $category_id ? implode(',',$category_id) : "";
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		
		$alias = $alias ? get_join_unique_slug("article",$alias,$id) :  get_join_unique_slug("article",$title,$id);
		
		$body = put_base_url($this->input->post('body'));
		
		$ar_body = explode('{[readmore]}',$body);
		$intro = count($ar_body)>1 ? $this->_closetags($ar_body[0]) : "";
		
		$login_data = $this->session->userdata('login_data_admin');
		$date_field = "date_".($editmode ? "update" : "insert");
		$date_value = date('Y-m-d H:i:s');
		$user_field = "user_".($editmode ? "update" : "insert");
		$user_value = $login_data['user']->id;
		
		$row = array(
				'category_id' => $category_id,
				'title' => $title,
				'alias' => $alias,
				'intro' => $intro,
				'body' => $body,
				'meta_enable' => $this->input->post('meta_enable'),
				'meta_keywords' => $this->input->post('meta_keywords'),
				'meta_description' => $this->input->post('meta_description'),
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
		
		$data['title'] = $label.ucwords($this->title);
		$data['message'] = '';
		$data['action'] = site_url($this->controller.'/'.$method.'/'.$this->_get_offset());
		
		//set link_back link
		$data['link_back'] = $this->_get_index_offset();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
	
	function _closetags ( $html )
        {
        #put all opened tags into an array
        preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
        $openedtags = $result[1];
        #put all closed tags into an array
        preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
        $closedtags = $result[1];
        $len_opened = count ( $openedtags );
        # all tags are closed
        if( count ( $closedtags ) == $len_opened )
        {
        return $html;
        }
        $openedtags = array_reverse ( $openedtags );
        # close tags
        for( $i = 0; $i < $len_opened; $i++ )
        {
            if ( !in_array ( $openedtags[$i], $closedtags ) )
            {
            $html .= "</" . $openedtags[$i] . ">";
            }
            else
            {
            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
            }
        }
        return $html;
    }
}

?>