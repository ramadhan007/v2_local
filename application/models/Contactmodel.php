<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ContactModel extends CI_Model {
	
	// table name
	private $table= 'tb_contact';

	function __construct(){
		parent::__construct();
	}
	
	//get field by id
	function get_field_by_id($id, $field)
	{
		return model_get_field_by_id($this->table, $id, $field);
	}
	
	// get person by id
	function get_by_id($id){
		return model_get_by_id($this->table, $id, "*, fc_check_user_access(sekolah_id, '".$this->user['id']."') as allow_edit");
	}
	
	// get record by field
	function get_by_field($field_name, $field_value){
		return model_get_by_field($this->table, $field_name, $field_value);
	}
	
	//get array for menu list (select, radio)
	function get_list($value='id', $text='name')
	{
		return model_get_list($this->table, $value, $text, false, 'deleted', '0');
	}
	
	// get number of records in database
	function count_all($cari=''){
		$add_cond = " AND a.sekolah_id = '".$this->session->userdata('active_sekolah_id')."'";
		
		$sql = "
			select	count(a.id)
			from 	tb_contact as a
					inner join tb_user as b on a.user_id = b.id
			where	not deleted $add_cond";
		return get_val($sql);
	}
	
	// get records with paging
	function get_paged_list($limit=10, $offset=0, $cari=''){
		$add_cond = " AND a.sekolah_id = '".$this->session->userdata('active_sekolah_id')."'";
		
		$sql = "
			select	a.id, b.name, b.email, b.phone, b.picture
			from 	tb_contact as a
					inner join tb_user as b on a.user_id = b.id
			where	not deleted $add_cond
			order	by b.name";
		return $this->db->query($sql);
	}
	
	function get_all(){
		return model_get_all($this->table, 'id', 'asc', 'deleted', '0');
	}
	
	// add new record
	function save($data){
		return model_save($this->table,$data);
	}
	
	// update record by id
	function update($id, $data){
		model_update($this->table, $id, $data);
	}
	
	// delete record by id
	function delete($ids=array()){
		$login_data = $this->session->userdata('login_data_admin');
		$user_delete = $login_data['user']->id;
		$date_delete = date('Y-m-d H:i:s');
		model_delete_tag($this->table, $ids, $user_delete, $date_delete);
	}
}

?>