<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	//controller main property
	var $controller = "user";
	var $title = "user";
	var $data = '';
	
	var $login_by = '';
	
	var $upload_config = array();
	
	var $dashboard = array();
	
	function __construct(){
		parent::__construct();
		
		// load library
		$this->load->library(array('mailer','message','template'));
		
		// load helper
		$this->load->helper(array('string'));
		// load model
		$this->load->model('userModel','',TRUE);
		$this->model = new userModel;
		
		//fill template parameters
		$this->view['title'] = 'User';
		
		$this->login_by = get_main_config('login_by');
		
		//upload config
		$this->upload_config['upload_path'] = 'userfiles/user';
		$this->upload_config['allowed_types'] = 'jpg|jpeg|png|gif';
		$this->upload_config['max_size'] = '5120';
		$this->upload_config['encrypt_name'] = TRUE;
	}
	
	function index(){
		//check login
		if(!$this->user['logged_in']){
			$this->login();
		}
		else
		{
			$data = array();
			$data['body'] = '<p>Anda berada di user area. Silahkan mengakses menu yang tersedia</p>';
			$data['button'] = '<a class="btn btn-primary" href="'.site_url().'">Home</a>';
		
			$dashboard = array();
			$dashboard['title'] = "Dashboard";
			$dashboard['content'] = $this->load->view(template_view_dir($this->controller.'/info'), $data, true);
			
			$this->content($dashboard);
		}
	}
	
	function content($dashboard){
		$this->view['title'] = $dashboard['title'];
		$this->view['content'] = $this->controller."/index";
		$this->view['show_sidebar'] = true;
		
		$this->dashboard = $dashboard;
		$this->load->view('main');
	}
	
	function contentplain($dashboard){
		$this->view['title'] = '';
		$this->view['content'] = $this->controller."/index";
		
		$this->dashboard = $dashboard;
		$this->load->view(template_view_dir('plain'));
	}
	
	function dashboard(){
		$this->index();
	}
	
	function mybooking(){
		$user_id = $this->user['id'];
		
		$sql = "SELECT	a.id, CONV(a.`booking_number`,10,36) AS booking_code, c.`name`, b.`depart_date`, a.`bed`, fn_get_list_item_text('hotel_bed', a.`bed`) AS bed_text, a.`num_pax`, a.`price`, a.`dp_price`
		FROM 	tb_umroh_booking AS a
				INNER JOIN `tb_umroh_batch` AS b ON a.`umroh_batch_id` = b.`id`
				INNER JOIN `tb_umroh_paket` AS c ON b.`umroh_paket_id` = c.`id`
		WHERE	a.`user_id` = '$user_id' and not a.deleted";
		
		$data['rows'] = get_rows($sql);
	
		$dashboard = array();
		$dashboard['title'] = "Pesanan Saya";
		$dashboard['content'] = $this->load->view(template_view_dir($this->controller.'/booking'), $data, true);
		
		$this->content($dashboard);
	}
	
	function myjamaah($umroh_booking_id=''){
		
		$user_id = $this->user['id'];
		
		$sql = "SELECT	a.id, CONV(a.`booking_number`,10,36) AS booking_code, c.`name`, b.`depart_date`
			FROM 	tb_umroh_booking AS a
					INNER JOIN `tb_umroh_batch` AS b ON a.`umroh_batch_id` = b.`id`
					INNER JOIN `tb_umroh_paket` AS c ON b.`umroh_paket_id` = c.`id`
			WHERE	a.user_id = '$user_id' and not a.deleted";
			
		if($umroh_booking_id) $sql .= " AND a.id = '$umroh_booking_id'";
		
		$rows_booking = get_rows($sql);
		
		$data['rows_booking'] = $rows_booking;
		
		$ar_rows = array();
		
		foreach($rows_booking as $row_booking){
			$sql = "SELECT	a.id, a.nama, (b.price*if(a.pay_status,a.currency_rate,ifnull(e.rate_real,0))) as price,
						fc_umroh_jamaah_payment_paid(a.id) as total_paid,
						a.requirement_status,
						fn_get_list_item_short('jamaah_requirement_status',a.requirement_status) as requirement_status_text,
						fn_get_list_item_icon('jamaah_requirement_status',a.requirement_status) as requirement_status_icon,
						fn_get_list_item_class('jamaah_requirement_status',a.requirement_status) as requirement_status_class,
						a.inventory_status,
						fn_get_list_item_short('jamaah_inventory_status',a.inventory_status) as inventory_status_text,
						fn_get_list_item_icon('jamaah_inventory_status',a.inventory_status) as inventory_status_icon,
						fn_get_list_item_class('jamaah_inventory_status',a.inventory_status) as inventory_status_class,
						a.flight_status,
						fn_get_list_item_short('jamaah_flight_status',a.flight_status) as flight_status_text,
						fn_get_list_item_icon('jamaah_flight_status',a.flight_status) as flight_status_icon,
						fn_get_list_item_class('jamaah_flight_status',a.flight_status) as flight_status_class,
						a.hotel_status,
						fn_get_list_item_short('jamaah_hotel_status',a.hotel_status) as hotel_status_text,
						fn_get_list_item_icon('jamaah_hotel_status',a.hotel_status) as hotel_status_icon,
						fn_get_list_item_class('jamaah_hotel_status',a.hotel_status) as hotel_status_class,
						a.visa_status,
						fn_get_list_item_short('jamaah_visa_status',a.visa_status) as visa_status_text,
						fn_get_list_item_icon('jamaah_visa_status',a.visa_status) as visa_status_icon,
						fn_get_list_item_class('jamaah_visa_status',a.visa_status) as visa_status_class
				FROM 	tb_umroh_jamaah AS a
						INNER JOIN tb_umroh_booking AS b ON (a.`umroh_booking_id` = b.id and not b.deleted)
						INNER JOIN tb_umroh_batch AS c ON b.umroh_batch_id = c.id
						INNER JOIN tb_umroh_paket AS d ON c.`umroh_paket_id` = d.`id`
						LEFT JOIN tb_currency_rate as e on (e.sekolah_id = b.sekolah_id AND d.currency = e.curr_from AND not e.deleted)
				WHERE	b.`user_id` = '$user_id'
						AND b.id = '$row_booking->id'";
			
			$ar_rows['rows_'.$row_booking->id] = get_rows($sql);
		}
		
		$data['ar_rows'] = $ar_rows;
		
		$sql = "
			SELECT	a.id, CONV(a.booking_number,10,36) AS booking_code
			FROM 	tb_umroh_booking as a
			WHERE 	a.`user_id` = '$user_id' and not a.deleted
			ORDER 	BY CONV(a.booking_number,10,36) ASC";
		
		$rows_batch = get_rows($sql);
		$array_data = array();
		foreach($rows_batch as $row_batch){
			$array_data[] = array(
					'value' => $row_batch->id,
					'text' => $row_batch->booking_code,
				);
		}
		$data['filter_umroh_booking_id'] = html_select('filter_umroh_booking_id', $array_data, $umroh_booking_id, 'Semua', 'form-control');
		
		$dashboard = array();
		$dashboard['title'] = "Jamaah Saya";
		// date_mysql2dmy($row_booking->depart_date,0)
		$dashboard['content'] = $this->load->view(template_view_dir($this->controller.'/jamaah/list'), $data, true);
		
		$this->content($dashboard);
	}
	
	function myjamaahedit($umroh_booking_jamaah_id){
		if($this->user['logged_in']){
			$user_id = $this->user['id'];
			$row = get_row("
				select 	a.*
				from 	tb_umroh_jamaah as a
						inner join tb_umroh_booking as b on (a.umroh_booking_id = b.id and not b.deleted)
				where 	a.id = '$umroh_booking_jamaah_id' AND b.user_id = '$user_id'");
			if(count($row)){
				$data = array();
				$data['row'] = $row;
				$data['html'] = $this->_jamaah_get_html(0, $row);
				
				$dashboard = array();
				$dashboard['title'] = "Edit Jamaah";
				$dashboard['content'] = $this->load->view(template_view_dir($this->controller.'/jamaah/edit'), $data, true);
		
				$this->content($dashboard);
			}else{
				$this->error404();
			}
		}else{
			$this->error404();
		}
	}
	
	function myjamaahsave(){
		
		$user_id = $this->user['id'];
		$ar_jamaah = $this->input->post('jamaah');
		$jamaah = $ar_jamaah[0];
		
		$row = get_row("
				select 	a.*
				from 	tb_umroh_jamaah as a
						inner join tb_umroh_booking as b on (a.umroh_booking_id = b.id and not b.deleted)
				where 	a.id = '".$jamaah['id']."' AND b.user_id = '$user_id'"
			);
		
		if(count($row)){
			$data = array();
			foreach($jamaah as $key=>$val){
				if($key!='id'){
					if($key=='tanggal_lahir' || $key=='passport_tgl_keluar' || $key=='passport_masa_berlaku'){
						$data[$key] = date_dmy2mysql($val);
					}else{
						$data[$key] = $val;
					}
				}
			}
			$data['user_update'] = $user_id;
			$data['date_update'] = date('Y-m-d H:i:s');
			model_update('tb_umroh_jamaah', $jamaah['id'], $data);
			
			redirect($this->controller.'/myjamaah');
			
		}else{
			$this->error404();
		}
	}
	
	function _jamaah_get_html($index, $row=array()){
		//prepare select/radio html
		$html = array();
		
		//jenis_kelamin
		$array_data = get_list_item('jenis_kelamin');
		$html['jenis_kelamin'] = html_select("jamaah[$index][jenis_kelamin]", $array_data, set_value("jamaah[$index][jenis_kelamin]", ($row ? $row->jenis_kelamin : '')),'- Jenis Kelamin -');
		
		//status_nikah
		$array_data = get_list_item('status_nikah');
		$html['status_nikah'] = html_select("jamaah[$index][status_nikah]", $array_data, set_value("jamaah[$index][status_nikah]", ($row ? $row->status_nikah : '')),'- Status Pernikahan -');
		
		//mahram_hubungan
		$array_data = get_list_item('hubungan_keluarga');
		$html['mahram_hubungan'] = html_select("jamaah[$index][mahram_hubungan]", $array_data, set_value("jamaah[$index][mahram_hubungan]", ($row ? $row->mahram_hubungan : '')),'- Hubungan Mahram -');
		
		//pernah_haji_umroh
		$array_data = get_list_item('pilihan_pernah');
		$html['pernah_haji_umroh'] = html_select("jamaah[$index][pernah_haji_umroh]", $array_data, set_value("jamaah[$index][pernah_haji_umroh]", ($row ? $row->pernah_haji_umroh : '')),'- Pernah Haji / Umroh -');
		
		//bill_to
		$array_data = get_list_item('bill_notif_to');
		$html['bill_to'] = html_select("jamaah[$index][bill_to]", $array_data, set_value("jamaah[$index][bill_to]", ($row ? $row->bill_to : 'user')));
		
		//notif_to
		$html['notif_to'] = html_select("jamaah[$index][notif_to]", $array_data, set_value("jamaah[$index][notif_to]", ($row ? $row->notif_to : 'user')));
		
		return $html;
	}
	
	function mypayment($umroh_jamaah_id=''){
		
		$user_id = $this->user['id'];
		
		$sql = "SELECT	a.id, CONV(c.`booking_number`,10,36) AS booking_code, e.`name` AS paket_name, d.`depart_date`, b.`nama` AS jamaah_nama, fn_get_list_item_text('pay_type', a.`pay_type`) AS pay_type_text,
				fn_get_list_item_short('pay_method', a.`pay_method`) AS pay_method_text, a.`amount`,
				fn_get_list_item_text('pay_status', a.`pay_status`) AS pay_status_text, a.`request_expire`,
				a.payproof_submitted, a.payproof_path, a.payproof_filename
		FROM 	tb_umroh_jamaah_payment AS a
				INNER JOIN tb_umroh_jamaah AS b ON a.umroh_jamaah_id = b.id
				INNER JOIN tb_umroh_booking AS c ON b.umroh_booking_id = c.id
				INNER JOIN `tb_umroh_batch` AS d ON c.`umroh_batch_id` = d.`id`
				INNER JOIN `tb_umroh_paket` AS e ON d.`umroh_paket_id` = e.`id`
		WHERE	c.`user_id` = '$user_id'";
		
		if($umroh_jamaah_id) $sql .= " AND b.id = '$umroh_jamaah_id'";
		
		$data['rows'] = get_rows($sql);
		
		$sql = "
			SELECT	a.id, CONCAT(CONV(b.booking_number,10,36), ' - ', a.`nama`) AS nama
			FROM 	tb_umroh_jamaah AS a
					INNER JOIN tb_umroh_booking AS b ON (a.`umroh_booking_id` = b.`id` AND NOT b.`deleted`)
			WHERE 	b.`user_id` = '$user_id' AND NOT a.deleted
			ORDER 	BY CONV(b.booking_number,10,36) ASC, a.`nama` ASC";
		
		$rows_batch = get_rows($sql);
		$array_data = array();
		foreach($rows_batch as $row_batch){
			$array_data[] = array(
					'value' => $row_batch->id,
					'text' => $row_batch->nama,
				);
		}
		$data['filter_umroh_jamaah_id'] = html_select('filter_umroh_jamaah_id', $array_data, $umroh_jamaah_id, 'Semua', 'form-control');
		
		$dashboard = array();
		$dashboard['title'] = "Pembayaran Saya";
		$dashboard['content'] = $this->load->view(template_view_dir($this->controller.'/payment'), $data, true);
		
		$this->content($dashboard);
	}
	
	function invoice($umroh_payment_id){
		$user_id = $this->user['id'];
		
		$sql = "SELECT	c.sekolah_id, m.`alamat` AS sekolah_alamat, m.`phone` AS sekolah_phone, m.`whatsapp` AS sekolah_whatsapp,
				m.`email` AS sekolah_email,
				a.id, a.date_insert, a.`request_expire`,
				fn_get_list_item_text('pay_type', a.`pay_type`) AS pay_type_text, b.nama,
				CONV(c.`booking_number`,10,36) AS booking_code, e.`name` AS umroh_paket_name, a.`amount`,
				b.bill_to, b.bill_to_name, b.bill_to_address, b.bill_to_phone,
				b.alamat_sekarang, b.alamat_kodepos, b.telp_hp,
				u.name AS user_name, u.address AS user_address, u.phone AS user_phone
		FROM 	tb_umroh_jamaah_payment AS a
				INNER JOIN tb_umroh_jamaah AS b ON a.umroh_jamaah_id = b.id
				INNER JOIN tb_umroh_booking AS c ON b.umroh_booking_id = c.id
				INNER JOIN `tb_umroh_batch` AS d ON c.`umroh_batch_id` = d.`id`
				INNER JOIN `tb_umroh_paket` AS e ON d.`umroh_paket_id` = e.`id`
				INNER JOIN tb_sekolah AS m ON c.`sekolah_id` = m.`id`
				LEFT JOIN tb_user AS u ON c.user_id = u.id
		WHERE	c.`user_id` = '$user_id' and a.id = '$umroh_payment_id'";
		
		$row = get_row($sql);
		
		$data['row'] = $row;
		
		if($row->bill_to=='user'){
			$data['bill_to_name'] = $row->user_name;
			$data['bill_to_address'] = $row->user_address;
			$data['bill_to_phone'] = $row->user_phone;
		}elseif($row->bill_to=='jamaah'){
			$data['bill_to_name'] = $row->nama;
			$data['bill_to_address'] = $row->alamat_sekarang.($row->alamat_kodepos ? ", " : "").$row->alamat_kodepos;
			$data['bill_to_phone'] = $row->telp_hp;
		}else{
			$data['bill_to_name'] = $row->bill_to_name;
			$data['bill_to_address'] = $row->bill_to_address;
			$data['bill_to_phone'] = $row->bill_to_phone;
		}
		
		$data['rows_bank_account'] = get_rows("
			SELECT	*
			FROM 	tb_bank_account
			WHERE	sekolah_id = '$row->sekolah_id'
		");
		
		$this->load->helper('senofile');
		
		$doc = array();
		$doc['title'] = "Tagihan No: $row->id/$row->sekolah_id/INV/MMM";
		$doc['filename'] = "Tagihan No ".fixFileName("$row->id/$row->sekolah_id/INV/MMM").".pdf";
		$doc['content'] = $this->load->view('master/payment/invoice', $data, true);
		
		$this->view['title'] = $doc['title'];
		$this->view['content'] = "common/document";
		$this->view['js_pdf'] = "content=content.replace('src=\"../../images/logo_long.png\"', 'src=\"images/logo_long.png\"');";
		
		$this->load->view(template_view_dir('plain'), $doc);
	}
	
	function payproofupload(){
		$user_id = $this->user['id'];
		$umroh_jamaah_payment_id = $this->input->post('umroh_jamaah_payment_id');
		
		$config['upload_path'] = 'userfiles/user_'.$user_id.'/payproofs';
		$config['allowed_types'] = 'bmp|jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx';
		$config['max_size'] = '10240';
		$config['encrypt_name'] = TRUE;

		$this->load->helper('senofile');
		$this->load->library('upload');
		$base_upload_dir = $config['upload_path'];
		$upload_success = false;
		
		//check payproof_file
		if(is_uploaded_file($_FILES['payproof_file']['tmp_name'])){
			//upload payproof
			$this->upload->initialize($config);
			prepDir($config['upload_path']);
			if (!$this->upload->do_upload('payproof_file')){	//upload gagal
				$ar_return = array(
						'status' => false,
						'message' => $this->upload->display_errors('',''),
					);
				echo json_encode($ar_return);
			}else{	// upload sukses
				$up_file = $this->upload->data();
				
				$sql = "update 	tb_umroh_jamaah_payment
						set 	payproof_submitted = '1',
								payproof_path = '".$config['upload_path'].'/'.$up_file['file_name']."',
								payproof_filename = '".$up_file['orig_name']."'
						where	id = '$umroh_jamaah_payment_id'";
						
				run_query($sql);
				
				$ar_return = array(
						'status' => true,
						'path' => $config['upload_path'].'/'.$up_file['file_name'],
						'filename' => $up_file['orig_name'],
						'message' => '',
					);
				echo json_encode($ar_return);
			}
		}
		else{
			$ar_return = array(
					'status' => false,
					'message' => $this->upload->display_errors('',''),
				);
			echo json_encode($ar_return);
		}
	}
	
	function _getinfo()
	{
		return array();
		$this->load->model('infoModel');
		$rows = $this->infoModel->get_rows();
		$ret_array = array();
		if(count($rows))
		{
			foreach($rows as $row)
			{
				$ret_array[] = array(
						'title' => $row->title.'&nbsp;'.($row->new ? img('images/new.gif') : ''),
						'body' => $row->body
					);
			}
		}
		else
		{
			$ret_array[] = array(
					'title' => '',
					'body' => 'Tidak ada info terbaru'
				);
		}
		return $ret_array;
	}
	
	function forgetpass()
	{
		$this->view['toptitle'] = "User - Password Reset";
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/forget";
		$data['message'] = "Please enter the e-mail address for your account. A reset link will be sent to you. Once you navigate to the link, you will be able to choose a new password for your account.";
		$data['action'] = site_url($this->controller.'/forgetpassSendLink');
		$data['link_back'] = site_url($this->controller.'/login');

		$this->load->view($this->config->item('template_user').'/index', $data);
	}
	
	function forgetpassSendLink()
	{
		$email = $this->input->post('email');
		if(trim($email)=="")
		{
			$data['user_found'] = FALSE;
			$data['message'] = "<blink>You have not enter your email address</blink>";
			$data['action'] = site_url($this->controller.'/forgetpassSendLink');
		}
		else
		{
			$id = $this->model->get_field_by_field('email',$email,'id');
			if($id){
				$id_tmp = '1';
				while($id_tmp!='')
				{
					$scode = random_string('alnum', 20);
					$id_tmp = $this->model->get_field_by_field('scode',$scode,'id');
				}
				$row=array('scode'=>$scode);
				$this->model->update($id, $row);
				
				// send email
				$inputs = array();
				$inputs['reset_link'] = site_url('user/forgetpassReset/'.$scode);

				$this->mailer->mail_common($id,'link_reset_pass',$inputs);
				
				$data['user_found'] = TRUE;
				$data['message'] =  get_tpl_message('forgetpass_sendlink_user_found');
				$data['action'] = site_url($this->controller.'/login');
			}
			else{
				$data['user_found'] = FALSE;
				$data['message'] = "<blink>".get_tpl_message('forgetpass_sendlink_user_not_found')."</blink>";
				$data['action'] = site_url($this->controller.'/forgetpassSendLink');
			}
		}
		
		$this->view['toptitle'] = "User - Password Reset";
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/forgetpasssendlink";

		$data['link_ok'] = site_url($this->controller.'/login');
		$data['link_back'] = site_url($this->controller.'/login');

		$this->load->view($this->config->item('template_user').'/index', $data);
	}

	function forgetpassReset($scode='',$message='')
	{
		if(trim($scode)!="")
		{
			$data['id'] = '';
			$data['scode'] = '';
			
			$row = $this->model->get_field_by_field('scode',$scode,'id, title, name');
			
			if($row)
			{
				$id = $row->id;
				
				$data['data_found'] = TRUE;
				$msg = $this->message->render($id,'forgetpass_reset_user_found');
				$data['message'] = $message ? $message : $msg['body'];
				
				$data['action'] = site_url($this->controller.'/forgetpassDoReset');
				
				$this->session->set_userdata($this->controller.'_scode', $scode);
			}
			else
			{
				$data['data_found'] = FALSE;
				$inputs = array('forgetpass_link' => site_url($this->controller.'/forgetpass'));
				$msg = $this->message->render('','forgetpass_reset_user_not_found',$inputs);
				$data['message'] = $message ? $message : $msg['body'];
				$data['action'] = site_url($this->controller.'/login');
			}
		}
		else
		{
			$data['data_found'] = FALSE;
			$inputs = array('forgetpass_link' => site_url($this->controller.'/forgetpass'));
			$msg = $this->message->render($id,'forgetpass_reset_user_not_found',$inputs);
			$data['message'] = $message ? $message : $msg['body'];
			$data['action'] = site_url($this->controller.'/login');
		}

		$this->view['toptitle'] = "User - Password Reset";
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/forgetpassreset";
		
		$data['link_ok'] = site_url($this->controller.'/login');
		$data['link_member'] = site_url($this->controller.'/forgetpass');

		$this->load->view($this->config->item('template_user').'/index', $data);
	}

	function forgetpassDoReset()
	{
		//simpan variable post
		$scode = $this->session->userdata($this->controller.'_scode');
		$id = $this->model->get_field_by_field('scode',$scode,'id');
		
		$password = $this->input->post('password');
		$passconf = $this->input->post('passconf');

		// check field
		if(trim($password)=='' || trim($passconf)=='')
		{
			$this->forgetpassReset($scode,'<blink>Please fill the password</blink>');
		}
		else
		{
			if(trim($password)!=trim($passconf))
			{
				$this->forgetpassReset($scode,'<blink>Your password confirmation is not match</blink>');
			}
			else
			{
				//do reset pass
				$password =  $this->input->post('password');
				$this->model->resetpass($id,$password);
				
				// send email
				$inputs = array();
				$inputs['login_link'] = site_url($this->controller.'/login');
				$inputs['password'] = $password;

				$this->mailer->mail_common($id,'reset_pass_ok',$inputs);
				
				$inputs = array('login_link' => site_url($this->controller.'/login'));
				$msg = $this->message->render($id,'forgetpass_reset_ok',$inputs);

				$data['message'] = $msg['body'];
				
				$this->view['toptitle'] = $msg['title'];
				$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/forgetpassresetok";

				$data['link_ok'] = site_url($this->controller.'/login');

				$this->load->view($this->config->item('template_user').'/index', $data);
			}
		}
	}
	
	function checklogin(){
		// $this->user['logged_in'] = 1;
		echo json_encode($this->user);
	}

	function login()
	{
		if($this->user['logged_in'])
		{
			redirect($this->controller.'/profile');
		}
		else
		{
			$data = $this->session->userdata('tmp_login_data');
			$this->session->unset_userdata('tmp_login_data');
			if(!$data){
				$this->load->library('crypt');
				$remember = $this->crypt->decrypt(get_cookie("login_remember"));
				
				$data['checked'] = $remember=='yes' ? 'checked="checked"' : '';
				$data['login_by'] = $remember=='yes' ? $this->crypt->decrypt(get_cookie("login_".$this->login_by)) : '';
				$data['password'] = $remember=='yes' ? $this->crypt->decrypt(get_cookie("login_password")) : '';
			}
			
			$this->view['title'] = "Login User";
			$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/login";
			$this->load->view($this->config->item('template_user').'/index', $data);
		}
	}
	
	function doLogin($byajax = '')
	{
		$login_by = trim($this->input->post($this->login_by));
		$password = $this->input->post('password');
		$remember = $this->input->post('remember');
		$retlogin = $this->model->checklogin($login_by, $password);
		if(!$retlogin['return'])
		{
			if($byajax){
				$ar_return = array('status' => 0, 'message' => strip_tags(get_tpl_message('login_failed_'.$this->login_by)));
				echo json_encode($ar_return);
			}
			else{
				$data['checked'] = $remember=='yes' ? 'checked="checked"' : '';
				$data['login_by'] = $login_by;
				$data['password'] = $password;
			
				$this->session->set_userdata('message', get_tpl_message('login_failed_'.$this->login_by));
				$this->session->set_userdata('tmp_login_data', $data);
				$this->login();
			}
		}
		else
		{
			if($remember=='yes')
			{
				$this->load->library('crypt');
				$expire = '86400';
				set_cookie("login_".$this->login_by, $this->crypt->encrypt($login_by), $expire);
				set_cookie("login_password", $this->crypt->encrypt($password), $expire);
				set_cookie("login_remember", $this->crypt->encrypt($remember), $expire);
			}
			else
			{
				delete_cookie("login_".$this->login_by);
				delete_cookie("login_password");
				delete_cookie("login_remember");
			}
			$login_data['is_logged_in'] = TRUE;
			$login_data['user'] = $retlogin['row'];
			$this->session->set_userdata('login_data', $login_data);
			if($byajax){
				$ar_return = array('status' => 1, 'message' => '', 'row' => $retlogin['row']);
				echo json_encode($ar_return);
			}else{
				$this->user = is_logged_in();
				$this->index();
			}
		}
	}
	
	function _clear_upload_error(){
		$this->session->unset_userdata($this->controller.'_upload_error_file_picture');
	}
	
	function myprofile($recall = false)
	{
		if(!$recall) $this->_clear_upload_error();
		
		$row = model_get_by_id('tb_user', $this->user['id']);
		$row = fix_base_url($row);
		$data['password'] = $row->password;
		if($recall){
			
		}
		else{
			$row->password = "";
			$data['row'] = $row;
		}
		
		$data['picture'] = $row->picture ? $row->picture : base_url('images/no-user.png');
		
		$dashboard = array();
		$dashboard['title'] = "Edit Profile";
		$dashboard['content'] = $this->load->view(template_view_dir($this->controller.'/profile'), $data, true);
		
		$this->content($dashboard);
	}
	
	function myprofileSave(){
		// get id
		$id = $this->user['id'];
		
		// set validation properties
		$this->_set_rules_profile();
		
		//password validation
		$setpass = $this->input->post('setpass');
		$password = '';
		if($setpass){	//sebelumnya pasti tidak ada password
			$pass1 = $this->input->post('password');
			$pass2 = $this->input->post('passconf');
			if($pass1 || $pass2){	//jika salah satu password diisi, maka validasi
				$this->_set_rules_pass(TRUE);
				$this->load->library('crypt');
				$password = $this->crypt->encrypt($pass1);
			}
		}
		else{
			if($this->model->get_field_by_id($id, 'password')){	//jika sudah punya password
				$pass1 = $this->input->post('password');
				$pass2 = $this->input->post('passconf');
				if($pass1 || $pass2){	//jika salah satu password diisi, maka validasi
					$this->_set_rules_pass(TRUE);
					$this->load->library('crypt');
					$password = $this->crypt->encrypt($pass1);
				}
				else{
					$password = $this->model->get_field_by_id($id, 'password');
				}
			}
		}
		
		//username changed
		$username = $this->input->post('username');
		if($username!=$this->model->get_field_by_id($id, 'username')){
			if($username){
				$this->_set_rules_username(TRUE);
			}else{
				$this->_set_rules_username(TRUE, TRUE);
			}
		}
		else{
			$this->_set_rules_username(FALSE);
		}
		
		//phone changed
		$phone_changed = false;
		$phone = $this->input->post('phone');
		if($phone!=$this->model->get_field_by_id($id, 'phone')){
			$phone_changed = true;
			if($phone){
				$this->_set_rules_phone(TRUE);
			}else{
				$this->_set_rules_phone(TRUE, TRUE);
			}
		}
		else{
			$this->_set_rules_phone(FALSE);
		}
		
		//email changed
		$email = $this->input->post('email');
		if($email!=$this->model->get_field_by_id($id, 'email')){
			if($email){
				$this->_set_rules_email(TRUE);
			}else{
				$this->_set_rules_email(TRUE, TRUE);
			}
		}
		else{
			$this->_set_rules_email(FALSE);
		}
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{	
			$this->myprofile(true);
		}
		else
		{
			$this->load->helper('senofile');
			$up_file_picture = uploadFile('file_picture', $this->upload_config);
			$upload_success = $up_file_picture['status'];
			
			if(!$upload_success){
				$this->myprofile(true);
			}
			else{
				// save data
				$row = $this->_get_post_data_profile($password);
				
				//get old picture
				$picture_old = $this->model->get_field_by_id($id, 'picture');
				if($picture_old){
					if($this->input->post('delete_picture')=='1'){
						$picture_old_path = rem_base_url($picture_old);
						if(strpos($picture_old_path,'://')===false) delFile($picture_old_path);
						$row['picture'] = '';
					}
				}
				if($up_file_picture['path']) $row['picture'] = put_base_url(base_url($up_file_picture['path']));
				
				//save record
				$this->model->update($id,$row);
				
				//delete old file if new file is uploaded
				if($up_file_picture['path'] && $this->input->post('delete_picture')!='1'){
					$picture_old_path = rem_base_url($picture_old);
					if(strpos($picture_old_path,'://')===false) delFile($picture_old_path);
				}
				
				//refresh session
				$row = $this->model->get_field_by_id($id, 'id, email, phone, name, picture, usertype');
				$row->picture = fix_base_url($row->picture);
				$row->login_with = $this->user['login_with'];
				$login_data['is_logged_in'] = TRUE;
				$login_data['user'] = $row;
				$this->session->set_userdata('login_data', $login_data);
				$this->user = is_logged_in();
				
				//update phone if changed
				if($phone_changed && $row->phone) check_insert_phone_contact($row->phone);
				
				//back to profile
				$this->session->set_flashdata('message', 'Detil profile sudah diupdate');
				redirect($this->controller.'/profile');
			}
		}
	}
	
	function _set_rules_profile(){
		//mandatory field
		$this->form_validation->set_rules('name','Nama','trim|required');
		$this->form_validation->set_rules('address','Alamat','trim');
		$this->form_validation->set_rules('setpass','','');
	}
	
	function _get_post_data_profile($password)
	{
		$row = array(
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'name' => $this->input->post('name'),
			'phone' => $this->input->post('phone'),
			'address' => $this->input->post('address'),
			'password' => $password,
		);
		
		return $row;
	}
	
	function registerAffiliate()
	{
		if($this->user['logged_in']){
			$user_id = $this->user['id'];
			if($this->input->post('later')=='1'){
				$id = 1;
			}
			else{
				$row = array(
						'user_id' 	=> $user_id,
						'name' 		=> $this->input->post('name'),
						'number' 	=> $this->input->post('number'),
						'bank' 		=> $this->input->post('bank'),
					);
				$id = model_save('tb_user_bank', $row);
			}
			if($id){
				$this->model->update($user_id, array('usertype' => '5'));
				
				//refresh session
				$row = $this->model->get_field_by_id($user_id, 'id, email, name, usertype');
				$row->login_with = $this->user['login_with'];
				$login_data['is_logged_in'] = TRUE;
				$login_data['user'] = $row;
				$this->session->set_userdata('login_data', $login_data);
				$this->user = is_logged_in();
				
				echo json_encode(array(
						'success' => 1,
						'value'	=> model_get_field_by_id('tb_usertype', $this->user['usertype'], 'name'),
						'message' => '',
					));
			}else{
				echo json_encode(array(
						'success' => 0,
						'message' => 'Terjadi kesalahan saat menyimpan data. Silahkan coba lagi!',
					));
			}
		}else{
			echo json_encode(array(
					'success' => 0,
					'message' => 'Anda tidak login :)',
				));
		}
	}
	
	function requestAgent()
	{
		if($this->user['logged_in'] && $this->user['usertype']==5){
			$user_id = $this->user['id'];
			$row = array(
					'phone' => $this->input->post('phone'),
					'whatsapp' => $this->input->post('whatsapp'),
					'location_lat' => $this->input->post('location_lat'),
					'location_lng' => $this->input->post('location_lng'),
					'location_desc' => $this->input->post('location_desc'),
					'usertype' => '4',
				);
			$email = $this->model->get_field_by_id($user_id, 'email');
			if(!$email){
				if($this->model->check_data($email)){
					echo json_encode(array(
							'success' => 0,
							'message' => 'Email telah digunakan',
						));
					exit();
				}
				else{
					$row['email'] = $this->input->post('email');
				}
			}
			$this->model->update($user_id, $row);
			
			//refresh session
			$row = $this->model->get_field_by_id($user_id, 'id, email, name, usertype');
			$row->login_with = $this->user['login_with'];
			$login_data['is_logged_in'] = TRUE;
			$login_data['user'] = $row;
			$this->session->set_userdata('login_data', $login_data);
			$this->user = is_logged_in();
			
			echo json_encode(array(
					'success' => 1,
					'value'	=> model_get_field_by_id('tb_usertype', $this->user['usertype'], 'name'),
					'message' => '',
				));
			
		}else{
			echo json_encode(array(
					'success' => 0,
					'message' => 'Anda tidak login :)',
				));
		}
	}
	
	function logout($byajax = '')
	{
		$this->session->unset_userdata($this->controller.'_id');
		$this->session->unset_userdata('login_data');
		$this->session->unset_userdata('user_last_activity');
		if($byajax){
			echo json_encode(array(
					'success' => true,
					'message' => '',
				));
		}
		else{
			redirect('');
		}
	}
	
	function register()
	{
		if(!$this->user['logged_in']){
			$this->view['title'] = "Pendaftaran User";
			$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/register';
			
			$data['html'] = $this->_get_html();
			$data['action'] = site_url($this->controller.'/registerSave');
			$data['link_back'] = site_url($this->controller.'/profile');
	
			$this->load->view($this->config->item('template_user').'/index', $data);
		}
		else{
			$this->index();
		}
	}
	
	// validation rules
	function _set_rules_register(){
		//mandatory field
		$this->form_validation->set_rules('name','Nama Lengkap','trim|required');
		$rules = "trim|required";
		if($this->login_by=='email') $rules .= "|valid_email";
		$rules .= "|is_unique[tb_user.phone]";
		$this->form_validation->set_rules($this->login_by,ucfirst($this->login_by),$rules);
		$this->form_validation->set_rules('password','Password','trim|required');
		$this->form_validation->set_rules('passconf','Konfirmasi Password','trim|required|matches[password]');
		$this->form_validation->set_rules('g-recaptcha-response','Gambar Keamanan','callback_check_scode');
	}

	function registerSave($byajax = ''){
		//set validation rules for register
		$this->_set_rules_register();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			if($byajax){
				echo json_encode(array(
					'status' => 0,
					'message' => str_replace("\n"," ",strip_tags(validation_errors())),
				));
			}else{
				$this->register();
			}
		}
		else
		{
			// save data
			$this->load->library('crypt');
			$raw_password = $this->crypt->encrypt($this->input->post('password'));
			$password = $this->crypt->encrypt($raw_password);
			$row = $this->_get_post_data_register($password);
			$row['usertype'] = '3';
			$user_activation = get_main_config('user_activation');
			if($user_activation=='1'){
				$row['status'] = '0';
			}else{
				$row['status'] = '1';
			}
			$id = $this->model->save($row);
			
			//add phone_contact
			if($this->login_by=='phone') check_insert_phone_contact($row['phone']);
			
			// check main_config
			$enable_email = get_main_config('enable_email');
			$enable_whatsapp = get_main_config('enable_whatsapp');
			$enable_sms = get_main_config('enable_sms');
			
			if($enable_email || $enable_whatsapp || $enable_sms){
				$notif = $this->template->render($id, 'user_register');
				if($this->login_by=='email' && $enable_email){
					$row_notif = array(
							'send_as' => 'email',
							'recipient' => $row['email'],
							'subject' => $notif['subject'],
							'body' => $notif['body'],
						);
					model_save('tb_notif_pool', $row_notif);
				}
				if($this->login_by=='phone' && $enable_whatsapp){
					$row_notif = array(
							'send_as' => 'whatsapp',
							'recipient' => $row['phone'],
							'subject' => $notif['subject'],
							'body' => $notif['body_whatsapp'],
						);
					model_save('tb_notif_pool', $row_notif);
				}
				if($this->login_by=='phone' && $enable_sms){
					$row_notif = array(
							'send_as' => 'sms',
							'recipient' => $row['phone'],
							'subject' => $notif['subject'],
							'body' => $notif['body_sms'],
						);
					model_save('tb_notif_pool', $row_notif);
				}
			}
			
			$name = ucfirst($row['name']);
			
			if($byajax){
				//directly login
				$retlogin = $this->model->checklogin($row[$this->login_by], $raw_password);
				$login_data['is_logged_in'] = TRUE;
				$login_data['user'] = $retlogin['row'];
				$this->session->set_userdata('login_data', $login_data);
				
				$ar_return = array('status' => 1, 'message' => '', 'row' => $retlogin['row']);
				echo json_encode($ar_return);
			}else{
				// redirect to success message
			}
			
			// send email
			/* $inputs = array();
			if($user_activation=='1'){
				// set scode for activation
				$id_tmp = '1';
				while($id_tmp!='')
				{
					$scode = random_string('alnum', 20);
					$id_tmp = $this->model->get_field_by_field('scode',$scode,'id');
				}
				$row=array('scode'=>$scode);
				$this->model->update($id, $row);
				
				$inputs['activation_link'] = site_url('user/activate/'.$scode);
	
				$this->_regsuccess_activate($id, $this->mailer->mail_common($id,'activation',$inputs));
			}
			else{
				$inputs['login_link'] = site_url('user/login');
				$inputs['password'] = $this->input->post('password');
	
				$this->_regsuccess_no_activate($id, $this->mailer->mail_common($id,'register',$inputs));
			} */
		}
	}
	
	function registerPhone(){
		$register_secret = $this->session->userdata('register_secret');
		if($register_secret){
			if($register_secret==$this->input->post('register_secret')){
				
				$phone = $this->input->post('register_phone');
				if(!$this->model->check_phone($phone)){
					$login_data = $this->session->userdata('login_data');
				
					$row = array(
						'phone' => $this->input->post('register_phone'),
					);
					
					$this->model->update($login_data['user']->id, $row);
					
					//update login data
					$login_data['user']->phone = $row['phone'];
					$this->session->set_userdata('login_data', $login_data);
					
					//add phone_contact
					check_insert_phone_contact($row['phone']);
					
					// check main_config
					$enable_whatsapp = get_main_config('enable_whatsapp');
					$enable_sms = get_main_config('enable_sms');
					
					if($enable_whatsapp || $enable_sms){
						$notif = $this->template->render($login_data['user']->id, 'user_register');
						if($enable_whatsapp){
							$row_notif = array(
									'send_as' => 'whatsapp',
									'recipient' => $row['phone'],
									'subject' => $notif['subject'],
									'body' => $notif['body_whatsapp'],
								);
							model_save('tb_notif_pool', $row_notif);
						}
						if($enable_sms){
							$row_notif = array(
									'send_as' => 'sms',
									'recipient' => $row['phone'],
									'subject' => $notif['subject'],
									'body' => $notif['body_sms'],
								);
							model_save('tb_notif_pool', $row_notif);
						}
					}
					
					$ar_return = array(
						'status' => 1,
						'message' => '',
					);		
				}
				else{
					$ar_return = array(
						'status' => 0,
						'message' => 'Nomor HP telah terdaftar',
					);
				}	
			}else{
				$ar_return = array(
					'status' => 0,
					'message' => 'Secret Code tidak sesuai',
				);
			}
		}else{
			$ar_return = array(
				'status' => 0,
				'message' => 'Secret Code kosong',
			);
		}
		echo json_encode($ar_return);
	}
	
	function _update_visit_user_id($user_id)
	{
		$visit_id = $this->session->userdata('visit_id');
		if($visit_id){
			model_update('tb_visit', $visit_id, array('user_id' => $user_id));
		}
	}
	
	function loginFacebook($byajax = ''){
		$picture = $this->input->post('fb_picture');
		$fb_uid = $this->input->post('fb_uid');
		if($fb_uid){
			//validasi accessToken
			$this->load->helper('http');
			$accessToken = $this->input->post('fb_accessToken');
			$resp = http_load("https://graph.facebook.com/me?access_token=$accessToken");
			$ar_resp = json_decode($resp);
			if($ar_resp->id==$fb_uid){	//respon id = post id, cocok
				$row_check = $this->model->get_field_by_field('fb_uid', $fb_uid, 'id, picture');
				if($row_check){
					//jika ada, check picture
					if($row_check->picture){ //jika ada, langsung login
						$this->_login_social($fb_uid,'',$byajax);
					}
					else{	//jika tidak ada, update picture lalu login
						$this->model->update($row_check->id, array('picture' => $picture));
						$this->_login_social($fb_uid,'',$byajax);
					}
				}
				else{
					//jika tidak ada, cek email account fb
					$row_check = $this->model->get_field_by_field('email', $this->input->post('fb_email'), 'id, picture');
					if($row_check){
						if($row_check->picture){
							$this->model->update($row_check->id, array('fb_uid' => $fb_uid));
							$this->_login_social($fb_uid,'',$byajax);
						}
						else{
							$this->model->update($row_check->id, array('fb_uid' => $fb_uid, 'picture' => $picture));
							$this->_login_social($fb_uid,'',$byajax);
						}
					}
					else{
						//jika tidak ada, register lalu login
						$registerDate = date("Y-m-d H:i:s", time());
						$row = array(
							'fb_uid' => $this->input->post('fb_uid'),
							'email' => $this->input->post('fb_email'),
							'name' => $this->input->post('fb_name'),
							'usertype' => '3',	//member
							'status' => '1',	//otomatis aktif
							'picture' => $picture,
							// 'ref_id' => $this->_get_user_ref_id(),
							'registerDate' => $registerDate,
						);
						$id = $this->model->save($row);
						// $this->_update_visit_user_id($id);	//update user_id corresponding visit record
						$this->_login_social($fb_uid,'',$byajax, true);
					}
				}
			}
		}
	}
	
	function loginGoogle($byajax = ''){
		$picture = $this->input->post('g_picture');
		if($picture){
			$ar_picture = explode("/", $picture);
			if(count($ar_picture)>=2){
				if($ar_picture[count($ar_picture)-2]=='s96-c'){
					$ar_picture[count($ar_picture)-2] = 's200-c';
					$picture = implode("/", $ar_picture);
				}
			}
		}
		$g_uid = $this->input->post('g_uid');
		if($g_uid){
			//validasi accessToken
			$this->load->helper('http');
			$id_token = $this->input->post('g_id_token');
			$resp = http_load("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$id_token");
			$ar_resp = json_decode($resp);
			$client_id = "763389307343-g6cqjgb673dge7j09ngb0isdr2d6a4rd.apps.googleusercontent.com";
			if($ar_resp->aud==$client_id && $ar_resp->sub==$g_uid){	//respon aud = client_id, sub = id -> cocok
				$row_check = $this->model->get_field_by_field('g_uid', $g_uid, 'id, picture');
				if($row_check){
					//jika ada, check picture
					if($row_check->picture){ //jika ada, langsung login
						$this->_login_social('', $g_uid, $byajax);
					}
					else{	//jika tidak ada, update picture lalu login
						$this->model->update($row_check->id, array('picture' => $picture));
						$this->_login_social('', $g_uid, $byajax);
					}
				}
				else{
					$row_check = $this->model->get_field_by_field('email', $this->input->post('g_email'), 'id, picture');
					if($row_check){
						if($row_check->picture){
							$this->model->update($row_check->id, array('g_uid' => $g_uid));
							$this->_login_social('', $g_uid, $byajax);
						}
						else{
							$this->model->update($row_check->id, array('g_uid' => $g_uid, 'picture' => $picture));
							$this->_login_social('', $g_uid, $byajax);
						}
					}
					else{
						//jika tidak ada, signup google lalu login
						$registerDate = date("Y-m-d H:i:s", time());
						$row = array(
							'g_uid' => $g_uid,
							'email' => $this->input->post('g_email'),
							'name' => $this->input->post('g_name'),
							'usertype' => '3',	//member
							'status' => '1',	//otomatis aktif
							'picture' => $picture,
							// 'ref_id' => $this->_get_user_ref_id(),
							'registerDate' => $registerDate,
						);
						$id = $this->model->save($row);
						// $this->_update_visit_user_id($id);	//update user_id corresponding visit record
						$this->_login_social('', $g_uid, $byajax, true);
					}
				}
			}
		}
	}
	
	function _login_social($fb_uid='', $g_uid='', $byajax = '', $just_register = false){
		$retlogin = $this->model->checklogin('', '', $fb_uid, $g_uid);
		if(!$retlogin['return'])
		{
			if($byajax){
				$ar_return = array('status' => 0, 'message' => strip_tags(get_tpl_message('login_failed_'.$this->login_by)));
				echo json_encode($ar_return);
			}
			else{
				$this->session->set_userdata('message', get_tpl_message('login_failed_'.$this->login_by));
				$this->login();
			}
		}
		else
		{
			$login_data['is_logged_in'] = TRUE;
			$login_data['user'] = $retlogin['row'];
			$this->session->set_userdata('login_data', $login_data);
			$register_secret = rand_char(20);
			$this->session->set_userdata('register_secret', $register_secret);
			
			if($just_register){
				// check main_config
				$enable_email = get_main_config('enable_email');
				
				if($enable_email){
					$notif = $this->template->render($login_data['user']->id, 'user_register');
					$row_notif = array(
							'send_as' => 'email',
							'recipient' => $login_data['user']->email,
							'subject' => $notif['subject'],
							'body' => $notif['body'],
						);
					model_save('tb_notif_pool', $row_notif);
				}
			}
			
			if($byajax){
				$ar_return = array(
					'status' => 1,
					'message' => '',
					'row' => $retlogin['row'],
					'just_register' => $just_register,
					'register_secret' => $register_secret,
				);
				echo json_encode($ar_return);
			}
			else{
				$this->user = is_logged_in();
				$this->index();
			}
		}
	}
	
	function _regsuccess_activate($id, $sendemail)
	{
		$inputs = array('sender_email' => get_main_config('sender_email'));
		if($sendemail)
		{
			$msg = $this->message->render($id,'regsuccess_activate_mail',$inputs);
		}
		else
		{
			$msg = $this->message->render($id,'regsuccess_activate_nomail',$inputs);
		}
		$this->view['doctitle'] = 'GrabKonten - User Registration';
		$this->view['title'] = $msg['title'];
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/index";
     	$data['info'] = array('0' => array('title'=>'', 'body'=>$msg['body']));
		
		$this->load->view($this->config->item('template_user').'/index', $data);
	}
	
	function activate($scode='')
	{
		if(trim($scode)!="")
		{
			$row = $this->model->get_field_by_field('scode',$scode,'id, title, name, password');
			if($row)
			{
				$id = $row->id;
				
				$this->model->update($id, array('status' => '1', 'scode' => ''));
				
				$inputs = array('login_link' => site_url($this->controller.'/login'));
				$msg = $this->message->render($id,'activate_success',$inputs);
				
				$this->view['doctitle'] = 'JNLBay Journal Submission System - Account Activation';
				$this->view['toptitle'] = $msg['title'];
				$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/index";
				
				$data['info'] = array('0' => array('title'=>'', 'body'=>$msg['body']));
				
				$this->load->view($this->config->item('template_user').'/index', $data);
			}
			else
			{
				$sender_email = get_main_config('sender_email');
				$inputs = array(
						'sender_email' => mailto($sender_email,$sender_email),
						'resend_activation_link' => site_url('user/resendactivation')
					);
				$msg = $this->message->render('','activate_invalid_scode',$inputs);
				
				$this->view['doctitle'] = 'JNLBay Journal Submission System - Account Activation';
				$this->view['toptitle'] = $msg['title'];
				$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/index";
				 
				$data['info'] = array('0' => array('title'=>'', 'body'=>$msg['body']));
				
				$this->load->view($this->config->item('template_user').'/index', $data);
			}
		}
		else
		{
			$msg = get_tpl_message('activate_no_scode', TRUE);
			$this->view['doctitle'] = 'JNLBay Journal Submission System - Account Activation';
			$this->view['toptitle'] = $msg['title'];
			$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/index";
			
			$data['info'] = array('0' => array('title'=>'', 'body' => $msg['body']));
			
			$this->load->view($this->config->item('template_user').'/index', $data);
		}
	}
	
	function _regsuccess_no_activate($id, $sendemail)
	{
		$inputs = array('login_link' => site_url($this->controller.'/login'));
		
		if($sendemail)
		{
			$msg = $this->message->render($id,'regsuccess_no_activate_mail',$inputs);
		}
		else
		{
			$msg = $this->message->render($id,'regsuccess_no_activate_nomail',$inputs);
		}
		
		$this->view['title'] = 'Pendaftaran Sukses';
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller."/index";
     	$data['info'] = array('title'=>$msg['title'], 'body'=>$msg['body']);
		
		$this->load->view($this->config->item('template_user').'/index', $data);
	}
	
	function _set_rules_username($changed=FALSE, $is_empty=FALSE){
		if($changed){
			if(!$is_empty){
				$this->form_validation->set_rules('username','Username','trim|is_unique[tb_user.username]');
			}else{
				$this->form_validation->set_rules('username','Username','trim');
			}
		}else{
			$this->form_validation->set_rules('username','Username','trim');
		}
	}
	
	function _set_rules_phone($changed=FALSE, $is_empty=FALSE){
		if($changed){
			if(!$is_empty){
				$this->form_validation->set_rules('phone','No HP','trim|is_unique[tb_user.phone]');
			}else{
				$this->form_validation->set_rules('phone','No HP','trim');
			}
		}else{
			$this->form_validation->set_rules('phone','No HP','trim');
		}
	}
	
	function _set_rules_email($changed=FALSE, $is_empty=FALSE){
		if($changed){
			if(!$is_empty){
				$this->form_validation->set_rules('email','Alamat Email','trim|valid_email|is_unique[tb_user.email]');
			}else{
				$this->form_validation->set_rules('email','Alamat Email','trim|valid_email');
			}
		}else{
			$this->form_validation->set_rules('email','Alamat Email','trim|valid_email');
		}
	}
	
	function _set_rules_pass($changed=FALSE){
		if($changed){
			$this->form_validation->set_rules('password','Password','trim|required');
			$this->form_validation->set_rules('passconf','Konfirmasi Password','trim|required|matches[password]');
		}
		else{
			$this->form_validation->set_rules('password','Password','');
			$this->form_validation->set_rules('passconf','Konfirmasi Password','');
		}
	}
	
	// check_scode callback
	function check_scode($str)
	{
		if($str){
			$this->load->helper('http');
			$params = array(
					'secret' => '6LeuNloUAAAAAE7WFEKMB8s0wteElvn95c5OcC-T',
					'response' => $str,
					'remoteip' => '',
				);
			$response = http_load('https://www.google.com/recaptcha/api/siteverify',0,$params);
			$ar_response = json_decode($response);
			if($ar_response->success){
				return true;
			}else{
				$this->form_validation->set_message('check_scode', 'Buktikan bahwa Anda bukan robot :)');
				return false;
			}
		}
		else{
			$this->form_validation->set_message('check_scode', 'Buktikan bahwa Anda bukan robot :)');
			return false;
		}
	}
	
	// date_validation callback
	function valid_date($str)
	{
		if(!check_date($str)){
			$this->form_validation->set_message('valid_date', '%s tidak valid. dd-mm-yyyy');
			return false;
		}
		else{
			return true;
		}
	}
	
	//get posted data to row
	function _get_post_data_register($password)
	{
		$registerDate = $this->input->post('registerDate');
		if(!$registerDate){
			$registerDate = date("Y-m-d H:i:s", time());
		}
		
		$row = array(
			'name' => $this->input->post('name'),
			$this->login_by => $this->input->post($this->login_by),
			'password' => $password,
			'registerDate' => $registerDate,
		);
		
		return $row;
	}
	
	/* function _get_user_ref_id()
	{
		$ref_id = 0;
		$visit_id = $this->session->userdata('visit_id');
		if($visit_id){
			$visit_ref_id = model_get_field_by_id('tb_visit', $visit_id, 'ref_id');
			if($visit_ref_id){
				$ref_id = model_get_field_by_id('tb_visit', $visit_ref_id, 'user_id');
			}
		}
		return $ref_id;
	} */
	
	function _get_html($row=array())
	{
		$html = array();
		return $html;
	}
	
	public function downloadinfopaymentdp()
  	{
		$title = $this->input->post('title');
		
		$content = $this->input->post('content');
		$content = str_replace('src="../../images/logo_long.png"','src="images/logo_long.png"',$content);
		$content = str_replace('<tbody>','',$content);
		$content = str_replace('</tbody>','',$content);
		
		$content = str_replace('<p> </p>','<p><br></p>',$content);
		while(strpos($content,'  ')!==false)
		{
			$content = str_replace('  ',' ',$content);
		}
		$content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
		
		$this->load->library('dom_pdf');
		
		$pdf = $this->dom_pdf->load();
		
		$style = "<style>@page { margin: 1in 1in 1in 1in;}</style>";
		
		$head = "<head>".$style."</head>";
		
		$html = "<html>".$head."<body>".$content."</body></html>";
		if ( get_magic_quotes_gpc() ) $html = stripslashes($html);
		
		$pdf->load_html($html);
		$pdf->set_paper('a4', 'portrait');
		$pdf->render();
		$pdf->stream("Info Pembayaran DP.pdf");
	}
	
	function error404()
	{
		$this->view['title'] = 'HTTP Error 404';
		$this->view['keywords'] = "";
		$this->view['description'] = "";
		$this->view['content'] = $this->controller.'/error';
		$this->load->view('main');
	}
}

?>