<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notif extends CI_Controller {

	//controller main properties
	var $controller = "notif";
	
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
	
	function notiftocall(){
		$contact_id = $this->input->post('contact_id');
		
		$user_id = get_val("select user_id from tb_contact where id = '$contact_id'");
		
		$phone = $this->input->post('phone');
		$name = $this->input->post('name');
		$purpose = $this->input->post('purpose');
		
		$this->load->library('smssender');
		$row_tpl = get_row("select * from tb_tpl_email where tag = 'admin_i_will_call'");
		$content = $row_tpl->body_sms;
		$content = str_replace("{visitor_name}", $name, $content);
		$content = str_replace("{visitor_phone}", $phone, $content);
		$content = str_replace("{visitor_purpose}", $purpose, $content);
		
		$row = array(
				'sekolah_id' => get_sekolah_id(),
				'title' => $row_tpl->subject,
				'subtitle' => $content,
				'body' => $content,
				'target' => '0',
				'user_id' => $user_id,
				'scope' => 'back',
				'need_action' => '1',
				'notif_date' => date('Y-m-d H:i:s'),
			);
		
		$notif_id = model_save('tb_notif', $row);
		
		$rows_user = get_rows("
				select 	b.id, b.phone, b.email
				from	tb_notif_user as a
						inner join tb_user as b on a.user_id = b.id
				where	a.notif_id = '$notif_id'
			");
			
		$this->load->library('mailer');
		
		foreach($rows_user as $row_user){
			
			// kirim sms
			$this->smssender->send($row_user->phone, $content);
			
			//email
			$inputs = array();
			$inputs['visitor_name'] = $name;
			$inputs['visitor_phone'] = $phone;
			$inputs['visitor_purpose'] = $purpose;

			$this->mailer->mail_common($row_user->id,'admin_i_will_call',$inputs);
		}
		
		echo json_encode(array(
				'status' => 1,
				'message' => "Admin telah diberitahu bahwa Anda akan menelepon",
			));
	}
	
	function requestcallme(){
		$phone = $this->input->post('phone');
		$name = $this->input->post('name');
		$purpose = $this->input->post('purpose');
		
		$this->load->library('smssender');
		$row_tpl = get_row("select * from tb_tpl_email where tag = 'admin_call_me'");
		$content = $row_tpl->body_sms;
		$content = str_replace("{visitor_name}", $name, $content);
		$content = str_replace("{visitor_phone}", $phone, $content);
		$content = str_replace("{visitor_purpose}", $purpose, $content);
		
		$row = array(
				'sekolah_id' => get_sekolah_id(),
				'title' => $row_tpl->subject,
				'subtitle' => $content,
				'body' => $content,
				'target' => '1',
				'scope' => 'back',
				'need_action' => '1',
				'notif_date' => date('Y-m-d H:i:s'),
			);
		
		$notif_id = model_save('tb_notif', $row);
		
		$rows_user = get_rows("
			select 	b.id, b.phone, b.email
			from	tb_notif_user as a
					inner join tb_user as b on a.user_id = b.id
			where	a.notif_id = '$notif_id'
			");
			
		$this->load->library('mailer');
		
		foreach($rows_user as $row_user){
			
			// kirim sms
			$this->smssender->send($row_user->phone, $content);
			
			//email
			$inputs = array();
			$inputs['visitor_name'] = $name;
			$inputs['visitor_phone'] = $phone;
			$inputs['visitor_purpose'] = $purpose;

			$this->mailer->mail_common($row_user->id,'admin_call_me',$inputs);
		}
		
		echo json_encode(array(
				'status' => 1,
				'message' => "Permintaan Anda telah dikirimkan",
			));
	}
}

?>