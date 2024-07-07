<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Admin_Controller {
	function __construct(){
		parent::__construct(__FILE__, 'login', 'user', false, false);		
		$this->view['doctitle'] = 'Login';
	}
	
	function index()
	{
		$login_data = $this->session->userdata('login_data_admin');
		if($login_data && $login_data['is_logged_in']){
			// $this->load->controller($this->config->item('admin').'/rawdata/journey');
			// $this->journey->index();
			// redirect($this->config->item('admin').'/rawdata/journey');
			redirect($this->config->item('admin').'/report/dashboard');
		}
		else{
			$this->session->sess_destroy();
			
			unset($_SESSION['usertype']);
			unset($_SESSION['user_id']);
			
			$data['form_action'] = site_url($this->controller.'/doLogin');
			
			$this->load->library('crypt');
			
			$remember = $this->crypt->decrypt(get_cookie("admin_login_remember"));
			$data['checked'] = $remember=='yes' ? 'checked="checked"' : '';
			$data['login'] = $remember=='yes' ? $this->crypt->decrypt(get_cookie("admin_login_login")) : '';
			$data['password'] = $remember=='yes' ? $this->crypt->decrypt(get_cookie("admin_login_password")) : '';
			
			$this->view['toptitle'] = "Login";
			$this->view['content'] = $this->controller."/login";
			$this->load->view('main', $data);
		}
	}
	
	function doLogin()
	{
		$login = trim($this->input->post('login'));
		$password = $this->input->post('password');
		$remember = $this->input->post('remember');
		$retlogin = $this->model->checklogin($login, $password);
		if(!$retlogin['return'])
		{
			$ar_return = array('status' => 0, 'message' => strip_tags(get_tpl_message('login_failed')));
			echo json_encode($ar_return);
		}
		else
		{
			if($retlogin['row']->usertype)
			{
				if($remember=='yes')
				{
					$this->load->library('crypt');
					
					$expire = '86400';
					set_cookie("admin_login_login", $this->crypt->encrypt($login), $expire);
					set_cookie("admin_login_password", $this->crypt->encrypt($password), $expire);
					set_cookie("admin_login_remember", $this->crypt->encrypt($remember), $expire);
				}
				else
				{
					delete_cookie("admin_login_login");
					delete_cookie("admin_login_password");
					delete_cookie("admin_login_remember");
				}
				$login_data['is_logged_in'] = TRUE;
				$login_data['user'] = $retlogin['row'];
				$this->session->set_userdata('login_data_admin', $login_data);
				// $_SESSION['login_data_admin'] = $login_data;
				
				$_SESSION['usertype'] = $retlogin['row']->usertype;
				$_SESSION['user_id'] = $retlogin['row']->id;
				
				$ar_return = array('status' => 1, 'message' => '');
			}
			else
			{
				$ar_return = array('status' => 0, 'message' => strip_tags(get_tpl_message('login_no_admin_access')));
			}
			echo json_encode($ar_return);
		}
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
				
				$_SESSION['usertype'] = $retlogin['row']->usertype;
				$_SESSION['user_id'] = $retlogin['row']->id;
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
		// unset($_SESSION['login_data_admin']);
		$this->session->unset_userdata('user_last_activity');
		redirect($this->config->item('admin'));
	}
}

?>