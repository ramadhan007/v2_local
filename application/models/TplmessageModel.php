<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TplMessageModel extends CI_Model {
	
	// table name
	private $tb_data= 'tb_tpl_message';

	function __construct(){
		parent::__construct();
	}
	
	//get array for menu list (select, radio)
	function get_list()
	{
		$fld_value = 'tag';
		$fld_text = 'tag';
		$this->db->select($fld_value.' as value, '.$fld_text.' as text');
		$this->db->where('published','1');
		$rows = $this->db->get($this->tb_data)->result();
		$ret_array = array();
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
	}
	
	// get record by field
	function get_by_field($field_name, $field_value){
		return model_get_by_field($this->tb_data, $field_name, $field_value);
	}
	
	// get number of persons in database
	function count_all($cari = ''){
		if($cari) $this->db->where("tag like '%$cari%' OR title like '%$cari%'");
		$this->db->from($this->tb_data);
		return $this->db->count_all_results();
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari = ''){
		$this->db->select('id, tag, title');
		if($cari) $this->db->where("tag like '%$cari%' OR title like '%$cari%'");
		$this->db->order_by('id','asc');
		return $this->db->get($this->tb_data, $limit, $offset);
	}
	
	// get person by id
	function get_by_id($id){
		return model_get_by_id($this->tb_data, $id);
	}
	
	// add new person
	function save($data){
		$this->db->insert($this->tb_data, $data);
		return $this->db->insert_id();
	}
	// update person by id
	function update($id, $data){
		$this->db->where('id', $id);
		$this->db->update($this->tb_data, $data);
	}
	// delete person by id
	function delete($ids=array()){
		$this->db->where_in('id', $ids);
		$this->db->delete($this->tb_data);
	}
}

?>