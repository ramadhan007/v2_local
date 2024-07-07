<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Icon extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'icon', 'list', false);
		$this->js_list = 'list_old';
	}
	
	function search($offset=0, $field_id)
	{	
		//field id
		if($field_id){
			$this->session->set_userdata($this->controller.'_field_id', $field_id);
		}
		else{
			$field_id = $this->session->userdata($this->controller.'_field_id');
		}
		$data['field_id'] = $field_id;
		
		// offset
		$uri_segment = 4;
		$this->session->set_userdata($this->controller.'_offset', $offset);
		$data['offset'] = $offset;
		
		//template setting
		$this->view['content'] = $this->controller.'/search';
		
		//filter cari
		if(isset($_POST['filter_cari'])){
			$filter_cari = $this->input->post('filter_cari');
			$this->session->set_userdata($this->controller.'_filter_cari', $filter_cari);
			$offset=0;
		}
		else
		{
			$filter_cari = $this->session->userdata($this->controller.'_filter_cari');
		}
		
		// load all data
		$lists = $this->model->fontawesome();
		
		//print_r($list);
		
		//filter search data
		$lists_cari = array();
		if($filter_cari){
			foreach($lists as $list){
				if(strpos($list['text'],$filter_cari)!==false){
					$lists_cari[] = array('value' => $list['value'], 'text' => $list['text']);
				}
			}
		}
		else{
			$lists_cari = $lists;
		}
		
		//print_r($lists_cari);
		
		$rows = json_decode(json_encode($lists_cari), FALSE);
		$data['rows'] = $rows;
		
		//print_r($rows);
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url($this->controller.'/search/');
 		$config['total_rows'] = count($rows);
 		$data['total_rows'] = $config['total_rows'];
 		$config['per_page'] = count($rows);
		$config['uri_segment'] = $uri_segment;
		
		//initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		// load view
		$this->load->view('main_plain', $data);
	}
}

?>