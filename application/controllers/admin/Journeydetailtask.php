<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JourneyDetailTask extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'journey detail task', 'journeydetailtask', false, true, true);
		// $this->is_modal = true;
	}
	
	function _get_index_offset()
	{
		return site_url($this->controller);
	}
	
	function _get_parent_offset()
	{
		$offset = $this->session->userdata('admin/listcat_offset');
		return $offset ? $offset : "0";
	}
	
	function _get_parent_index_offset()
	{
		return site_url('admin/listcat/index/'.$this->_get_parent_offset());
	}
	
	function setparentid($parent_id){
		$parent_name = get_val("select name from tb_journey_detail where id = '$parent_id'");
		
		//save list_cat id & name
		$this->session->set_userdata($this->controller.'_parent_id', $parent_id);
		$this->session->set_userdata($this->controller.'_parent_name', $parent_name);
		
		//reset offset
		$this->session->set_userdata($this->controller.'_offset', 0);
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
			case 'copy':
				$cid = $this->input->post('cid');
				$this->_copy($cid[0]);
				break;
			case 'delete':
				$cid = $this->input->post('cid');
				$this->_delete($cid);
				break;
			case 'back':
				redirect($this->_get_parent_index_offset());
				break;
			case 'orderup':
			case 'orderdown':
				$cid = $this->input->post('cid');
				$this->_order($cid[0]);
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
		set_breadcrumb($this->_get_index_offset(),ucwords($this->title),false);
		
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
		
		$parent_name = $this->session->userdata($this->controller.'_parent_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." : $parent_name [List]";
		
		//filter_limit
		$html['filter_limit'] = get_filter_limit($this->limit);
		$data['html'] = $html;
		
		// load view
		$this->load->view("main", $data);
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
	
	function _order($id){
		if($this->task=='orderup'){
			$this->model->order_up($id);
		}else{
			$this->model->order_down($id);
		}
		// redirect to list page
		redirect($this->controller.'/index');
	}
	
	function _add(){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$parent_name = $this->session->userdata($this->controller.'_parent_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." : $parent_name [Add]";
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_add','Tambah',false);
	
		// load view
		$this->load->view("main", $data);
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
			$parent_name = $this->session->userdata($this->controller.'_parent_name');
			$this->view['toptitle'] = ucwords($this->title)." $parent_name [Add]";
			$this->view['content'] = $this->controller.'/edit';
			
			// reload view
			$this->load->view("main", $data);
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
		$parent_name = $this->session->userdata($this->controller.'_parent_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." : $parent_name [Edit]";
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view("main", $data);
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
			$parent_name = $this->session->userdata($this->controller.'_parent_name');
			$this->view['toptitle'] = ucwords($this->title)." $parent_name [Edit]";
			$this->view['content'] = $this->controller.'/edit';
		
			// load view
			$this->load->view("main", $data);
		}
		else
		{
			// save data
			$id = $this->input->post('id');
			$row = $this->_get_post_data(TRUE);
			
			//fix CRLF into LF only
			
			/* $array = str_split($row['response']);
			foreach ($array as $char) {
				$ascii = ord($char);
				if($ascii==13 || $ascii==10){
					echo "[$ascii]";
				}else{
					echo $char;
				}
			}
			exit();
			*/
			
			$this->model->update($id,$row);
			
			redirect($this->controller.'/index');
		}
	}
	
	function _copy($id){
		$row = get_row_array("select * from ".$this->model->get_table()." where id = '$id'");
		unset($row["id"]);
		$row["ordering"] = $this->model->get_max_ordering()+1;
		
		$id = $this->model->save($row);
		
		// redirect to list page
		redirect($this->controller.'/index');
	}
	
	function _delete($cid){
		// delete data
		$this->model->delete($cid);
	}
	
	// validation rules
	function _set_rules($editmode=FALSE){
		$this->form_validation->set_rules('name','Name','trim|required');
		$this->form_validation->set_rules('type','Type','trim|required');
		$this->form_validation->set_rules('find_by','Find By','trim');
		$this->form_validation->set_rules('element_name','Element Name','trim');
		$this->form_validation->set_rules('content','Content','trim');
		$this->form_validation->set_rules('content_ios','Content iOS','trim');
		$this->form_validation->set_rules('handler','Handler','trim');
		$this->form_validation->set_rules('timeout','Timeout','trim');
		$this->form_validation->set_rules('action','Action','trim');
		$this->form_validation->set_rules('wait','Wait','trim');
		$this->form_validation->set_rules('input','Input','');
		$this->form_validation->set_rules('start_timer','Start Timer','trim');
		$this->form_validation->set_rules('start_timer_when','Start Timer When','trim');
		$this->form_validation->set_rules('end_timer','End Timer','trim');
		$this->form_validation->set_rules('end_timer_when','End Timer When','trim');
		$this->form_validation->set_rules('record_param','Record Parameter','trim');
		$this->form_validation->set_rules('record_param_when','Record Parameter When','trim');
		$this->form_validation->set_rules('upload','Upload Detail Journey','trim');
		$this->form_validation->set_rules('upload_data','Data to Upload','trim');
		$this->form_validation->set_rules('upload_when','Upload When','trim');
		$this->form_validation->set_rules('platform','Platform','trim');
		$this->form_validation->set_rules('condition','Custom WHERE Condition','');
		$this->form_validation->set_rules('published','Published','trim');
		if($editmode) $this->form_validation->set_rules('ordering','Ordering','trim');
	}
	
	function _get_html($row=array())
	{
		//prepare select/radio html
		$html = array();
		
		//type
		$array_data = get_list_item('journey_detail_task_type');
		$html['type'] = html_select('type', $array_data, set_value('type', ($row ? $row->type : '')),'-- Type --');
		
		//find_by
		$array_data = get_list_item('journey_detail_task_find_by');
		$html['find_by'] = html_select('find_by', $array_data, set_value('find_by', ($row ? $row->find_by : '')),'-- Find By --');
		
		//handler
		$array_data = get_list_item('journey_detail_task_handler');
		$html['handler'] = html_select('handler', $array_data, set_value('handler', ($row ? $row->handler : '')),'-- Handler --');
		
		//action
		$array_data = get_list_item('journey_detail_task_action');
		$html['action'] = html_select('action', $array_data, set_value('action', ($row ? $row->action : '')),'-- Action --');
		
		//start_timer_when
		$array_data = get_list_item('journey_detail_task_when');
		$html['start_timer_when'] = html_select('start_timer_when', $array_data, set_value('start_timer_when', ($row ? $row->start_timer_when : '')),'-- Select When --');
		
		//end_timer_when
		$array_data = get_list_item('journey_detail_task_when');
		$html['end_timer_when'] = html_select('end_timer_when', $array_data, set_value('end_timer_when', ($row ? $row->end_timer_when : '')),'-- Select When --');
		
		//record_param
		$array_data = get_list_item('option_yes_no');
		$html['record_param'] = html_select('record_param', $array_data, set_value('record_param', ($row ? $row->record_param : '0')));
		
		//record_param_when
		$array_data = get_list_item('journey_detail_task_when');
		$html['record_param_when'] = html_select('record_param_when', $array_data, set_value('record_param_when', ($row ? $row->record_param_when : '')),'-- Select When --');
		
		//upload
		$array_data = get_list_item('option_yes_no');
		$html['upload'] = html_select('upload', $array_data, set_value('upload', ($row ? $row->upload : '0')));
		
		//upload_when
		$array_data = get_list_item('journey_detail_task_when');
		$html['upload_when'] = html_select('upload_when', $array_data, set_value('upload_when', ($row ? $row->upload_when : '')),'-- Select When --');
		
		//platform
		$array_data = get_list_item('platform');
		$html['platform'] = html_select('platform', $array_data, set_value('platform', ($row ? $row->platform : '')),'All');
		
		//published
		$array_data = get_list_item('published');
		$html['published'] = html_select('published', $array_data, set_value('published', ($row ? $row->published : '1')));
		
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
		if($editmode){
			$new_ordering = $this->input->post('ordering');
			$ordering = $this->model->update_get_ordering($id, $new_ordering);
		}
		else{
			$ordering = $this->model->get_max_ordering()+1;
		}
		
		$row = array(
				'journey_detail_id' => $this->session->userdata($this->controller.'_parent_id'),
				'name' => $this->input->post('name'),
				'type' => $this->input->post('type'),
				'find_by' => $this->input->post('find_by'),
				'element_name' => $this->input->post('element_name'),
				'content' => $this->input->post('content'),
				'content_ios' => $this->input->post('content_ios'),
				'handler' => $this->input->post('handler'),
				'timeout' => $this->input->post('timeout'),
				'action' => $this->input->post('action'),
				'wait' => $this->input->post('wait'),
				'input' => $this->input->post('input'),
				'start_timer' => $this->input->post('start_timer'),
				'start_timer_when' => $this->input->post('start_timer_when'),
				'end_timer' => $this->input->post('end_timer'),
				'end_timer_when' => $this->input->post('end_timer_when'),
				'record_param' => $this->input->post('record_param'),
				'record_param_when' => $this->input->post('record_param_when'),
				'upload' => $this->input->post('upload'),
				'upload_data' => $this->input->post('upload_data'),
				'upload_when' => $this->input->post('upload_when'),
				'platform' => $this->input->post('platform'),
				'condition' => $this->input->post('condition'),
				'published' => $this->input->post('published'),
				'ordering' => $ordering,
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
