<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TplEmailModel extends CI_Model {
	
	// table name
	private $tb_data= 'tb_tpl_email';

	function __construct(){
		parent::__construct();
	}
	
	//get array for menu list (select, radio)
	function get_list($category='')
	{
		$this->db->select('id, tag, subject');
		if($category) $this->db->where('category',$category);
		$rows = $this->db->get($this->tb_data)->result();
		$ret_array = array();
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->id, 'text' => '&nbsp;'.$row->tag.' : '.$row->subject);
		}
		return $ret_array;
	}
	
	// fields dengan custom where
	function get_custom($where, $field){
		$this->db->select($field);
		$this->db->from($this->tb_data);
		$this->db->where($where);
		$row = $this->db->get()->row();
		if(strpos(trim($field),',')){
			return $row;
		}
		else{
			return $row->$field;
		}
	}
	
	// dapatkan nilai fields dari id
	function get_field_by_id($id, $field){
		$this->db->select($field);
		$this->db->from($this->tb_data);
		$this->db->where('id',$id);
		$row = $this->db->get()->row();
		if(strpos(trim($field),',')){
			return $row;
		}
		else{
			return $row->$field;
		}
	}
	
	// get record by field
	function get_by_field($field_name, $field_value){
		return model_get_by_field($this->tb_data, $field_name, $field_value);
	}
	
	// get number of persons in database
	function count_all($cari=''){
		if($cari) $this->db->where("category LIKE '%$cari%' OR tag LIKE '%$cari%' OR subject LIKE '%$cari%'");
		$this->db->from($this->tb_data);
		return $this->db->count_all_results();
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari = ''){
		$this->db->select('id, category, tag, subject');
		if($cari) $this->db->where("category LIKE '%$cari%' OR tag LIKE '%$cari%' OR subject LIKE '%$cari%'");
		$this->db->order_by('id','asc');
		if($limit=='all') return $this->db->get($this->tb_data);
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