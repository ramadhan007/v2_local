<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommentModel extends CI_Model {
	
	// table name
	private $table= 'tb_article_comment';

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
	function get_list($main_id, $prev_array = array(), $usetop=false)
	{
		$substract = $usetop ? "0" : "1";
		$query = "SELECT id as value,
			CONCAT(REPEAT('&nbsp;',(fc_menu_item_level(id)-$substract)*3),(CASE fc_menu_item_level(id) WHEN 1 THEN '' ELSE '-&nbsp;' END),title) as text
			FROM $this->table
			WHERE article_id = '$main_id'
			ORDER BY fc_menu_item_ordering(id)";
		$rows = get_rows($query);
		$ret_array = $prev_array;
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
	}
	
	// get number of persons in database
	function count_all($main_id, $cari=''){
		$main_field = 'article_id';
		return model_count_all($this->table, $cari, array('title','alias'), $main_field, $main_id);
	}
	
	// get persons with paging
	function get_paged_list($main_id, $limit = 10, $offset = 0, $cari=''){
		//condition
		$cond = "WHERE article_id = '".$main_id."'";
		if($cari) $cond .= " AND (title = '$cari' OR alias = '$cari')";
		
		//fix offset
		$offset = $offset ? $offset : 0;
		
		//query
		$query = "SELECT id,
			CONCAT(REPEAT('&nbsp;',((fc_menu_item_level(id))-1)*3),(CASE fc_menu_item_level(id) WHEN 1 THEN '' ELSE '<sup>|_</sup>&nbsp;' END),title) as title, alias, icon, link, CONCAT(REPEAT('&nbsp;',((fc_menu_item_level(id))-1)*3),(CASE fc_menu_item_level(id) WHEN 1 THEN '' ELSE '<sup>|_</sup>&nbsp;' END),ordering) as ordering, published
			FROM $this->table
			$cond
			ORDER BY fc_menu_item_ordering(id)
			LIMIT $offset, $limit";
			
		//echo $query;
			
		return get_rows($query);
	}
	
	function get_all($main_id){
		$main_field = 'article_id';
		return model_get_all($this->table, 'id', 'asc', $main_field, $main_id);
	}
	
	function update_get_ordering($main_id, $id, $new_ordering)
	{
		//parent field
		$main_field = 'article_id';
		
		//get old ordering
		$old_ordering = $this->get_ordering($id);
		
		//get parent id
		$parent_id = $this->get_field_by_id($id,'parent_id');
		
		//get max ordering same parent
		$max_ordering = $this->get_max_ordering($parent_id);
		
		//get min ordering same parent
		$min_ordering = $this->get_min_ordering($parent_id);
		
		if($new_ordering > $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering-1 WHERE $main_field = '$main_id' AND parent_id = '$parent_id' AND (ordering > $old_ordering AND ordering <= $new_ordering)";
			run_query($query);
		}
		elseif($new_ordering < $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering+1 WHERE $main_field = '$main_id' AND parent_id = '$parent_id' AND (ordering < $old_ordering AND ordering >= $new_ordering)";
			run_query($query);
		}
		return $new_ordering;
	}
	
	function get_ordering($id)
	{
		$ordering = $this->get_field_by_id($id, 'ordering');
		return $ordering ? $ordering : 0;
	}
	
	function get_min_ordering($main_id, $parent_id)
	{
		$this->db->select_min('ordering');
		$this->db->where('article_id',$main_id);
		$this->db->where('parent_id',$parent_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function get_max_ordering($main_id, $parent_id)
	{
		$this->db->select_max('ordering');
		$this->db->where('article_id',$main_id);
		$this->db->where('parent_id',$parent_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	//get array for menu list (select, radio)
	function get_list_ordering($id)
	{
		$main_id = $this->get_field_by_id($id,'article_id');
		$parent_id = $this->get_field_by_id($id,'parent_id');
		$fld_value = 'ordering';
		$fld_text = 'title';
		$this->db->select($fld_value.' as value, '.$fld_text.' as text');
		$this->db->where('article_id',$main_id);
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