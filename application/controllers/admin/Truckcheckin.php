<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TruckCheckIn extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'check in truck', '', true);
		/* Artinya:
		Inherit dari Admin_Controller
		title = check in truck
		model = ''
		user_access_check = true --> user_access_check true akan membuat controller ini hanya bisa diakses jika match usertype dengan level akses pada master menu
		jika kita set user_access_check = false, maka controller bisa diakses siapa saja dengan cara ketik manual
		
		 */
	}
	
	function index()
	{
		if(!$this->continue) return;
		echo "check in";
	}
	
	function user()
	{
		$row = get_row("select * from tb_user where username = 'superadmin'");
		$data['row'] = $row;
		$this->view['content'] = $this->controller.'/user';
		$this->load->view('main', $data);
	}
}

?>