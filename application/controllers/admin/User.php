<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Admin_Controller {
	
	var $upload_config = array();
	var $login_by = '';
	
	function __construct(){
		parent::__construct(__FILE__, 'user', 'user');
		
		//upload config
		$this->upload_config['upload_path'] = 'userfiles/user';
		$this->upload_config['allowed_types'] = 'jpg|jpeg|png|gif';
		$this->upload_config['max_size'] = '5120';
		$this->upload_config['encrypt_name'] = TRUE;
		
		$this->login_by = get_main_config('login_by');
	}
	
	function _get_index()
	{
		return site_url($this->controller);
	}
	
	function index($offset = '')
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
			case 'editchild':
				$cid = $this->input->post('cid');
				redirect('admin/usersite/index/0/'.$cid[0]);
				break;
			case 'sync':
				$this->_sync();
				break;
			default:
				$this->_show($offset);
		}
	}
	
	function _show($offset = 0){
		// offset
		$uri_segment = 4;
		if($offset==''){
			$offset = $this->session->userdata($this->controller.'_offset');
			$offset = $offset!='' ? $offset : 0;
		}
		
		//template setting
		$this->view['content'] = $this->controller.'/list';
		set_breadcrumb($this->_get_index(),'Pengguna',true);
		
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
		$rows = $this->model->get_paged_list($this->user['usertype'], $this->limit, $offset, $filter_cari)->result();
		$data['rows'] = $rows;
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/index/');
 		$config['total_rows'] = $this->model->count_all($this->user['usertype'], $filter_cari);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = $this->limit;
		$config['cur_page'] = $offset;
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->view['toptitle'] = 'Manage '.ucwords($this->title);
		$data['numrows'] = count($rows);
		
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
			$rows = $this->model->get_paged_list($this->user['usertype'], $this->limit, $offset, $filter_cari)->result();
			$data['rows'] = $rows;
			$tbody = $this->load->view($this->config->item('template_admin').'/'.$this->controller.'/list_tbody', $data, true);
			echo str_replace("'","\'",$tbody);
		}
		elseif($param=='pagin'){
			// generate pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url($this->controller.'/index/');
			$config['total_rows'] = $this->model->count_all($this->user['usertype'], $filter_cari);
			$data['total_rows'] = $config['total_rows'];
			$config['per_page'] = $this->limit;
			$config['cur_page'] = $offset;
			$config['uri_segment'] = $uri_segment;
			
			//initialize pagination
			$this->pagination->initialize($config);
			echo $this->pagination->create_links();
		}
		elseif($param=='paginfo'){
			$numrows = count($this->model->get_paged_list($this->user['usertype'], $this->limit, $offset, $filter_cari)->result());
			$totalrows = $this->model->count_all($this->user['usertype'], $filter_cari);
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
	
	function _clear_upload_error(){
		$this->session->unset_userdata($this->controller.'_upload_error_file_picture');
	}
	
	function _add($recall = false){
		//unset session $id
		$this->session->unset_userdata($this->controller.'_id');
		
		//unset file error message
		if(!$recall) $this->_clear_upload_error();
		
		//get view data
		$data = $this->_get_view_data();
		$data['picture'] = base_url('images/no-user.png');
		
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_add','Add',false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function addData(){
		//unset file error message
		$this->_clear_upload_error();
		
		//set validation rules
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			$this->_add(true);
		}
		else
		{
			$this->load->helper('senofile');
			$up_file_picture = uploadFile('file_picture', $this->upload_config);
			$upload_success = $up_file_picture['status'];
			
			if(!$upload_success){
				$this->_add(true);
			}else{				
				// save data
				$this->load->library('crypt');
				$password = $this->crypt->encrypt($this->input->post('password'));
				$row = $this->_get_post_data($password, false);
				if($up_file_picture['path']) $row['picture'] = put_base_url(base_url($up_file_picture['path']));
				$id = $this->model->save($row);
				
				// send email
				/* if($this->input->post('email')){
					$this->load->library('mailer');
					$inputs = array();
					
					$inputs['login_link'] = site_url('user/login');
					$inputs['email'] = $this->input->post('email');
					$inputs['password'] = $this->input->post('password');
		
					$this->mailer->mail_common($id,'register',$inputs);
				} */
				
				// redirect to person list page
				redirect($this->_get_index());
			}
		}
	}
	
	function edit($id, $recall = false){
		//save $id as session for next use
		$this->session->set_userdata($this->controller.'_id', $id);
		
		//unset file error message
		if(!$recall) $this->_clear_upload_error();
		
		// prefill form values
		$row = $this->model->get_by_id($id);
		$row = fix_base_url($row);
		
		//get view data
		if($recall){
			$data = $this->_get_view_data(TRUE);
		}
		else{
			$row->password = "";
			$data = $this->_get_view_data(TRUE, $row);
			$data['row'] = $row;
		}
		$data['picture'] = $row->picture ? $row->picture : base_url('images/no-user.png');
		
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function updateData(){
		//unset file error message
		$this->_clear_upload_error();
		
		// get edited id
		$id = $this->input->post('id');
		
		// set validation properties
		$this->_set_rules(TRUE);
		
		//password validation
		$pass1 = $this->input->post('password');
		$pass2 = $this->input->post('passconf');
		if($pass1 || $pass2){
			$this->_set_rules_pass(TRUE);
			$this->load->library('crypt');
			$password = $this->crypt->encrypt($pass1);
		}
		else{
			$this->_set_rules_pass(FALSE);
			$password = $this->model->get_field_by_id($id, 'password');
		}
		
		if($this->login_by=='email'){
			//email changed
			$str = $this->input->post('email');
			if($str!=$this->model->get_field_by_id($id, 'email')){
				$this->_set_rules_email(TRUE);
			}
			else{
				$this->_set_rules_email(FALSE);
			}
		}else if($this->login_by=='phone'){
			//phone changed
			$str = $this->input->post('phone');
			if($str!=$this->model->get_field_by_id($id, 'phone')){
				$this->_set_rules_phone(TRUE);
			}
			else{
				$this->_set_rules_phone(FALSE);
			}
		}else{	//username
			//username changed
			$str = $this->input->post('username');
			if($str!=$this->model->get_field_by_id($id, 'username')){
				$this->_set_rules_username(TRUE);
			}
			else{
				$this->_set_rules_username(FALSE);
			}
		}
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			$this->edit($id, true);
		}
		else
		{
			$this->load->helper('senofile');
			$up_file_picture = uploadFile('file_picture', $this->upload_config);
			$upload_success = $up_file_picture['status'];
			
			if(!$upload_success){
				$this->edit($id, true);
			}else{
				// save data
				$row = $this->_get_post_data($password);
				
				//get old picture
				$picture_old = $this->model->get_field_by_id($id, 'picture');
				if($picture_old){
					if($this->input->post('delete_picture')=='1'){
						$picture_old_path = rem_base_url($picture_old);
						if(strpos($picture_old_path,'://')===false) delFile($picture_old_path);
						$row['picture'] = '';
					}
				}
				if($up_file_picture['path']) $row['picture'] = put_base_url(base_url($up_file_picture['path']));
				
				//save record
				$this->model->update($id,$row);
				
				//delete old file if new file is uploaded
				if($up_file_picture['path'] && $this->input->post('delete_picture')!='1'){
					$picture_old_path = rem_base_url($picture_old);
					if(strpos($picture_old_path,'://')===false) delFile($picture_old_path);
				}
				
				// redirect to person list page
				redirect($this->_get_index());
			}
		}
	}
	
	function profile($recall = false){
		$id = $this->user['id'];
		
		//unset file error message
		if(!$recall) $this->_clear_upload_error();
		
		// prefill form values
		$row = $this->model->get_by_id($id);
		$row = fix_base_url($row);
		
		//get view data
		$this->view['toptitle'] = "Edit Profil";
		$data['action'] = site_url($this->controller.'/updateProfile');
		if($recall){
			$link_back = $this->session->userdata($this->controller.'_link_back');
		}else{
			$link_back = $_SERVER['HTTP_REFERER'];
			$this->session->set_userdata($this->controller.'_link_back', $link_back);
		}
		$data['link_back'] = $link_back;
		
		if($recall){
			$data['html'] = $this->_get_html(array());
		}
		else{
			$row->password = "";
			$data['row'] = $row;
			$data['html'] = $this->_get_html($row);
		}
		$data['picture'] = $row->picture ? $row->picture : base_url('images/no-user.png');
		
		$this->view['content'] = $this->controller.'/profile';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function updateProfile(){
		//unset file error message
		$this->_clear_upload_error();
		
		// get current user_id
		$id = $this->user['id'];
		
		// set validation properties
		$this->_set_rules(TRUE, TRUE);
		
		//password validation
		$pass1 = $this->input->post('password');
		$pass2 = $this->input->post('passconf');
		if($pass1 || $pass2){
			$this->_set_rules_pass(TRUE);
			$this->load->library('crypt');
			$password = $this->crypt->encrypt($pass1);
		}
		else{
			$this->_set_rules_pass(FALSE);
			$password = $this->model->get_field_by_id($id, 'password');
		}
		
		if($this->login_by=='email'){
			//email changed
			$str = $this->input->post('email');
			if($str!=$this->model->get_field_by_id($id, 'email')){
				$this->_set_rules_email(TRUE);
			}
			else{
				$this->_set_rules_email(FALSE);
			}
		}else if($this->login_by=='phone'){
			//phone changed
			$str = $this->input->post('phone');
			if($str!=$this->model->get_field_by_id($id, 'phone')){
				$this->_set_rules_phone(TRUE);
			}
			else{
				$this->_set_rules_phone(FALSE);
			}
		}else{	//username
			//username changed
			$str = $this->input->post('username');
			if($str!=$this->model->get_field_by_id($id, 'username')){
				$this->_set_rules_username(TRUE);
			}
			else{
				$this->_set_rules_username(FALSE);
			}
		}
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			$this->profile(true);
		}
		else
		{
			$this->load->helper('senofile');
			$up_file_picture = uploadFile('file_picture', $this->upload_config);
			$upload_success = $up_file_picture['status'];
			
			if(!$upload_success){
				$this->profile(true);
			}else{
				// save data
				$row = $this->_get_post_data($password, true, true);
				
				//get old picture
				$picture_old = $this->model->get_field_by_id($id, 'picture');
				if($picture_old){
					if($this->input->post('delete_picture')=='1'){
						$picture_old_path = rem_base_url($picture_old);
						if(strpos($picture_old_path,'://')===false) delFile($picture_old_path);
						$row['picture'] = '';
					}
				}
				if($up_file_picture['path']) $row['picture'] = put_base_url(base_url($up_file_picture['path']));
				
				//save record
				$this->model->update($id,$row);
				
				//delete old file if new file is uploaded
				if($up_file_picture['path'] && $this->input->post('delete_picture')!='1'){
					$picture_old_path = rem_base_url($picture_old);
					if(strpos($picture_old_path,'://')===false) delFile($picture_old_path);
				}
				
				// redirect to person list page
				$link_back = $this->session->userdata($this->controller.'_link_back');
				$this->session->unset_userdata($this->controller.'_link_back');
				redirect($link_back);
			}
		}
	}
	
	function _delete($id){
		//get old record
		$ids = implode(',',$id);
		$rows = get_rows("select picture from tb_user where id in ($ids)");
		
		// delete data
		$this->model->delete($id);
		
		//delete images
		$this->load->helper('senofile');
		foreach($rows as $row){
			
			if($row->picture){
				$picture_path = rem_base_url($row->picture);
				if(strpos($picture_path,'://')===false) delFile($picture_path);
			}
		}
	}
	
	// validation rules
	function _set_rules($editmode=FALSE, $for_profile = false){
		$this->form_validation->set_rules('name','Nama','trim|required');
		$this->form_validation->set_rules('address','Alamat','trim');
		if(!$editmode){
			if($this->login_by=='email'){
				$this->form_validation->set_rules('email','Alamat Email','trim|required|valid_email|is_unique[tb_user.email]');
			}else if($this->login_by=='phone'){
				$this->form_validation->set_rules('phone','No Telepon','trim|required|is_unique[tb_user.phone]');
			}else{
				$this->form_validation->set_rules('username','Username','trim|required|is_unique[tb_user.username]');
			}
			$this->form_validation->set_rules('password','Password','trim|required');
			$this->form_validation->set_rules('passconf','Konfirmasi Password','trim|required|matches[password]');
		}
		if($for_profile){
			$this->form_validation->set_rules('usertype','Tipe User','');
			$this->form_validation->set_rules('userrole','Role User','');
			$this->form_validation->set_rules('status','Status User','');
		}else{
			$this->form_validation->set_rules('usertype','Tipe User','trim|required');
			$this->form_validation->set_rules('userrole','Role User','trim');
			$this->form_validation->set_rules('status','Status User','trim|required');
		}
	}
	
	function _set_rules_username($changed=FALSE){
		if($changed){
			$this->form_validation->set_rules('username','Username','trim|required|is_unique[tb_user.username]');
		}
		else{
			$this->form_validation->set_rules('username','Username','trim|required');
		}
		$this->form_validation->set_rules('email','Email','trim');
		$this->form_validation->set_rules('phone','No Telp','trim');
	}
	
	function _set_rules_email($changed=FALSE){
		if($changed){
			$this->form_validation->set_rules('email','Alamat Email','trim|required|valid_email|is_unique[tb_user.email]');
		}
		else{
			$this->form_validation->set_rules('email','Alamat Email','trim|required|valid_email');
		}
		$this->form_validation->set_rules('username','Username','trim');
		$this->form_validation->set_rules('phone','No Telp','trim');
	}
	
	function _set_rules_phone($changed=FALSE){
		if($changed){
			$this->form_validation->set_rules('phone','No Telepon','trim|required|is_unique[tb_user.phone]');
		}
		else{
			$this->form_validation->set_rules('phone','No Telepon','trim|required');
		}
		$this->form_validation->set_rules('email','Email','trim');
		$this->form_validation->set_rules('username','Username','trim');
	}
	
	function _set_rules_pass($changed=FALSE){
		if($changed){
			$this->form_validation->set_rules('password','Password','trim|required');
			$this->form_validation->set_rules('passconf','Konfirmasi Password','trim|required|matches[password]');
		}
		else{
			$this->form_validation->set_rules('password','Password','');
			$this->form_validation->set_rules('passconf','Konfirmasi Password','');
		}
	}
	
	// date_validation callback
	function valid_date($str)
	{
		if(!check_date($str)){
			$this->form_validation->set_message('valid_date', '%s tidak valid. dd-mm-yyyy');
			return false;
		}
		else{
			return true;
		}
	}
	
	//get posted data to row
	function _get_post_data($password, $editmode = true, $for_profile = false){
		$row = array(
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'name' => $this->input->post('name'),
			'phone' => $this->input->post('phone'),
			'address' => $this->input->post('address'),
			'password' => $password,
		);
		
		if(!$editmode) $row['registerDate'] = date('Y-m-d H:i:s',time());
		
		if(!$for_profile){
			$row['usertype'] = $this->input->post('usertype');
			$row['userrole'] = $this->input->post('userrole');
			$row['status'] = $this->input->post('status');
		}
		
		return $row;
	}
	
	function _get_html($row=array())
	{
		//prepare select/radio html
		$html = array();
		
		//tipe user
		$array_data = $this->model->get_list_usertype($this->user['usertype']);
		$html['usertype'] = html_select('usertype', $array_data, set_value('usertype', ($row ? $row->usertype : '')));
		
		//role user
		$array_data = get_array("select * from tb_userrole order by name");
		$html['userrole'] = html_select('userrole', $array_data, set_value('userrole', ($row ? $row->userrole : '')), 'Select Role');
		
		//status
		$array_data = array(
				'0' => array('value' => '0', 'text' => 'New'),
				'1' => array('value' => '1', 'text' => 'Active'),
				'2' => array('value' => '2', 'text' => 'Blocked'),
			);
		$html['status'] = html_radio('status', $array_data, set_value('status', ($row ? $row->status : '1')), '', FALSE, 'inputbox');
		
		return $html;
	}
	
	function _get_view_data($editmode=FALSE, $row=array())
	{
		if($editmode){
			$label = "Edit ";
			$method = "updateData";
		}
		else{
			$label = "Add ";
			$method = "addData";
		}
		$this->view['toptitle'] = "Manage ".ucwords($this->title)." - $label";
		$data['action'] = site_url($this->controller.'/'.$method);
		$data['link_back'] = $this->_get_index();
		$data['html'] = $this->_get_html($row);
		return $data;
	}
}

?>