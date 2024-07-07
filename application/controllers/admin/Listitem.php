<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listitem extends Admin_Controller {
	
	function __construct(){
		parent::__construct(__FILE__, '', 'listitem',false);
		$this->is_modal = true;
	}
	
	function setcatid($list_cat_id){
		$this->load->model('listcatModel');
		$row = $this->listcatModel->get_field_by_id($list_cat_id, 'name, type');
		$list_cat_name = $row->name;
		$list_cat_type = $row->type;
		
		//save list_cat id & name
		$this->session->set_userdata($this->controller.'_list_cat_id', $list_cat_id);
		$this->session->set_userdata($this->controller.'_list_cat_name', $list_cat_name);
		$this->session->set_userdata($this->controller.'_list_cat_type', $list_cat_type);
		
		redirect($this->controller);
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
			case 'back':
				redirect($this->_get_parent_index_offset());
				break;
			default:
				$this->_show();
		}
	}
	
	function _show($offset=''){	
		// offset
		$uri_segment = 5;
		if($offset==''){
			$offset = $this->session->userdata($this->controller.'_offset');
			$offset = $offset!='' ? $offset : 0;
		}
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		
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
		
		$list_cat_name = $this->session->userdata($this->controller.'_list_cat_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." $list_cat_name [List]";
		
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
			// $tbody = str_replace("'","\'",$tbody);
			echo $tbody;
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
				echo "Showing ".($offset+1)." to ".($offset+$numrows)." of ".$totalrows." entries";
			}else{
				echo "No record";
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
		$list_cat_name = $this->session->userdata($this->controller.'_list_cat_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." $list_cat_name [Add]";
		$this->view['content'] = $this->controller.'/edit';
	
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
			$list_cat_name = $this->session->userdata($this->controller.'_list_cat_name');
			$this->view['toptitle'] = ucwords($this->title)." $list_cat_name [Add]";
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
				redirect($this->controller.'/index');
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
		$list_cat_name = $this->session->userdata($this->controller.'_list_cat_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." $list_cat_name [Edit]";
		$this->view['content'] = $this->controller.'/edit';
		
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
			$list_cat_name = $this->session->userdata($this->controller.'_list_cat_name');
			$this->view['toptitle'] = ucwords($this->title)." $list_cat_name [Edit]";
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
			
			redirect($this->controller.'/index');
		}
	}
	
	function _delete($cid){
		// delete data
		$this->model->delete($cid);
	}
	
	// validation rules
	function _set_rules($editmode=FALSE){
		$this->form_validation->set_rules('text','Text','trim|required');
		$this->form_validation->set_rules('short','Singkatan','trim');
		$this->form_validation->set_rules('val','Nilai','trim');
		$this->form_validation->set_rules('val_min','Nilai Min','trim');
		$this->form_validation->set_rules('val_max','Nilai Max','trim');
		$this->form_validation->set_rules('icon','Icon','');
		$this->form_validation->set_rules('class','Class','');
	}
	
	function _get_html($row=array())
	{
		//prepare select/radio html
		$html = array();
		
		//class
		$array_data = array(
				'0' => array('value' => 'default', 'text' => 'default'),
				'1' => array('value' => 'primary', 'text' => 'primary'),
				'2' => array('value' => 'success', 'text' => 'success'),
				'3' => array('value' => 'info', 'text' => 'info'),
				'4' => array('value' => 'warning', 'text' => 'warning'),
				'5' => array('value' => 'danger', 'text' => 'danger'),
			);
		$html['class'] = html_select('class', $array_data, set_value('class', ($row ? $row->class : 'class')), 'None');
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$row = array(
				'list_cat_id' => $this->session->userdata($this->controller.'_list_cat_id'),
				'text' => $this->input->post('text'),
				'short' => ($this->input->post('short')!=='' ? $this->input->post('short') : $this->input->post('text')),
				'val' => $this->input->post('val')!=='' ? $this->input->post('val') : $this->input->post('text'),
				'val_min' => $this->input->post('val_min'),
				'val_max' => $this->input->post('val_max'),
				'icon' => $this->input->post('icon'),
				'class' => $this->input->post('class'),
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
		$data['link_back'] = site_url($this->controller.'/index');
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>
