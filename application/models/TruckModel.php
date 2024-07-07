<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TruckModel extends CI_Model {
	
	// table name
	private $table= 'tb_truck';

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
		return model_get_by_id($this->table, $id);
	}
	
	// get record by field
	function get_by_field($field_name, $field_value){
		return model_get_by_field($this->table, $field_name, $field_value);
	}
	
	//get array for menu list (select, radio)
	function get_list($value='id', $text='nama')
	{
		return model_get_list($this->table, $value, $text);
	}
	
	// get number of records in database
	function count_all($cari=''){
		$where_fields = array("deleted");
		$where_vals = array("0");
		if($this->user['usertype']>2){
			$where_fields[] = "user_insert";
			$where_vals[] = $this->user['id'];
		}
		return model_count_all($this->table, $cari, array('license_plate'), $where_fields, $where_vals);
	}
	
	// get records with paging
	function get_paged_list($limit=10, $offset=0, $cari=''){
		$where_fields = array("deleted");
		$where_vals = array("0");
		if($this->user['usertype']>2){
			$where_fields[] = "user_insert";
			$where_vals[] = $this->user['id'];
		}
		return model_get_paged_list($this->table, $limit, $offset, "*, fn_get_list_item_formatted_icon('published', published) as published1", 'license_plate', 'asc', $cari, array('license_plate'), $where_fields, $where_vals);
	}
	
	function get_all(){
		return model_get_all($this->table, 'id', 'asc');
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
		$user_delete = $this->user['id'];
		$date_delete = date('Y-m-d H:i:s');
		model_delete_tag($this->table, $ids, $user_delete, $date_delete);
	}
}

?>