<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notif extends CI_Controller {

	//controller main properties
	var $controller = "admin/notif";
	
	function __construct(){
		parent::__construct();
		
		/* if($this->user['logged_in']){
			if(!check_user_access($this->user['id'], $this->controller)){
				$this->session->set_userdata('noaccess_menu', site_url($this->controller));
				redirect('admin/dashboard/noaccess');
			}
		}
		else{
			echo "";
			exit();
		} */
	}
	
	function index()
	{
		
	}
	
	function followup(){
		$notif_user_id = $this->input->post('notif_user_id');
		run_query("update tb_notif_user set followed_up = '1' where id = '$notif_user_id'");
		echo json_encode(array(
				'status' => 1,
			));
	}
}

?>