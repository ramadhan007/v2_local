<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apmclient extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'apm clients', 'apmclient');
	}
	
	function _get_index()
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
		set_breadcrumb($this->_get_index(),ucwords($this->title),true);
		
		//filter_cari
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
		
		//filter_published
		$filter_published_old = $this->session->userdata($this->controller.'_filter_published');
		if(isset($_POST['filter_published'])){
			$filter_published = $this->input->post('filter_published');
			if(strlen($filter_published)!=strlen($filter_published_old)){
				$this->session->set_userdata($this->controller.'_filter_published', $filter_published);
				$offset=0;
			}elseif($filter_published!=$filter_published_old){
				$this->session->set_userdata($this->controller.'_filter_published', $filter_published);
				$offset=0;
			}
		}
		else{
			$filter_published = $this->session->userdata($this->controller.'_filter_published');
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata($this->controller.'_filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata($this->controller.'_filter_location_id', $filter_location_id);
				$offset=0;
			}
		}
		else{
			$filter_location_id = $this->session->userdata($this->controller.'_filter_location_id');
		}
		
		//filter_operator_id
		$filter_operator_id_old = $this->session->userdata($this->controller.'_filter_operator_id');
		if(isset($_POST['filter_operator_id'])){
			$filter_operator_id = $this->input->post('filter_operator_id');
			if($filter_operator_id!=$filter_operator_id_old){
				$this->session->set_userdata($this->controller.'_filter_operator_id', $filter_operator_id);
				$offset=0;
			}
		}
		else{
			$filter_operator_id = $this->session->userdata($this->controller.'_filter_operator_id');
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
		
		$filters = array();
		$filters['cari'] = $filter_cari;
		$filters['published'] = $filter_published;
		$filters['location_id'] = $filter_location_id;
		$filters['operator_id'] = $filter_operator_id;
		
		// load data
		$rows = $this->model->get_paged_list($this->limit, $offset, $filters)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($filters);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['cur_page'] = $offset;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$location_name = $this->session->userdata($this->controller.'_location_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." $location_name [List]";
		
		//filter_limit
		$html['filter_limit'] = get_filter_limit($this->limit);
		
		$sql = "select id, name from tb_location where published order by name";
		$array_data = get_array($sql);
		$html['filter_location_id'] = html_select('filter_location_id', $array_data, set_value('filter_location_id', $filter_location_id), 'Location', 'form-control');
		
		$sql = "select id, name from tb_operator where published order by name";
		$array_data = get_array($sql);
		$html['filter_operator_id'] = html_select('filter_operator_id', $array_data, set_value('filter_operator_id', $filter_operator_id), 'Operator', 'form-control');
		
		$array_data = get_list_item('published');
		$html['filter_published'] = html_select('filter_published', $array_data, set_value('filter_published', $filter_published),'Published');
		
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
		
		//filter_cari
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
		
		//filter_published
		$filter_published_old = $this->session->userdata($this->controller.'_filter_published');
		if(isset($_POST['filter_published'])){
			$filter_published = $this->input->post('filter_published');
			if(strlen($filter_published)!=strlen($filter_published_old)){
				$this->session->set_userdata($this->controller.'_filter_published', $filter_published);
				$offset=0;
			}elseif($filter_published!=$filter_published_old){
				$this->session->set_userdata($this->controller.'_filter_published', $filter_published);
				$offset=0;
			}
		}
		else{
			$filter_published = $this->session->userdata($this->controller.'_filter_published');
		}
		
		//filter_location_id
		$filter_location_id_old = $this->session->userdata($this->controller.'_filter_location_id');
		if(isset($_POST['filter_location_id'])){
			$filter_location_id = $this->input->post('filter_location_id');
			if($filter_location_id!=$filter_location_id_old){
				$this->session->set_userdata($this->controller.'_filter_location_id', $filter_location_id);
				$offset=0;
			}
		}
		else{
			$filter_location_id = $this->session->userdata($this->controller.'_filter_location_id');
		}
		
		//filter_operator_id
		$filter_operator_id_old = $this->session->userdata($this->controller.'_filter_operator_id');
		if(isset($_POST['filter_operator_id'])){
			$filter_operator_id = $this->input->post('filter_operator_id');
			if($filter_operator_id!=$filter_operator_id_old){
				$this->session->set_userdata($this->controller.'_filter_operator_id', $filter_operator_id);
				$offset=0;
			}
		}
		else{
			$filter_operator_id = $this->session->userdata($this->controller.'_filter_operator_id');
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
		
		$filters = array();
		$filters['cari'] = $filter_cari;
		$filters['published'] = $filter_published;
		$filters['location_id'] = $filter_location_id;
		$filters['operator_id'] = $filter_operator_id;
		
		if($param=='tbody'){
			$rows = $this->model->get_paged_list($this->limit, $offset, $filters)->result();
			$data['rows'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody', $data, true);
			// $tbody = str_replace("'","\'",$tbody);
			echo $tbody;
		}
		elseif($param=='pagin'){
			// generate pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url($this->controller.'/index/');
			$config['total_rows'] = $this->model->count_all($filters);
			$data['total_rows'] = $config['total_rows'];
			$config['per_page'] = $this->limit;
			$config['cur_page'] = $offset;
			$config['uri_segment'] = $uri_segment;
			
			//initialize pagination
			$this->pagination->initialize($config);
			echo $this->pagination->create_links();
		}
		elseif($param=='paginfo'){
			$numrows = count($this->model->get_paged_list($this->limit, $offset, $filters)->result());
			$totalrows = $this->model->count_all($filters);
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
		$location_name = $this->session->userdata($this->controller.'_location_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." $location_name [Add]";
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
			$location_name = $this->session->userdata($this->controller.'_location_name');
			$this->view['toptitle'] = ucwords($this->title)." $location_name [Add]";
			$this->view['content'] = $this->controller.'/edit';
			
			// reload view
			$this->load->view("main", $data);
		}
		else
		{
			// save data
			$row = $this->_get_post_data();
			$id = $this->model->save($row);
			
			$tables = array('tb_apm_client', 'tb_apm_client_device', 'tb_device', 'tb_downtime', 'tb_journey', 'tb_journey_detail', 'tb_journey_detail_task', 'tb_list_cat', 'tb_list_item', 'tb_location', 'tb_menu', 'tb_menu_item', 'tb_operator', 'tb_setting', 'tb_tpl_email', 'tb_tpl_message', 'tb_user', 'tb_userrole', 'tb_usertype');
			
			foreach($tables as $table){
				$rows = get_rows("select * from $table");
				foreach($rows as $row){
					$sql = "INSERT INTO tb_log_update (`apm_client_id`, `id`, `table_name`, `record_id`, `action`, `log_date`)
							SELECT '$id', fn_new_log_update_id('$id'), '$table', '".$row->id."', 'insert', NOW();";
					run_query($sql);
				}
			}
			
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
	
	function forceupdate($id){
		$tables = array('tb_apm_client', 'tb_apm_client_device', 'tb_device', 'tb_downtime', 'tb_journey', 'tb_journey_detail', 'tb_journey_detail_task', 'tb_list_cat', 'tb_list_item', 'tb_location', 'tb_menu', 'tb_menu_item', 'tb_operator', 'tb_setting', 'tb_tpl_email', 'tb_tpl_message', 'tb_user', 'tb_userrole', 'tb_usertype');
			
		foreach($tables as $table){
			$rows = get_rows("select * from $table");
			foreach($rows as $row){
				$sql = "INSERT INTO tb_log_update (`apm_client_id`, `id`, `table_name`, `record_id`, `action`, `log_date`)
						SELECT '$id', fn_new_log_update_id('$id'), '$table', '".$row->id."', 'insert', NOW();";
				run_query($sql);
			}
		}
		
		echo "finished";
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
		$location_name = $this->session->userdata($this->controller.'_location_name');
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." $location_name [Edit]";
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
			$location_name = $this->session->userdata($this->controller.'_location_name');
			$this->view['toptitle'] = ucwords($this->title)." $location_name [Edit]";
			$this->view['content'] = $this->controller.'/edit';
		
			// load view
			$this->load->view("main", $data);
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
		$this->form_validation->set_rules('name','Name','trim');
		$this->form_validation->set_rules('location_id','Location','trim');
		$this->form_validation->set_rules('operator_id','Operator','trim');
		$this->form_validation->set_rules('application','Application','trim');
		$this->form_validation->set_rules('published','Published','trim|required');
	}
	
	function _get_html($row=array())
	{
		//prepare select/radio html
		$html = array();
		
		//location
		$array_data = get_array('select id, name from tb_location order by name');
		$html['location_id'] = html_select('location_id', $array_data, set_value('location_id', ($row ? $row->location_id : '')), 'Select Location');
		
		//operator
		$array_data = get_array('select id, name from tb_operator order by name');
		$html['operator_id'] = html_select('operator_id', $array_data, set_value('operator_id', ($row ? $row->operator_id : '')), 'Select Operator');
		
		//application
		$array_data = get_list_item('application');
		$html['application'] = html_select('application', $array_data, set_value('application', ($row ? $row->application : '')), 'Select Application');
		
		//published
		$array_data = get_list_item('published');
		$html['published'] = html_select('published', $array_data, set_value('published', ($row ? $row->published : '1')));
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data($editmode=FALSE){
		$row = array(
				'name' => $this->input->post('name'),
				'location_id' => $this->input->post('location_id'),
				'operator_id' => $this->input->post('operator_id'),
				'application' => $this->input->post('application'),
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
		$data['link_back'] = site_url($this->controller.'/index');
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>