<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JourneyDetailModel extends CI_Model {
	
	// table name
	private $table= 'tb_journey_detail';

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
	function get_list($journey_id, $order_by='val', $order_dir='asc')
	{
		return model_get_list($this->table, 'val', 'text', false, 'journey_id', $journey_id, $order_by, $order_dir);
	}
	
	// get number of persons in database
	function count_all($cari=''){
		$main_field = 'journey_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		
		return model_count_all($this->table, $cari, array('name'), $main_field, $main_id);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari=''){
		$main_field = 'journey_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		
		$select = "*, fc_count_journey_detail_task(id) as list,
					fn_get_list_item_short('journey_detail_task_upload',fc_journey_detail_task_upload(id)) as upload_text,
					fn_get_list_item_icon('journey_detail_task_upload',fc_journey_detail_task_upload(id)) as upload_icon,
					fn_get_list_item_class('journey_detail_task_upload',fc_journey_detail_task_upload(id)) as upload_class,
					fn_get_list_item_short('published',published) as published_text,
					fn_get_list_item_icon('published',published) as published_icon,
					fn_get_list_item_class('published',published) as published_class,
					fn_get_list_item_short('platform',platform) as platform_text";
		
		return model_get_paged_list($this->table, $limit, $offset, $select, 'ordering', 'asc', $cari, array('name'), $main_field, $main_id);
	}
	
	function get_all(){
		$main_field = 'journey_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		return model_get_all($this->table, 'id', 'asc', $main_field, $main_id);
	}
	
	function order_up($id){
		$ordering = $this->get_ordering($id);
		$this->update_get_ordering($id, $ordering-1);
		run_query("UPDATE $this->table set ordering = $ordering-1 WHERE id = '$id';");
	}
	
	function order_down($id){
		$ordering = $this->get_ordering($id);
		$this->update_get_ordering($id, $ordering+1);
		run_query("UPDATE $this->table set ordering = $ordering+1 WHERE id = '$id';");
	}
	
	function update_get_ordering($id, $new_ordering)
	{
		//parent field
		$main_field = 'journey_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		
		//get old ordering
		$old_ordering = $this->get_ordering($id);
		
		//get max ordering same parent
		$max_ordering = $this->get_max_ordering();
		
		//get min ordering same parent
		$min_ordering = $this->get_min_ordering();
		
		if($new_ordering > $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering-1 WHERE $main_field = '$main_id' AND (ordering > $old_ordering AND ordering <= $new_ordering)";
			run_query($query);
		}
		elseif($new_ordering < $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering+1 WHERE $main_field = '$main_id' AND (ordering < $old_ordering AND ordering >= $new_ordering)";
			run_query($query);
		}
		return $new_ordering;
	}
	
	function get_ordering($id)
	{
		$ordering = $this->get_field_by_id($id, 'ordering');
		return $ordering ? $ordering : 0;
	}
	
	function get_min_ordering()
	{
		$this->db->select_min('ordering');
		$this->db->where('journey_id',$this->session->userdata($this->controller.'_parent_id'));
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function get_max_ordering()
	{
		$this->db->select_max('ordering');
		$this->db->where('journey_id',$this->session->userdata($this->controller.'_parent_id'));
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function min_max_ordering()
	{
		//parent field
		$main_field = 'journey_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		
		return get_row("SELECT ifnull(MIN(ordering),0) as min_ordering, ifnull(MAX(ordering),0) as max_ordering FROM tb_journey_detail WHERE $main_field = '$main_id'");
	}
	
	//get array for journey list (select, radio)
	function get_list_ordering($id)
	{
		$journey_id = $this->get_field_by_id($id,'journey_id');
		$fld_value = 'ordering';
		$fld_text = 'name';
		$this->db->select($fld_value.' as value, '.$fld_text.' as text');
		$this->db->where('journey_id',$journey_id);
		$this->db->order_by('ordering','asc');
		$rows = $this->db->get($this->table)->result();
		$ret_array = array();
		foreach($rows as $row){
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
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