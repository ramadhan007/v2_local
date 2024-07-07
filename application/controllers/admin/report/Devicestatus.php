<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DeviceStatus extends Admin_Controller {

	//controller main properties
	var $controller = "admin/report/devicestatus";
	var	$title = "devices";
	var $is_modal = false;
	
	function __construct(){
		parent::__construct(__FILE__, 'devices', 'device');
		
		$this->limit = 'all';
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
				$this->_show($this->is_modal);
		}
	}
	
	function simple(){
		$this->_show(true);
	}
	
	function _show($is_modal){	
		// offset
		$uri_segment = 5;
		$offset = $this->session->userdata($this->controller.'_offset');
		$offset = $offset!='' ? $offset : 0;
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb($this->_get_index_offset(),ucwords($this->title),false);
		
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
		
		$filter_published = '1';
		
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
		
		$offset = 0;
		
		// save offset
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		$filter_cari = '';
		$filter_limit = 'all';
		$filter_location_id = '';
		$filter_operator_id = '';
		
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
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." $location_name - List";
		
		//filter_limit
		$html['filter_limit'] = get_filter_limit($this->limit);
		
		$sql = "select id, name from tb_location where published order by name";
		$array_data = get_array($sql);
		$html['filter_location_id'] = html_select('filter_location_id', $array_data, set_value('filter_location_id', $filter_location_id), 'Location', 'form-control');
		
		$sql = "select id, name from tb_operator where published order by name";
		$array_data = get_array($sql);
		$html['filter_operator_id'] = html_select('filter_operator_id', $array_data, set_value('filter_operator_id', $filter_operator_id), 'Operator', 'form-control');
		
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
		
		$filter_published = '1';
		
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
		
		$offset = 0;
		
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		$filter_cari = '';
		$filter_limit = 'all';
		$filter_location_id = '';
		$filter_operator_id = '';
		
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
	
	function listcontent1($id){
		$rows = $this->model->get_one_list($id)->result();
		$data['rows'] = $rows;
		$data['offset'] = $this->input->post('offset');
		$data['index'] = $this->input->post('index');
		$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody1', $data, true);
		echo str_replace("'","\'",$tbody);
	}
	
	function getdevicestatus(){
		$ids = $this->input->post('ids');
		$rows = get_rows("select id, status_final as status, status_time from tb_device where id in ($ids)", false);
		echo json_encode($rows);
	}
}

?>