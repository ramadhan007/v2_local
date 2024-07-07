<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Session extends CI_Controller {

	//controller main properties
	var $controller = "admin/session";
	
	function __construct(){
		parent::__construct();
	}
	
	function get($param){
		echo $this->session->userdata($this->controller.'_'.$param);
	}
	
	function set($param){
		$value = $this->input->post('value');
		$this->session->set_userdata($this->controller.'_'.$param, $value);
	}
}

?>