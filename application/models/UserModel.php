<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model {
	
	// table name
	private $table= 'tb_user';

	function __construct(){
		parent::__construct();
	}
	
	//get array for menu list (select, radio)
	function get_list($value='id', $text='name')
	{
		$text = "concat(name,' (',email,')')";
		return model_get_list($this->table, $value, $text);
	}
	
	// dapatkan daftar nama field
	function get_fields()
	{
		return model_get_fields($this->table);
	}
	
	// dapatkan nilai fields dari id
	function get_field_by_id($id, $field)
	{
		return model_get_field_by_id($this->table, $id, $field);
	}
	
	// dapatkan nilai fields dari field
	function get_field_by_field($keyfield, $keyval, $field){
		return model_get_field_by_field($this->table, $keyfield, $keyval, $field);
	}
	
	// get number of persons in database
	function count_all($usertype, $cari=''){
		$cond_cari = "WHERE a.usertype >= $usertype";
		$cond_cari .= $cari ? " AND (a.name like '%$cari%' OR a.username like '%$cari%' OR a.email like '%$cari%' OR a.phone like '%$cari%')" : "";
		$query = "SELECT count(*)
			FROM $this->table as a
				INNER join tb_usertype as b on a.usertype = b.id
			$cond_cari";
		return get_val($query);
	}
	
	// get persons with paging
	function get_paged_list($usertype, $limit=10, $offset=0, $cari=''){
		$offset = $offset ? $offset : 0;
		$cond_cari = $cari ? "WHERE (a.name like '%$cari%' OR a.username like '%$cari%' OR a.email like '%$cari%')" : "";
		$query = "SELECT a.id, a.name, a.username, a.email, a.phone, a.usertype, b.name as usertype1, c.name as userrole1,
			a.status, lastvisitDate
			FROM $this->table as a
				INNER join tb_usertype as b on a.usertype = b.id
				LEFT join tb_userrole as c on a.userrole = c.id
			$cond_cari
			ORDER BY a.name ASC
			".($limit=='all' ? '' : "LIMIT $offset, $limit");
		return $this->db->query($query);
	}
	
	// get person by id
	function view_by_id($id){
		return model_get_by_id($this->table, $id);
	}
	
	// get record by id
	function get_by_id($id){
		return model_get_by_id($this->table, $id);
	}
	
	// add new person
	function save($data){
		return model_save($this->table,$data);
	}
	
	// update person by id
	function update($id, $data){
		model_update($this->table, $id, $data);
	}
	
	// delete person by id
	function delete($ids=array()){
		model_delete($this->table, $ids);
	}
	
	//check user
	function check_data($email)
	{
		return model_check_data($this->table, 'email', $email);
	}
	
	//check user by phone
	function check_phone($phone)
	{
		return model_check_data($this->table, 'phone', $phone);
	}
	
	//check field where id & field not match
	function check_field_db_not($id, $field, $value)
	{
		return model_check_field_db_not($this->table, $id, $field, $value);
	}
	
	//front member area function
	function checklogin($login_by, $password, $fb_uid='', $g_uid='')
	{
		$this->load->library('crypt');
		$query = "SELECT a.id, a.username, a.email, a.phone, a.name, a.picture, a.usertype, b.name as usertype1, b.template, b.menu
			FROM $this->table AS a
				INNER JOIN tb_usertype AS b ON b.id = a.usertype";
		if($fb_uid){
			$query .= " WHERE a.fb_uid = '$fb_uid'";
		}
		elseif($g_uid){
			$query .= " WHERE a.g_uid = '$g_uid'";
		}
		else{
			$query .= " WHERE a.".get_main_config('login_by')." = '$login_by' AND a.".get_main_config('login_by')." <> '' AND a.password = '".$this->crypt->encrypt($password)."' and a.password <> ''";
			// $query .= " WHERE (a.username = '$login_by' OR a.email = '$login_by' OR a.phone = '$login_by') AND a.password = '".$this->crypt->encrypt($password)."' and a.password <> ''";
		}
		// echo $query; exit();
		$row = get_row($query);
		
		$retval = array();
		if($row){
			$retval['return'] = TRUE;
			$row->login_with = "";
			if($fb_uid) $row->login_with = "fb";
			if($g_uid) $row->login_with = "g";
			
			//user picture
			if(!$row->picture) $row->picture = base_url('images/no-user.png');
			
			//update lastVisit
			$this->db
				->set('lastvisitDate', date("Y-m-d H:i:s", time()))
				->where('id', $row->id)
				->update('tb_user');
			
			$retval['row'] = fix_base_url($row);
		}
		else{
			$retval['return'] = FALSE;
		}
		return $retval;
	}
	
	// reset password
	function resetpass($id, $password){
		$this->db->where('id', $id);
		$data = array(
				'password'=>Encrypt($password),
				'scode'=>''
			);
		$this->db->update($this->table, $data);
	}
	
	function get_list_usertype($usertype)
	{
		$this->db->select('id as value, name as text');
		$this->db->where('published','1');
		$this->db->where('id >=',$usertype);
		$this->db->order_by('id','desc');
		$rows = $this->db->get('tb_usertype')->result();
		$ret_array = array();
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
	}
}

?>