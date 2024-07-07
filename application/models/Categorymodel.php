<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CategoryModel extends CI_Model {
	
	// table name
	private $table= 'tb_category';

	function __construct(){
		parent::__construct();
	}
	
	//count content
	function count_content($id)
	{
		$count = get_val("SELECT COUNT(*) FROM tb_article WHERE concat(',',category_id,',') LIKE '%,$id,%'");
		return $count ? $count : 0;
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
	function get_list($sekolah_id, $prev_array = array(), $usetop=false)
	{
		$cond = $sekolah_id ? "WHERE sekolah_id = '$sekolah_id'" : "";
		$substract = $usetop ? "0" : "1";
		$query = "SELECT id as value,
			CONCAT(REPEAT('&nbsp;',(fc_category_level(id)-$substract)*3),(CASE fc_category_level(id) WHEN 1 THEN '' ELSE '-&nbsp;' END),title) as text
			FROM $this->table
			$cond
			ORDER BY sekolah_id, fc_category_ordering(id)";
		$rows = get_rows($query);
		$ret_array = $prev_array;
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
	}
	
	// get number of records in database
	function count_all($cari=''){
		$sekolah_id = $this->session->userdata('active_sekolah_id');
		return model_count_all($this->table, $cari, array('title','alias'), 'sekolah_id', $sekolah_id);
	}
	
	// get records with paging
	function get_paged_list($limit = 10, $offset = 0, $cari=''){
		$sekolah_id = $this->session->userdata('active_sekolah_id');
		
		//condition
		$cond = "WHERE sekolah_id = '$sekolah_id'";
		if($cari) $cond .=  ($cond ? " AND " : "WHERE ")."(title = '$cari' OR alias = '$cari')";
		
		//fix offset
		$offset = $offset ? $offset : 0;
		
		//query
		$query = "SELECT id, sekolah_id,
			CONCAT(REPEAT('&nbsp;',((fc_category_level(id))-1)*3),(CASE fc_category_level(id) WHEN 1 THEN '' ELSE '<sup>|_</sup>&nbsp;' END),title) as title, alias, icon, CONCAT(REPEAT('&nbsp;',((fc_category_level(id))-1)*3),(CASE fc_category_level(id) WHEN 1 THEN '' ELSE '<sup>|_</sup>&nbsp;' END),ordering) as ordering, published, fc_get_sekolah_nama(sekolah_id) as sekolah_nama
			FROM $this->table
			$cond
			ORDER BY sekolah_id, fc_category_ordering(id)
			LIMIT $offset, $limit";
			
		//echo $query;
			
		return get_rows($query);
	}
	
	function get_all(){
		return model_get_all($this->table, 'id', 'asc');
	}
	
	function update_get_ordering($id, $new_ordering)
	{
		//get old ordering
		$old_ordering = $this->get_ordering($id);
		
		//get sekolah_id
		$sekolah_id = $this->get_field_by_id($id,'sekolah_id');
		
		//get parent id
		$parent_id = $this->get_field_by_id($id,'parent_id');
		
		//get max ordering same parent
		$max_ordering = $this->get_max_ordering($sekolah_id, $parent_id);
		
		//get min ordering same parent
		$min_ordering = $this->get_min_ordering($sekolah_id, $parent_id);
		
		if($new_ordering > $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering-1 WHERE sekolah_id = '$sekolah_id' AND parent_id = '$parent_id' AND (ordering > $old_ordering AND ordering <= $new_ordering)";
			run_query($query);
		}
		elseif($new_ordering < $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering+1 WHERE sekolah_id = '$sekolah_id' AND parent_id = '$parent_id' AND (ordering < $old_ordering AND ordering >= $new_ordering)";
			run_query($query);
		}
		return $new_ordering;
	}
	
	function get_ordering($id)
	{
		$ordering = $this->get_field_by_id($id, 'ordering');
		return $ordering ? $ordering : 0;
	}
	
	function get_min_ordering($sekolah_id, $parent_id)
	{
		$this->db->select_min('ordering');
		$this->db->where('sekolah_id',$sekolah_id);
		$this->db->where('parent_id',$parent_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function get_max_ordering($sekolah_id, $parent_id)
	{
		$this->db->select_max('ordering');
		$this->db->where('sekolah_id',$sekolah_id);
		$this->db->where('parent_id',$parent_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	//get array for menu list (select, radio)
	function get_list_ordering($sekolah_id, $id)
	{
		$parent_id = $this->get_field_by_id($id,'parent_id');
		$fld_value = 'ordering';
		$fld_text = 'title';
		$this->db->select($fld_value.' as value, '.$fld_text.' as text');
		$this->db->where('sekolah_id',$sekolah_id);
		$this->db->where('parent_id',$parent_id);
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