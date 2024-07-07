<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

	// controller main properties
	var $controller;
	var	$title;
	var $is_modal = false;
	var $continue = true;
	
	// num of records per page
	var $limit = 8;
	var $num_links = 10;
	
	// param view & user
	var	$view = array();
	var	$user = array();
	
	function __construct($file, $title, $model = '', $check_access = true, $must_login = true, $is_modal = false){
		parent::__construct();
		
		$this->controller = strtolower($this->get_controller($file));
		$this->title = $title;
		$this->view = controller_view();
		$this->js_list = 'list';
		
		if($model) $this->load->model($model.'Model','model',TRUE);
		
		$this->view['toptitle'] = "Manage ".ucwords($this->title);
		$this->view['doctitle'] = 'Administration - '.ucfirst($this->title);
		
		$this->is_modal = $is_modal;
		
		//check login
		if($must_login){
			if($this->user['logged_in']){
				if($check_access){
					if(!check_user_access($this->user['id'], $this->controller)){
						$this->continue = false;
						$this->session->set_userdata('access_item', ucfirst($this->title));
						// redirect($this->config->item("admin").'/dashboard/noaccess/'.($this->is_modal ? 1:0));
						$this->load->controller($this->config->item('admin').'/dashboard');
						$this->dashboard->noaccess($this->is_modal);
					}
				}else{
					if($this->user['usertype']>3){
						$this->continue = false;
						$this->session->set_userdata('access_item', ucfirst($this->title));
						// redirect($this->config->item("admin").'/dashboard/noaccess/'.($this->is_modal ? 1:0));
						$this->load->controller($this->config->item('admin').'/dashboard');
						$this->dashboard->noaccess($this->is_modal);
					}
				}
			}
			else{
				redirect($this->config->item("admin").'/login');
			}
		}
	}
	
	function get_controller($filedir){
		$ctrldir = str_replace("\\","/",getcwd())."/application/controllers/";
		$filedir = str_replace("\\","/",$filedir);
		return substr(str_replace($ctrldir, "", $filedir),0,-4);
	}
}

?>