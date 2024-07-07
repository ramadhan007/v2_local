<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menuitem extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, "menu item", 'menuitem');
	}
	
	function _get_index()
	{
		return site_url($this->controller);
	}
	
	function _get_parent_offset()
	{
		$offset = $this->session->userdata($this->config->item("admin").'/menu_offset');
		return $offset ? $offset : "0";
	}
	
	function _get_parent_index_offset()
	{
		return site_url($this->config->item("admin").'/menu/index/'.$this->_get_parent_offset());
	}
	
	function index($menu_id = 0)
	{
		if(!$this->continue) return;
		if($menu_id)
		{
			$this->load->model('menuModel');
			$menu_title = $this->menuModel->get_field_by_id($menu_id, 'title');
			
			//save menu id & title
			$this->session->set_userdata($this->controller.'_menu_id', $menu_id);
			$this->session->set_userdata($this->controller.'_menu_title', $menu_title);
		}
		else
		{
			$menu_id = $this->session->userdata($this->controller.'_menu_id');
			$menu_title = $this->session->userdata($this->controller.'_menu_title');
		}
		
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
				$this->_show();
		}
	}
	
	function _show(){	
		// offset
		$uri_segment = 4;
		$offset = $this->session->userdata($this->controller.'_offset');
		$offset = $offset!='' ? $offset : 0;
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb($this->_get_index(),ucwords($this->title),false);
		
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
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari);
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
		
		$menu_title = $this->session->userdata($this->controller.'_menu_title');
		$this->view['toptitle'] = "Kelola ".ucwords($this->title)." $menu_title [List]";
		
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
			$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari);
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
			$numrows = count($this->model->get_paged_list($this->limit, $offset, $filter_cari));
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
		$menu_title = $this->session->userdata($this->controller.'_menu_title');
		$this->view['toptitle'] = "Kelola ".ucwords($this->title)." $menu_title [Add]";
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
			$menu_title = $this->session->userdata($this->controller.'_menu_title');
			$this->view['toptitle'] = ucwords($this->title)." $menu_title [Add]";
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
				// redirect to list page
				$this->_add();
			}
			else
			{
				// redirect to list page
				redirect($this->controller);
			}
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
		$menu_title = $this->session->userdata($this->controller.'_menu_title');
		$this->view['toptitle'] = "Kelola ".ucwords($this->title)." $menu_title [Edit]";
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
			$menu_title = $this->session->userdata($this->controller.'_menu_title');
			$this->view['toptitle'] = ucwords($this->title)." $menu_title [Edit]";
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
			
			redirect($this->controller);
		}
	}
	
	function view($id){
		// set common properties
		$data['title'] = 'Detail '.$this->title;
		$data['link_back'] = $this->_get_index();
		
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
	}
	
	// validation rules
	function _set_rules($editmode=FALSE){
		$this->form_validation->set_rules('title','Menu Item','trim|required');
		$this->form_validation->set_rules('alias','Alias','trim');
		$this->form_validation->set_rules('icon','Icon','');
		$this->form_validation->set_rules('link','Link','');
		$this->form_validation->set_rules('usertype','Level Akses','required');
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
		
		//parent_id
		$array_data = $this->model->get_list(array(array('value' => '0', 'text' => 'Top')), true);
		$html['parent_id'] = html_select('parent_id', $array_data, set_value('parent_id', ($row ? $row->parent_id : '0')), '', 'form-control');
		
		//tipe user
		$this->load->model('userModel');
		$array_data = $this->userModel->get_list_usertype($this->user['usertype']);
		$html['usertype'] = html_select('usertype', $array_data, set_value('usertype', ($row ? $row->usertype : '')));
		
		//published
		$html['published'] = html_yesno_radio('published', '1');
		
		//ordering
		$id = $this->session->userdata($this->controller.'_id');
		if($id){
			$array_data = $this->model->get_list_ordering($id);
			$ordering = $this->model->get_ordering($id);
			$html['ordering'] = html_select('ordering', $array_data, set_value('ordering', ($row ? $row->ordering : $ordering)), '', 'form-control');
		}
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$id = $editmode ? $this->input->post('id') : '';
		$parent_id = $this->input->post('parent_id');
		
		if($editmode){
			$old_parent_id = $this->model->get_field_by_id($id,'parent_id');
			if($old_parent_id==$parent_id){
				$new_ordering = $this->input->post('ordering');
				$ordering = $this->model->update_get_ordering($id, $new_ordering);
			}
			else{
				$ordering = $this->model->get_max_ordering($parent_id)+1;
			}
		}
		else{
			$ordering = $this->model->get_max_ordering($parent_id)+1;
		}
		
		$title = $this->input->post('title');
		$alias = $this->input->post('alias');
		
		$alias = $alias ?  get_unique_slug('tb_menu_item','alias',$alias,$id) :  get_unique_slug('tb_menu_item','alias',$title,$id);
		
		$link = $this->input->post('link');
		$base_controller = '';
		if($link){
			$ar_link = explode('/', rtrim(ltrim($link,'/'),'/'));
			if(count($ar_link)>2){
				while( !file_exists(APPPATH.'controllers/'.implode('/', $ar_link).'.php') ){
					array_pop($ar_link);
				}
				if(count($ar_link)) $base_controller = implode('/', $ar_link);
			}else{
				$base_controller = $link;
			}
		}
		
		$row = array(
				'menu_id' => $this->session->userdata($this->controller.'_menu_id'),
				'parent_id' => $parent_id,
				'title' => $title,
				'alias' => $alias,
				'icon' => $this->input->post('icon'),
				'link' => $link,
				'base_controller' => $base_controller,
				'ordering' => $ordering,
				'usertype' => $this->input->post('usertype'),
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
		$data['action'] = site_url($this->controller.'/'.$method);
		
		//set link_back link
		$data['link_back'] = $this->_get_index();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>
