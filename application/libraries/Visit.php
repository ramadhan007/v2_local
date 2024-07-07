<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Visit {

	var	$CI;
	var $isAdmin;

	public function __construct($params = array())
	{
		// get instance of CI global class
		$this->CI =& get_instance();
		
		// if (session_id() === "") { session_start(); }
		
		$ar_uri = explode("/",uri_string());
		$this->isAdmin = $ar_uri[0]==$this->CI->config->item('admin');
		
		// unset mycampaign_session
		// unset($_SESSION['mycampaign_id']);
		
		//set timezone
		date_default_timezone_set(get_main_config('timezone'));
		
		$this->_preload_user();
		
		//get session login admin
		// $login_data = $this->CI->session->userdata('login_data_admin');
		if($this->CI->user['logged_in'])
		{
			$id = $this->CI->user['id'];
			
			$last_activity = $this->CI->session->userdata('user_last_activity');
			$time_since = time() - $last_activity;
			$interval = 60;
			if(!$last_activity || $time_since >= $interval)
			{
				$updated = $this->CI->db
					->set('lastvisitDate', date("Y-m-d H:i:s", time()))
					->where('id', $id)
					->update('tb_user');
				
				$this->CI->session->set_userdata('user_last_activity',time());
			}
		}
		
		// $this->_preset_param();
		
	}
	
	function _preload_user()
	{
		$ar_uri = explode("/",uri_string());
		$type = ($ar_uri[0]==$this->CI->config->item('admin')) ? "_admin" : "";
		$this->CI->user = is_logged_in($type);
	}
	
	function _preset_param(){
		if($this->isAdmin){
			//active_sekolah_id
			if(isset($_POST['active_sekolah_id'])){
				$sekolah_id = $this->CI->input->post('active_sekolah_id');
				
				$sekolah_id_old = $this->CI->session->userdata('active_sekolah_id');
				$this->CI->session->set_userdata('active_sekolah_id', $sekolah_id);
				
				if($sekolah_id_old!=$sekolah_id){
					$this->CI->session->unset_userdata($this->CI->config->item('admin').'/article_filter_category_id');
					
					$sekolah_tingkat = "";
					$sekolah_nama = "";
					if($sekolah_id){
						$row_sekolah = get_field_by_id('tb_sekolah', $sekolah_id, 'nama, tingkat');
						$sekolah_tingkat = $row_sekolah->tingkat;
						$sekolah_nama = $row_sekolah->nama;
					}
					
					$this->CI->session->set_userdata('active_sekolah_tingkat', $sekolah_tingkat);
					$this->CI->session->set_userdata('active_sekolah_nama', $sekolah_nama);
					
					$_SESSION['sekolah_id'] = $sekolah_id;
				}
			}
		}
	}
}
