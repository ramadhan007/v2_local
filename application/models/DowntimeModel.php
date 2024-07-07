<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DowntimeModel extends CI_Model {
	
	// table name
	private $table= 'tb_downtime';

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
	function get_list($value='id', $text='name')
	{
		return model_get_list($this->table, $value, $text);
	}
	
	// get number of records in database
	function count_all($cari=''){
		return model_count_all($this->table, $cari, array());
	}
	
	// get records with paging
	function get_paged_list($limit=10, $offset=0, $cari=''){
		return model_get_paged_list($this->table, $limit, $offset, "*, fc_count_device(id) as list, fn_get_list_item_short('published',published) as published_text,
					fn_get_list_item_icon('published',published) as published_icon,
					fn_get_list_item_class('published',published) as published_class", 'end_datetime', 'desc', $cari, array());
	}
	
	function get_all(){
		return model_get_all($this->table, 'end_datetime', 'desc');
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