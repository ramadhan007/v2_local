<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mainconfig extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'konfigurasi utama', 'list', true);
	}
	
	function index($offset = 0)
	{
		if(!$this->continue) return;
		//check task
		$this->task = $this->input->post('task');
		
		switch($this->task)
		{
			case 'edit':
				$cid = $this->input->post('cid');
				$this->edit($cid[0]);
				break;
			default:
				$this->_show();
		}
	}
	
	function _load_main_config()
	{
		$config = array();
		
		//content
		$config['site_title'] = get_main_config('site_title');
		$config['site_short'] = get_main_config('site_short');
		$config['meta_keywords'] = get_main_config('meta_keywords');
		while(strpos($config['meta_keywords'],"  ")!==false){
			$config['meta_keywords'] = str_replace("  "," ",$config['meta_keywords']);
		}
		$config['meta_keywords'] = str_replace(", ",",",$config['meta_keywords']);
		$config['meta_description'] = get_main_config('meta_description');
		
		//system
		$config['sender_email'] = get_main_config('sender_email');
		$config['sender_name'] = get_main_config('sender_name');
		$config['login_by'] = get_main_config('login_by');
		$config['user_activation'] = get_main_config('user_activation');
		$config['timezone'] = get_main_config('timezone');
		$config['currency'] = get_main_config('currency');
		
		//notif
		$config['enable_email'] = get_main_config('enable_email');
		$config['enable_whatsapp'] = get_main_config('enable_whatsapp');
		$config['enable_sms'] = get_main_config('enable_sms');
		
		return $config;
	}
	
	function _show(){
		$data['action'] = site_url($this->controller.'/edit');
		
		// template variables
		$this->view['toptitle'] = 'Konfigurasi Utama';
		$this->view['content'] = $this->controller.'/show';
		set_breadcrumb(site_url($this->controller),$this->title,true);
		
		//get config
		$config = $this->_load_main_config();
		
		//value to text
		$config['currency'] = get_text_by_value($this->model->currency(),$config['currency']);
		$data['config'] = $config;
		
		// load view
		$this->load->view('main', $data);
	}
	
	function edit(){
		$data['action'] = site_url($this->controller.'/updateData');
		$data['link_back'] = site_url($this->controller);
		
		// template variables
		$this->view['toptitle'] = 'Konfigurasi Utama [Edit]';
		$this->view['content'] = $this->controller.'/edit';
		set_breadcrumb($this->controller.'_edit','Edit',false);
		
		$data['config'] = $this->_load_main_config();
		
		$data['html'] = $this->_get_html($data['config']);
		
		// load view
		$this->load->view('main', $data);
	}
	
	function updateData(){
		// set validation properties
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			$this->edit();
		}
		else
		{
			// save config file
			// site/content
			$meta_keywords = $this->input->post('meta_keywords');
			while(strpos($meta_keywords,"  ")!==false){
				$meta_keywords = str_replace("  "," ",$meta_keywords);
			}
			$meta_keywords = str_replace(",",", ",$meta_keywords);
			save_main_config('site_title',$this->input->post('site_title'));
			save_main_config('site_short',$this->input->post('site_short'));
			save_main_config('meta_keywords',$meta_keywords);
			save_main_config('meta_description',$this->input->post('meta_description'));
			
			// system
			save_main_config('sender_email',$this->input->post('sender_email'));
			save_main_config('sender_name',$this->input->post('sender_name'));
			save_main_config('login_by',$this->input->post('login_by'));
			save_main_config('user_activation',$this->input->post('user_activation'));
			save_main_config('timezone',$this->input->post('timezone'));
			save_main_config('currency',$this->input->post('currency'));
			
			//notif
			save_main_config('enable_email',$this->input->post('enable_email'));
			save_main_config('enable_whatsapp',$this->input->post('enable_whatsapp'));
			save_main_config('enable_sms',$this->input->post('enable_sms'));
			
			// redirect to list page
			redirect($this->controller);
		}
	}
	
	// validation rules
	function _set_rules(){
		// site
		$this->form_validation->set_rules('site_title','Site Title','trim|required');
		$this->form_validation->set_rules('site_short','Site Short','trim|required');
		$this->form_validation->set_rules('meta_keywords','Meta Keywords','');
		$this->form_validation->set_rules('meta_description','Meta Description','');
		
		//system
		$this->form_validation->set_rules('sender_email','Sender Email','trim|required|valid_email');
		$this->form_validation->set_rules('sender_name','Sender Name','trim|required');
		$this->form_validation->set_rules('login_by','Login Dengan','trim|required');
		$this->form_validation->set_rules('user_activation','User Activation','trim|required');
		$this->form_validation->set_rules('timezone','Time Zone','trim|required');
		$this->form_validation->set_rules('currency','Currency','trim|required');
		
		//notif
		$this->form_validation->set_rules('enable_email','Enable Email','trim|required');
		$this->form_validation->set_rules('enable_whatsapp','Enable Whatsapp','trim|required');
		$this->form_validation->set_rules('enable_sms','Enable SMS','trim|required');
	}
	
	// date_validation callback
	function valid_date($str)
	{
		if(!preg_match("/^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$/", $str))
		{
			$this->form_validation->set_message('valid_date', 'date format is not valid. dd-mm-yyyy');
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function _get_html($config=array())
	{
		//prepare select/radio html
		$html = array();
		
		//login_by
		$array_data = array();
		$array_data[] = array('value' => 'username', 'text' => 'Username');
		$array_data[] = array('value' => 'email', 'text' => 'Email');
		$array_data[] = array('value' => 'phone', 'text' => 'Telp/HP');
		$html['login_by'] = html_select('login_by', $array_data, set_value('login_by', ($config['login_by'] ? $config['login_by'] : 'email')), '');
		
		//user activation
		$array_data = array();
		$array_data[] = array('value' => '0', 'text' => 'Tidak');
		$array_data[] = array('value' => '1', 'text' => 'Ya');
		$html['user_activation'] = html_select('user_activation', $array_data, set_value('user_activation', $config['user_activation']), '');
		
		//timezone
		$array_data = array();
		foreach(timezone_identifiers_list() as $timezone)
		{
			$array_data[] = array('value' => $timezone, 'text' => $timezone);
		}
		$html['timezone'] = html_select('timezone', $array_data, set_value('timezone', $config['timezone']), '', 'form-control select2');
		
		//default currency
		$array_data = $this->model->currency();
		$html['currency'] = html_select('currency', $array_data, set_value('currency', $config['currency']), ' - Pilih Currency - ', 'form-control select2');
		
		//notif enable_email
		$array_data = array();
		$array_data[] = array('value' => '0', 'text' => 'Tidak');
		$array_data[] = array('value' => '1', 'text' => 'Ya');
		$html['enable_email'] = html_select('enable_email', $array_data, set_value('enable_email', $config['enable_email']), '');
		
		//notif enable_whatsapp
		$array_data = array();
		$array_data[] = array('value' => '0', 'text' => 'Tidak');
		$array_data[] = array('value' => '1', 'text' => 'Ya');
		$html['enable_whatsapp'] = html_select('enable_whatsapp', $array_data, set_value('enable_whatsapp', $config['enable_whatsapp']), '');
		
		//notif enable_sms
		$array_data = array();
		$array_data[] = array('value' => '0', 'text' => 'Tidak');
		$array_data[] = array('value' => '1', 'text' => 'Ya');
		$html['enable_sms'] = html_select('enable_sms', $array_data, set_value('enable_sms', $config['enable_sms']), '');
		
		return $html;
	}
	
	//get posted data to row
	function _get_post_data(){
		$row = array('tag' => $this->input->post('tag'),
				'subject' => $this->input->post('subject'),
				'body' => $this->input->post('body')
			);
		return $row;
	}
	
	function _get_view_data($editmode=FALSE, $row=array())
	{
		// set common properties
		if($editmode)
		{
			$label = "Edit ";
			$method = "updateData";
		}
		else
		{
			$label = "Tambah ";
			$method = "addData";
		}
		
		$data['title'] = $label.$this->title;
		$data['message'] = '';
		$data['action'] = site_url($this->controller.'/'.$method.'/'.$this->_get_offset());
		
		//set link_back link
		$data['link_back'] = $this->_get_index_offset();
		
		$data['html'] = $this->_get_html($row);
		
		return $data;
	}
}

?>
