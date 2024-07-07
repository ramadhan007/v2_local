<?php
if($this->controller==$this->config->item('admin').'/login' || $this->controller==$this->config->item('admin').'/register'){
	$this->load->view($this->config->item('template_admin').'/login');
}
else{
	$uri = uri_string();
	$ar_uri = explode("/",$uri);
	if($ar_uri[0]==$this->config->item('admin')){
		$this->load->view($this->config->item('template_admin').'/index'.($this->is_modal ? "_plain" : ""));
	}
	else{
		$this->load->view($this->config->item('template_user').'/index');
	}
}
?>