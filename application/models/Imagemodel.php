<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImageModel extends CI_Model {
	
	// table name
	private $table= 'tb_image';

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
	function count_all($user, $cari=''){
		$ar_where_field = array(); $ar_where_val = array();
		
		$ar_where_field[] = 'sekolah_id';
		$ar_where_val[] = $this->session->userdata('active_sekolah_id');
		
		if($this->session->userdata($this->controller.'_name')){
			$ar_where_field[] = "name";
			$ar_where_val[] = $this->session->userdata($this->controller.'_name');
		}
		
		return model_count_all($this->table, $cari, array('name', 'title'), $ar_where_field, $ar_where_val);
	}
	
	// get records with paging
	function get_paged_list($user, $limit=10, $offset=0, $cari=''){
		$ar_where_field = array(); $ar_where_val = array();
		
		$ar_where_field[] = 'sekolah_id';
		$ar_where_val[] = $this->session->userdata('active_sekolah_id');
		
		if($this->session->userdata($this->controller.'_name')){
			$ar_where_field[] = "name";
			$ar_where_val[] = $this->session->userdata($this->controller.'_name');
		}
		
		return model_get_paged_list($this->table, $limit, $offset, "*", array('sekolah_id', 'name', 'title'), array('asc', 'asc', 'asc'), $cari, array('name', 'title'), $ar_where_field, $ar_where_val);
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
		model_delete($this->table, $ids);
	}
}

?>