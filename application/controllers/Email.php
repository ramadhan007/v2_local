<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller {

	//controller main properties
	var $controller = "email";
	var	$model = "email";
	var	$title = "email";
	
	// num of records per page
	var $limit = 8;
	
	var	$view = array();
	var	$user = array();
	
	function __construct(){
		parent::__construct();
		
		// load library
		$this->load->model('emailModel','',TRUE);
		$this->model = new emailModel;
		
		$this->view['doctitle'] = 'Kelola '.ucwords($this->title);
		
		//check login
		/* if($this->user['logged_in']){
			if($this->user['usertype']>2) redirect('admin/area');
		}
		else{
			redirect('admin/login');
		} */
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
			default:
				$this->_show($offset);
		}
	}
	
	function _show($offset = 0){	
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		//template setting
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/list';
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
		
		// load data
		$rows = $this->model->get_paged_list($this->limit, $offset, $filter_cari)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($filter_cari);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title);
		$data['numrows'] = count($rows);
		
		// load view
		$this->load->view($this->config->item('template_user').'/index',$data);
	}

	function _add()
	{
		//unset session $id
         $this->session->unset_userdata($this->controller.'_id');
		 
		$data['action'] = site_url($this->controller.'/form'); ///// kanggo form action
		
        //set link_back link
         $data['link_back'] = $this->_get_index_offset(); 
		
		//prepare select/radio html
		$html = array();
		
		//parent_id
		$this->load->model('tplemailModel','');
		$array_data = $this->tplemailModel->get_list('Booking');
		$html['tpl_email_id'] = html_select('tpl_email_id', $array_data, set_value('tpl_email_id', ''), '', 'form-control');
		
		$data['html'] = $html;
		
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' - Pilih Template';
        $this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/seltpl';
		
		/// load view
        $this->load->view($this->config->item('template_user').'/index',$data);
	}
	
	function form(){
		//get view data		
		$data = $this->_get_view_data();
		
		// template variables
		$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Add]';
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/edit';
		set_breadcrumb($this->controller.'_add','Add',false);
	
		// load view
		$this->load->view($this->config->item('template_user').'/index',$data);
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
			$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/edit';
			
			// reload view
			$this->load->view($this->config->item('template_user').'/index',$data);
		}
		else
		{
			// save data
			$row = $this->_get_post_data();
			$id = $this->model->save($row);

			//send email
			$this->load->library('mailer');
			$ar_rcp = explode(';', str_replace(' ', '', $row['rcp']));
			foreach ($ar_rcp as $rcp) {
				$this->mailer->subject($row['subject']);
				$this->mailer->message($row['body']);
				$this->mailer->to($rcp);
				$this->mailer->send();
			}
			
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
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view($this->config->item('template_user').'/index',$data);
	}
	
	function updateData(){
		// set validation properties
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			echo set_value('setting_name[]', '');
			echo "<br>";
			echo set_value('setting_val[]', '');
			exit();
			
			//get view data
			$data = $this->_get_view_data(TRUE);
			
			// template variables
			$this->view['toptitle'] = 'Kelola '.ucwords($this->title).' [Edit]';
			$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/edit';
		
			// load view
			$this->load->view($this->config->item('template_user').'/index',$data);
		}
		else
		{
			// save data
			$id = $this->input->post('id');
			$row = $this->_get_post_data();
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
		$data['row'] = $this->model->get_by_id($id)->row();
		
		// template variables
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/view';
		set_breadcrumb($this->controller.'_view',$data['title'],false);
		
		// load view
		$this->load->view($this->config->item('template_user').'/index',$data);
	}
	
	function _delete($id){
		// delete data
		$this->model->delete($id);
		
		// redirect to person list page
		redirect($this->controller.'/index/'.$this->_get_offset());
	}
	
	// validation rules
	function _set_rules(){
		// $this->form_validation->set_rules('tpl_email_id','Tag Template','trim|required');
		$this->form_validation->set_rules('rcp','Penerima','trim|required');
		$this->form_validation->set_rules('subject','Subject','trim|required');
		$this->form_validation->set_rules('body','Body Email','');
		$this->form_validation->set_rules('sent','Terkirim','trim|required');
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
		$this->load->model('tplemailModel','');
		$tpl_email_id = $this->input->post('tpl_email_id');
		$def_subject = "";
		$def_body = "";
		if($tpl_email_id){
			$row_tpl = $this->tplemailModel->get_by_id($tpl_email_id);
			$def_subject = $row_tpl->subject;
			$def_body = $row_tpl->body;
		}
		$html['def_subject'] = $def_subject;


		$html['body'] = html_ckeditor('body', set_value('body', ($row ? $row->body : $def_body)));
		
		//sent
		$array_data = array(
			'0' => array('value' => '1', 'text' => 'Ya'),
			'1' => array('value' => '0', 'text' => 'Tidak'),
			);
		$html['sent'] = html_radio('sent', $array_data, set_value('sent', ($row ? $row->sent : '0')), '', FALSE);
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data(){
		$row = array('rcp' => $this->input->post('rcp'),
				'subject' => $this->input->post('subject'),
				'body' => $this->input->post('body'),
				'sent' => $this->input->post('sent')
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