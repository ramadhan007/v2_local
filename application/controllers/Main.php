<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	
	//controller main properties
	var $controller = "main";
	var	$title = "main";
	
	function __construct(){
		parent::__construct();
	}

	function index()
	{
		if(!uri_string()){	//handle empty uri = home
			$this->_home();
		}
		else{ //handle error uri = menu
			$this->_menu();
		}
	}
	
	function _home()
	{
		$data = array();
		$this->view['title'] = "Home";
		$this->view['keywords'] = get_main_config('meta_keywords');
		$this->view['description'] = get_main_config('meta_description');
		$this->load->view('main',$data);
	}
	
	function _menu()
	{
		// echo uri_string(); exit();
		$ar_uri_string = explode('/',uri_string());
		$alias = $ar_uri_string[0];
		$row = get_row("select * from tb_alias where alias = '$alias'");
		if($row){
			$param = $row->id;
			if(count($ar_uri_string)>1){
				$param = array();
				$param[] = $row->id;
				for($i=1; $i<=count($ar_uri_string)-1;$i++){
					$param[] = $ar_uri_string[$i];
				}
			}
			$controller = $row->controller;
			$this->load->library('../controllers/'.$controller);
			$this->$controller->index($param);
		}
		else{
			$param = '';
			if(count($ar_uri_string)>1){
				$param = array();
				$param[] = '';
				for($i=1; $i<=count($ar_uri_string)-1;$i++){
					$param[] = $ar_uri_string[$i];
				}
			}
			$this->load->library('../controllers/'.$alias);
			$this->$alias->index($param);
		}
	}
}

?>