<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ApmclientdeviceModel extends CI_Model {
	
	// table name
	private $table= 'tb_apm_client_device';

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
	function get_list($apm_client_id, $order_by='val', $order_dir='asc')
	{
		return model_get_list($this->table, 'val', 'text', false, 'apm_client_id', $apm_client_id, $order_by, $order_dir);
	}
	
	// get number of persons in database
	function count_all($cari=''){
		$main_field = 'apm_client_id';
		$main_id = $this->session->userdata($this->controller.'_apm_client_id');
		
		return model_count_all($this->table, $cari, array('text'), $main_field, $main_id);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari=''){
		$main_id = $this->session->userdata($this->controller.'_apm_client_id');
		
		$sql = "
			SELECT 	a.*, c.phone_number, d.name as location_name, e.name as operator_name,
					fn_get_list_item_short('published',a.published) as published_text,
					fn_get_list_item_icon('published',a.published) as published_icon,
					fn_get_list_item_class('published',a.published) as published_class
			FROM 	$this->table as a
					inner join tb_apm_client as b on a.apm_client_id = b.id
					inner join tb_device as c on a.device_id = c.id
					left join tb_location as d on c.location_id = d.id
					left join tb_operator as e on c.operator_id = e.id
			WHERE	a.apm_client_id = '$main_id'
					".($cari ? "AND b.name like ('%$cari%')" : "")."
			ORDER 	BY b.name ASC
			".($limit=='all' ? '' : "LIMIT $offset, $limit");
		
		return $this->db->query($sql);
	}
	
	function get_all(){
		$main_field = 'apm_client_id';
		$main_id = $this->session->userdata($this->controller.'_apm_client_id');
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