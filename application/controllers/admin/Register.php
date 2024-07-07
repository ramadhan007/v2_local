<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Admin_Controller {
	
	var $login_by;
		
	function __construct(){
		parent::__construct(__FILE__, 'pendaftaran', 'user', false, false);
		$this->view['doctitle'] = 'Pendaftaran';
		$this->login_by = get_main_config('login_by');
	}
	
	function index()
	{
		if(!$this->continue) return;
		$login_data = $this->session->userdata('login_data_admin');
		if(!$login_data['is_logged_in']){
			$this->session->sess_destroy();
			$this->_formRegister();
		}
		else{
			$this->load->library('../controllers/'.$this->config->item('admin').'/dashboard');
			$this->dashboard->index();
		}
	}
	
	function _formRegister(){
		$sql = "select id, name from tb_usertype where published and id >= 3 order by id desc";
		$array_data = get_array($sql);
		$html['usertype'] = html_select('usertype', $array_data, '', 'Daftar Sebagai:', 'form-control');
		
		$sql = "select id, nama as name from tb_sekolah where published";
		$array_data = get_array($sql);
		$html['sekolah_id'] = html_select('sekolah_id', $array_data, '', 'Pilih Sekolah:', 'form-control');
		
		$data['html'] = $html;
		
		$data['form_action'] = site_url($this->controller.'/doRegister');
		$this->view['toptitle'] = "Register";
		$this->view['content'] = str_replace("register","login",$this->controller)."/register";
		$this->load->view('main', $data);
	}
	
	function doRegister()
	{
		//set validation rules
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE){
			$ar_return = array('status' => 0, 'message' => strip_tags(validation_errors()));
		}else{
			//continue registration
			$this->load->library('crypt');
			$password = $this->crypt->encrypt($this->input->post('password'));
			$usertype = intval($this->input->post('usertype'));
			$sekolah_id = $this->input->post('sekolah_id');
			$nik = $this->input->post('nik');
			$status="1";
			if($usertype==3){
				$sekolah_id = "";
				$nik = "";
			}
			$row = array(
				'name' => $this->input->post('name'),
				'phone' => $this->input->post('phone'),
				'password' => $password,
				'usertype' => $usertype,
				'nik' => $nik,
				'status' => $status,
			);
			$id = $this->model->save($row);
			if($id){
				// insert tb_sekolah_user
				if($sekolah_id){
					$sql = "insert into tb_sekolah_user (sekolah_id, user_id, status) values ('$sekolah_id', '$id', '0');";
					if(run_query($sql)){
						$ar_return = array('status' => 1, 'message' => '');
					}else{
						// cancel user
						run_query("delete from tb_user where id = '$id';");
						$ar_return = array('status' => 0, 'message' => $this->db->error());
					}
				}else{
					$ar_return = array('status' => 1, 'message' => '');
				}
			}else{
				$ar_return = array('status' => 0, 'message' => $this->db->error());
			}
		}
		echo json_encode($ar_return);
	}
	
	// validation rules
	function _set_rules(){
		$this->form_validation->set_rules('name','Nama','trim|required');
		if($this->login_by=='email'){
			$this->form_validation->set_rules('email','Alamat Email','trim|required|valid_email|is_unique[tb_user.email]');
		}else if($this->login_by=='phone'){
			$this->form_validation->set_rules('phone','No Telepon','trim|required|is_unique[tb_user.phone]');
		}else{
			$this->form_validation->set_rules('username','Username','trim|required|is_unique[tb_user.username]');
		}
		$this->form_validation->set_rules('password','Password','trim|required');
		$this->form_validation->set_rules('passconf','Konfirmasi Password','trim|required|matches[password]');
		$this->form_validation->set_rules('usertype','Daftar Sebagai','trim|required');
		$usertype = intval($this->input->post('usertype'));
		if($usertype>3){ //jika pengajar / siswa
			$this->form_validation->set_rules('sekolah_id','Sekolah','trim|required');
		}else{
			$this->form_validation->set_rules('sekolah_id','Sekolah','');
		}
		$this->form_validation->set_rules('nik','NIP / NIS','');
		$this->form_validation->set_rules('agree','Setuju','trim|required');
	}
	
	function loginFacebook(){
		$picture = $this->input->post('fb_picture');
		$fb_uid = $this->input->post('fb_uid');
		if($fb_uid){
			//validasi accessToken
			$this->load->helper('http');
			$accessToken = $this->input->post('fb_accessToken');
			$resp = http_load("https://graph.facebook.com/me?access_token=$accessToken");
			$ar_resp = json_decode($resp);
			if($ar_resp->id==$fb_uid){	//respon id = post id, cocok
				$row_check = $this->model->get_field_by_field('fb_uid', $fb_uid, 'id, picture');
				if($row_check){
					//jika ada, check picture
					if($row_check->picture){ //jika ada, langsung login
						$this->_login_social($fb_uid);
					}
					else{	//jika tidak ada, update picture lalu login
						$this->model->update($row_check->id, array('picture' => $picture));
						$this->_login_social($fb_uid);
					}
				}
				else{
					//jika tidak ada, cek email account fb
					$row_check = $this->model->get_field_by_field('email', $this->input->post('fb_email'), 'id, picture');
					if($row_check){
						if($row_check->picture){
							$this->model->update($row_check->id, array('fb_uid' => $fb_uid));
							$this->_login_social($fb_uid);
						}
						else{
							$this->model->update($row_check->id, array('fb_uid' => $fb_uid, 'picture' => $picture));
							$this->_login_social($fb_uid);
						}
					}
					else{
						$ar_return = array('status' => 0, 'message' => "Account Facebook Anda belum terdaftar");
						echo json_encode($ar_return);
					}
				}
			}
		}
	}
	
	function loginGoogle(){
		$picture = $this->input->post('g_picture');
		if($picture){
			$ar_picture = explode("/", $picture);
			if(count($ar_picture)>=2){
				if($ar_picture[count($ar_picture)-2]=='s96-c'){
					$ar_picture[count($ar_picture)-2] = 's200-c';
					$picture = implode("/", $ar_picture);
				}
			}
		}
		$g_uid = $this->input->post('g_uid');
		if($g_uid){
			//validasi accessToken
			$this->load->helper('http');
			$id_token = $this->input->post('g_id_token');
			$resp = http_load("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$id_token");
			$ar_resp = json_decode($resp);
			$client_id = "763389307343-g6cqjgb673dge7j09ngb0isdr2d6a4rd.apps.googleusercontent.com";
			if($ar_resp->aud==$client_id && $ar_resp->sub==$g_uid){	//respon aud = client_id, sub = id -> cocok
				$row_check = $this->model->get_field_by_field('g_uid', $g_uid, 'id, picture');
				if($row_check){
					//jika ada, check picture
					if($row_check->picture){ //jika ada, langsung login
						$this->_login_social('', $g_uid);
					}
					else{	//jika tidak ada, update picture lalu login
						$this->model->update($row_check->id, array('picture' => $picture));
						$this->_login_social('', $g_uid);
					}
				}
				else{
					$row_check = $this->model->get_field_by_field('email', $this->input->post('g_email'), 'id, picture');
					if($row_check){
						if($row_check->picture){
							$this->model->update($row_check->id, array('g_uid' => $g_uid));
							$this->_login_social('', $g_uid);
						}
						else{
							$this->model->update($row_check->id, array('g_uid' => $g_uid, 'picture' => $picture));
							$this->_login_social('', $g_uid);
						}
					}
					else{
						$ar_return = array('status' => 0, 'message' => "Account Google Anda belum terdaftar");
						echo json_encode($ar_return);
					}
				}
			}
		}
	}
	
	function _login_social($fb_uid='', $g_uid=''){
		$retlogin = $this->model->checklogin('', '', $fb_uid, $g_uid);
		if(!$retlogin['return'])
		{
			$ar_return = array('status' => 0, 'message' => strip_tags(get_tpl_message('login_failed')));
			echo json_encode($ar_return);
		}
		else
		{
			if($retlogin['row']->usertype<=2){
				$login_data['is_logged_in'] = TRUE;
				$login_data['user'] = $retlogin['row'];
				$this->session->set_userdata('login_data_admin', $login_data);
				
				// set sekolah_id untuk akses userfiles & semua controller
				$sekolah_id = $retlogin['row']->sekolah_id;
				// $sekolah_id = $sekolah_id ? $sekolah_id : '1';
				
				$this->session->set_userdata('active_sekolah_id', $sekolah_id);
				$tingkat = get_field_by_id('tb_sekolah', $sekolah_id, 'tingkat');
				$this->session->set_userdata('active_sekolah_tingkat', $tingkat);
				$_SESSION['sekolah_id'] = $sekolah_id;
				
				// prepare dir
				$this->load->helper('senofile');
				prepDir('userfiles/'.$sekolah_id);
				
				$ar_return = array('status' => 1, 'message' => '');
			}else{
				$ar_return = array('status' => 0, 'message' => strip_tags(get_tpl_message('login_no_admin_access')));
			}
			echo json_encode($ar_return);
		}
	}
	
	function logout()
	{
		$this->session->unset_userdata('login_data_admin');
		$this->session->unset_userdata('user_last_activity');
		redirect($this->config->item('admin'));
	}
}

?>