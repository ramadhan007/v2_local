<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ControllerImageModel extends CI_Model {
	
	// table name
	private $table= 'tb_controller_image';

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
	
	// get number of persons in database
	function count_all($cari='', $controller, $main_id){
		return model_count_all($this->table, $cari, array('name','title'), array('controller', 'main_id'), array($controller, $main_id));
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari='', $controller, $main_id){
		return model_get_paged_list($this->table, $limit, $offset, "id, name, title, path, published", 'name', 'asc', $cari, array('title','name'), array('controller', 'main_id'), array($controller, $main_id));
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