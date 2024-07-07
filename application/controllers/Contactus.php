<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactus extends CI_Controller {

	//controller main properties
	var $controller = "contactus";
	var	$title = "hubungi kami";
	
	function __construct(){
		parent::__construct();
		
		//fill template parameters
		$this->view['title'] = 'Hubungi Kami';
	}
	
	function index()
	{
		$this->session->set_userdata('posted', '0');
		$this->_form();
	}
	
	function _set_rules(){
		if(!$this->user['logged_in']){
			$this->form_validation->set_rules('name','Nama','trim|required');
			$this->form_validation->set_rules('email','Email','trim|valid_email');
			$this->form_validation->set_rules('phone','HP/Whatsapp','trim|required');
			$this->form_validation->set_rules('g-recaptcha-response','Security Code','callback_check_scode');
		}
		$this->form_validation->set_rules('message','Pertanyaan','trim|required');
	}
	
	// check_scode callback
	function check_scode($str)
	{
		if($str){
			$this->load->helper('http');
			$params = array(
					'secret' => '6LeuNloUAAAAAE7WFEKMB8s0wteElvn95c5OcC-T',
					'response' => $str,
					'remoteip' => '',
				);
			$response = http_load('https://www.google.com/recaptcha/api/siteverify',0,$params);
			if($response){
				$ar_response = json_decode($response);
				if($ar_response->success){
					return true;
				}else{
					$this->form_validation->set_message('check_scode', 'Buktikan bahwa Anda bukan robot :)');
					return false;
				}
			}else{
				$this->form_validation->set_message('check_scode', 'Buktikan bahwa Anda bukan robot :)');
				return false;
			}
		}
		else{
			$this->form_validation->set_message('check_scode', 'Buktikan bahwa Anda bukan robot :)');
			return false;
		}
	}
	
	function _form()
	{
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/form';
		$this->load->view($this->config->item('template_user').'/index');
	}
	
	function submit()
	{
		$this->_set_rules();
		if($this->form_validation->run() == FALSE){
			$this->session->set_userdata('posted', '1');
			$this->_form();
		}
		else{
			$row = $this->_get_post_data();
			model_save('tb_contactus',$row);
			
			// send email to admin
			$this->load->library('mailer');
			$this->mailer->mail_admin('contact_admin',$row);
			
			//show success message
			$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/success';
			$this->load->view($this->config->item('template_user').'/index');
		}
	}
	
	function _get_post_data(){
		if($this->user['logged_in']){
			$row_user = model_get_by_id('tb_user', $this->user['id']);
			$row = array(
					'name' => $row_user->name,
					'email' => $row_user->email,
					'phone' => $row_user->phone,
				);
		}
		else{
			$row = array(
					'name' => $this->input->post('name'),
					'phone' => $this->input->post('phone'),
					'email' => $this->input->post('email'),
				);
		}
		$row['message'] = $this->input->post('message');
		$row['sekolah_id'] = get_sekolah_id();
		return $row;
	}
}

?>