<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JourneyDetailTaskModel extends CI_Model {
	
	// table name
	private $table= 'tb_journey_detail_task';

	function __construct(){
		parent::__construct();
	}
	
	function get_table(){
		return $this->table;
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
	function get_list($journey_detail_id, $order_by='val', $order_dir='asc')
	{
		return model_get_list($this->table, 'val', 'text', false, 'journey_detail_id', $journey_detail_id, $order_by, $order_dir);
	}
	
	// get number of persons in database
	function count_all($cari=''){
		$main_field = 'journey_detail_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		
		return model_count_all($this->table, $cari, array('name'), $main_field, $main_id);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari=''){
		$main_field = 'journey_detail_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		
		$select = "*,
					fn_get_list_item_short('journey_detail_task_type',type) as type_text,
					fn_get_list_item_short('journey_detail_task_find_by',find_by) as find_by_text,
					fn_get_list_item_short('journey_detail_task_handler',handler) as handler_text,
					fn_get_list_item_short('journey_detail_task_action',action) as action_text,
					fn_get_list_item_short('option_yes_no',record_param) as record_param_text,
					fn_get_list_item_icon('option_yes_no',record_param) as record_param_icon,
					fn_get_list_item_class('option_yes_no',record_param) as record_param_class,
					fn_get_list_item_short('option_yes_no',upload) as upload_text,
					fn_get_list_item_icon('option_yes_no',upload) as upload_icon,
					fn_get_list_item_class('option_yes_no',upload) as upload_class,
					fn_get_list_item_short('published',published) as published_text,
					fn_get_list_item_icon('published',published) as published_icon,
					fn_get_list_item_class('published',published) as published_class,
					fn_get_list_item_short('journey_detail_task_when',start_timer_when) as start_timer_when_text,
					fn_get_list_item_short('journey_detail_task_when',end_timer_when) as end_timer_when_text,
					fn_get_list_item_short('journey_detail_task_when',record_param_when) as record_param_when_text,
					fn_get_list_item_short('journey_detail_task_when',upload_when) as upload_when_text,
					fn_get_list_item_short('platform',platform) as platform_text";
		
		return model_get_paged_list($this->table, $limit, $offset, $select, 'ordering', 'asc', $cari, array('name'), $main_field, $main_id);
	}
	
	function get_all(){
		$main_field = 'journey_detail_id';
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
		$main_field = 'journey_detail_id';
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
		$this->db->where('journey_detail_id',$this->session->userdata($this->controller.'_parent_id'));
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function get_max_ordering()
	{
		$this->db->select_max('ordering');
		$this->db->where('journey_detail_id',$this->session->userdata($this->controller.'_parent_id'));
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function min_max_ordering()
	{
		//parent field
		$main_field = 'journey_detail_id';
		$main_id = $this->session->userdata($this->controller.'_parent_id');
		
		return get_row("SELECT ifnull(MIN(ordering),0) as min_ordering, ifnull(MAX(ordering),0) as max_ordering FROM tb_journey_detail_task WHERE $main_field = '$main_id'");
	}
	
	//get array for journey list (select, radio)
	function get_list_ordering($id)
	{
		$journey_detail_id = $this->get_field_by_id($id,'journey_detail_id');
		$fld_value = 'ordering';
		$fld_text = 'name';
		$this->db->select($fld_value.' as value, '.$fld_text.' as text');
		$this->db->where('journey_detail_id',$journey_detail_id);
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