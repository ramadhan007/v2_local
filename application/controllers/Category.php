<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

	//controller main properties
	var $controller = "category";
	var	$title = "kategori";
	
	function __construct(){
		parent::__construct();
		
		//fill template parameters
		$this->view['title'] = 'Artikel';
	}
	
	function index($id='')
	{
		$data = array();
		if($id){
			$rowc = get_row("SELECT title, meta_enable, meta_keywords, meta_description FROM tb_category WHERE id = '$id'");
			$this->view['title'] = $rowc->title;
			$rows = get_rows("SELECT * FROM tb_article WHERE concat(',',category_id,',') like '%,$id,%' and published");
			if($this->view['title']){
				$data['rows'] = $rows;
				if($rowc->meta_enable){
					$this->view['keywords'] = $rowc->meta_keywords;
					$this->view['description'] = $rowc->meta_description;
				}
				$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/index';
			}
			else{
				$this->error404();
			}
		}
		else{
			$this->error404();
		}
		$this->load->view($this->config->item('template_user').'/index',$data);
	}
	
	function error404()
	{
		$this->view['title'] = 'HTTP Error 404';
		$this->view['keywords'] = "";
		$this->view['description'] = "";
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/error';
	}
}

?>