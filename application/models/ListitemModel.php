<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ListItemModel extends CI_Model {
	
	// table name
	private $table= 'tb_list_item';

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
	
	//get array for form list (select, radio)
	function get_list($list_cat_id, $order_by='val', $order_dir='asc')
	{
		return model_get_list($this->table, 'val', 'text', false, 'list_cat_id', $list_cat_id, $order_by, $order_dir);
	}
	
	// get number of persons in database
	function count_all($cari=''){
		$main_field = 'list_cat_id';
		$main_id = $this->session->userdata($this->controller.'_list_cat_id');
		
		return model_count_all($this->table, $cari, array('text'), $main_field, $main_id);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari=''){
		$main_field = 'list_cat_id';
		$main_id = $this->session->userdata($this->controller.'_list_cat_id');
		$list_cat_type = $this->session->userdata($this->controller.'_list_cat_type');
		
		$order_by = 'val';
		if($list_cat_type=='number'){
			$order_by = 'CAST(val AS UNSIGNED)';
		}
		
		return model_get_paged_list($this->table, $limit, $offset, "*", $order_by, 'asc', $cari, array('text'), $main_field, $main_id);
	}
	
	function get_all(){
		$main_field = 'list_cat_id';
		$main_id = $this->session->userdata($this->controller.'_list_cat_id');
		return model_get_all($this->table, 'id', 'asc', $main_field, $main_id);
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
		model_delete($this->table, $ids);
	}
}

?>