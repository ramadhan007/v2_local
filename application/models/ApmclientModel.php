<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ApmclientModel extends CI_Model {
	
	// table name
	private $table= 'tb_apm_client';

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
	function get_list($location_id, $order_by='val', $order_dir='asc')
	{
		return model_get_list($this->table, 'val', 'text', false, 'location_id', $location_id, $order_by, $order_dir);
	}
	
	// get number of persons in database
	function count_all($filters=array()){
		$where = "";
		if(strlen($filters['published'])>0) $where = "a.published = '".$filters['published']."'";
		if($filters['location_id'])
			$where .= ($where ? " AND " : "")."a.location_id = '".$filters['location_id']."'";
		if($filters['operator_id'])
			$where .= ($where ? " AND " : "")."a.operator_id = '".$filters['operator_id']."'";
			
		if($filters['cari'])
			$where .= ($where ? " AND " : "")."(a.phone_number LIKE '%".$filters['cari']."%' OR b.name LIKE '%".$filters['cari']."%' OR c.name LIKE '%".$filters['cari']."%')";
		
		$sql = "
			SELECT 	count(*) as num
			FROM 	$this->table as a
					LEFT JOIN tb_location as b ON a.location_id = b.id
					LEFT JOIN tb_operator AS c ON a.operator_id = c.id
			".($where ? "WHERE $where" : "").";";
		
		return get_val($sql, false);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $filters=array()){
		$where = "";
		if(strlen($filters['published'])>0) $where = "a.published = '".$filters['published']."'";
		if($filters['location_id'])
			$where .= ($where ? " AND " : "")."a.location_id = '".$filters['location_id']."'";
		if($filters['operator_id'])
			$where .= ($where ? " AND " : "")."a.operator_id = '".$filters['operator_id']."'";
			
		if($filters['cari'])
			$where .= ($where ? " AND " : "")."(a.phone_number LIKE '%".$filters['cari']."%' OR b.name LIKE '%".$filters['cari']."%' OR c.name LIKE '%".$filters['cari']."%')";
		
		$sql = "
			SELECT 	a.*, fc_count_apm_client_device(a.id) as list,
					0 as log_update,
					b.name as location_name, c.name as operator_name,
					fn_get_list_item_text('application',a.application) as application_text,
					fn_get_list_item_short('published',a.published) as published_text,
					fn_get_list_item_icon('published',a.published) as published_icon,
					fn_get_list_item_class('published',a.published) as published_class
			FROM 	$this->table as a
					LEFT JOIN tb_location as b ON a.location_id = b.id
					LEFT JOIN tb_operator AS c ON a.operator_id = c.id
			".($where ? "WHERE $where" : "")."
			ORDER 	BY a.application desc, b.name, c.name
			".($limit=='all' ? '' : "LIMIT $offset, $limit");
			
		// echo $sql;
		
		return $this->db->query($sql);
	}
	
	function get_one_list($id){
		$sql = "
			SELECT 	a.*, fc_count_apm_client_device(a.id) as list,
					fc_count_apm_client_log_update(a.id) as log_update,
					b.name as location_name, c.name as operator_name,
					fn_get_list_item_text('application',a.application) as application_text,
					fn_get_list_item_short('published',a.published) as published_text,
					fn_get_list_item_icon('published',a.published) as published_icon,
					fn_get_list_item_class('published',a.published) as published_class
			FROM 	$this->table as a
					INNER JOIN tb_location as b ON a.location_id = b.id
					INNER JOIN tb_operator AS c ON a.operator_id = c.id
			WHERE	a.id = '$id'";
			
		// echo $sql;
		
		return $this->db->query($sql);
	}
	
	function get_all(){
		$where_fields = array();
		$where_vals = array();
		if(strlen($filters['published'])>0){
			$where_fields = 'published';
			$where_fields = $filters['published'];
		}
		if($filters['location_id']){
			$where_fields = 'location_id';
			$where_fields = $filters['location_id'];
		}
		if($filters['operator_id']){
			$where_fields = 'operator_id';
			$where_fields = $filters['operator_id'];
		}
		return model_get_all($this->table, 'id', 'asc', $where_fields, $where_vals);
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