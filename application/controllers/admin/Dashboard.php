<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	function __construct(){
		parent::__construct(__FILE__, 'dashboard', '', false, true);
		
		$this->view['toptitle'] = 'Dashboard';
		$this->view['doctitle'] = 'Dashboard';
		
		//check login
		// if(!$this->user['logged_in']) redirect('admin/login');
	}
	
	function index()
	{
		reset_breadcrumb();
		$this->view['content'] = $this->controller.'/index';
		$greeting = "<p>Halo ".$this->user["name"].", selamat datang di portal ".get_main_config("site_title")."{info}</p>";
		$array_message = array();
		if($this->user['usertype']>2){	//if not superadmin or admin
			if($this->user['usertype']==3){	//if adminsekolah
				// check notifikasi
				$sql = "SELECT	COUNT(*) as notif_count
						FROM 	tb_notif_user AS a
								INNER JOIN tb_notif AS b ON a.`notif_id` = b.`id`
						WHERE	((b.`need_action` AND NOT a.`followed_up`)
								OR (NOT b.`need_action` AND NOT a.`followed_up`))
								AND a.`user_id` = ".$this->user['id']."
								AND NOT a.`followed_up`";
				$notif_count = intval(get_val($sql));
				if($notif_count){
					$array_message[] = "Anda memiliki $notif_count buah notifikasi <i class=\"fa fa-bell-o\"></i>";
				}
				$this->load->model("sekolahModel");
				if(!$this->sekolahModel->count_all()){
					$array_message[] = "Anda belum memiliki data sekolah, silahkan membuatnya melalui menu ".anchor(site_url($this->config->item("admin")."/sekolah"), "Master Data =&gt; Sekolah");
				}else{
					$sql = "SELECT	c.`name` AS user_name, b.`nama` AS sekolah_nama, d.name AS usertype_name
							FROM 	`tb_sekolah_user` AS a
									INNER JOIN `tb_sekolah` AS b ON a.`sekolah_id` = b.`id`
									INNER JOIN `tb_user` AS c ON a.`user_id` = c.`id`
									INNER JOIN `tb_usertype` AS d ON c.`usertype` = d.`id`
							WHERE	a.`sekolah_id` IN (SELECT id FROM `tb_sekolah_user` WHERE `user_id` = '".$this->user['id']."')
									AND NOT a.`status`";
					$rows = get_rows($sql);
					foreach($rows as $row){
						$array_message[] = $row->user_name." mengirim permintaan untuk bergabung sebagai ".$row->usertype_name." di ".$row->sekolah_nama." [".anchor(site_url($this->config->item("admin")."/sekolahuser"),"Lihat")."]";
					}
				}
			}elseif($this->user['usertype']==4){	//if pengajar
				$sql = "SELECT	b.`nama` AS sekolah_nama, d.name AS usertype_name
					FROM 	`tb_sekolah_user` AS a
							INNER JOIN `tb_sekolah` AS b ON a.`sekolah_id` = b.`id`
							INNER JOIN `tb_user` AS c ON a.`user_id` = c.`id`
							INNER JOIN `tb_usertype` AS d ON c.`usertype` = d.`id`
					WHERE	a.`user_id` = '".$this->user['id']."' AND NOT a.`status`";
				$rows = get_rows($sql);
				foreach($rows as $row){
					$array_message[] = "Permintaan Anda sebagai ".$row->usertype_name." di ".$row->sekolah_nama." <strong>belum</strong> disetujui oleh Admin Sekolah tersebut";
				}
			}
		}
		$greeting = str_replace("{info}", (count($array_message) ? ", berikut informasi untuk Anda:" : "."), $greeting);
		$data['greeting'] = $greeting;
		$data['messages'] = $array_message;
		
		$this->load->view('main', $data);
	}
	
	function noaccess($is_modal)
	{
		$this->is_modal = $is_modal;
		set_breadcrumb('',"No Access",true);
		$this->view['content'] = $this->controller.'/noaccess';
		$data["message"] = "You have no access to menu:";
		$data["access_item"] = $this->session->userdata('access_item');
		$this->load->view('main', $data);
	}
	
	function nosekolah()
	{
		set_breadcrumb('',"Tidak Ada Sekolah",true);
		$this->view['content'] = $this->controller.'/noaccess';
		$data["message"] = "Tidak ada sekolah yang aktif, tidak dapat membuka data:";
		$data["access_item"] = $this->session->userdata('access_item');
		$this->load->view('main', $data);
	}
}

?>