<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactus extends CI_Controller {

	//controller main properties
	var $controller = "admin/contactus";
	var	$title = "kontak kami";
	
	function __construct(){
		parent::__construct();
		
		$this->js_list = 'list_old';
		
		// load model
		$this->load->model('contactusModel','',TRUE);
		$this->model = new contactusModel;
		
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
			case 'delete':
				$cid = $this->input->post('cid');
				$this->_delete($cid);
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
	
	function view($id){
		// set common properties
		$data['title'] = 'Detail';
		$data['link_back'] = $this->_get_index_offset();
		
		// get record details
		$row = $this->model->get_by_id($id);
		
		$data['row'] = $row;
		
		// template variables
		$this->view['content'] = $this->controller.'/view';
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' - Detail';
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
	
	function contactdetail()
	{
		$data['contactdetail'] = html_ckeditor('contactdetail', set_value('contactdetail', get_main_config('contactdetail')),'form-control','auto','260px');
		//$data['contactdetail'] = set_value('contactdetail', get_main_config('contactdetail'));
		$data['action'] = site_url($this->controller.'/savecontactdetail');
		
		$this->view['content'] = $this->controller.'/contactdetail';
		$this->load->view('main_plain', $data);
	}
	
	function savecontactdetail()
	{
		$contactdetail = $this->input->post('contactdetail');
		save_main_config('contactdetail',$contactdetail);
		echo "<script>parent.closeModal();</script>";
	}
}

?>