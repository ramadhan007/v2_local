<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MenuItemModel extends CI_Model {
	
	// table name
	private $table= 'tb_menu_item';

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
	function get_list($prev_array = array(), $usetop=false)
	{
		$menu_id = $this->session->userdata($this->controller.'_menu_id');
		$substract = $usetop ? "0" : "1";
		$query = "SELECT id as value,
			CONCAT(REPEAT('&nbsp;',(fc_menu_item_level(id)-$substract)*3),(CASE fc_menu_item_level(id) WHEN 1 THEN '' ELSE '-&nbsp;' END),title) as text
			FROM $this->table
			WHERE menu_id = '$menu_id'
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
	function count_all($cari=''){
		$main_field = 'menu_id';
		$main_id = $this->session->userdata($this->controller.'_menu_id');
		return model_count_all($this->table, $cari, array('title','alias'), $main_field, $main_id);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari=''){
		//condition
		$cond = "WHERE a.menu_id = '".$this->session->userdata($this->controller.'_menu_id')."'";
		if($cari) $cond .= " AND (a.title = '$cari' OR a.alias = '$cari')";
		
		//fix offset
		$offset = $offset ? $offset : 0;
		
		//query
		$query = "SELECT a.id,
			CONCAT(REPEAT('&nbsp;',((fc_menu_item_level(a.id))-1)*3),(CASE fc_menu_item_level(a.id) WHEN 1 THEN '' ELSE '<sup>|_</sup>&nbsp;' END),a.title) as title, a.alias, a.icon, a.link, CONCAT(REPEAT('&nbsp;',((fc_menu_item_level(a.id))-1)*3),(CASE fc_menu_item_level(a.id) WHEN 1 THEN '' ELSE '<sup>|_</sup>&nbsp;' END),a.ordering) as ordering, a.published, b.name as usertype1
			FROM $this->table as a
				INNER join tb_usertype as b on a.usertype = b.id
			$cond
			ORDER BY fc_menu_item_ordering(a.id)
			".($limit=='all' ? '' : "LIMIT $offset, $limit");
			
		//echo $query;
			
		return get_rows($query);
	}
	
	function get_all(){
		$main_field = 'menu_id';
		$main_id = $this->session->userdata($this->controller.'_menu_id');
		return model_get_all($this->table, 'id', 'asc', $main_field, $main_id);
	}
	
	function update_get_ordering($id, $new_ordering)
	{
		//parent field
		$main_field = 'menu_id';
		$main_id = $this->session->userdata($this->controller.'_menu_id');
		
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
	
	function get_min_ordering($parent_id)
	{
		$this->db->select_min('ordering');
		$this->db->where('menu_id',$this->session->userdata($this->controller.'_menu_id'));
		$this->db->where('parent_id',$parent_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function get_max_ordering($parent_id)
	{
		$this->db->select_max('ordering');
		$this->db->where('menu_id',$this->session->userdata($this->controller.'_menu_id'));
		$this->db->where('parent_id',$parent_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	//get array for menu list (select, radio)
	function get_list_ordering($id)
	{
		$menu_id = $this->get_field_by_id($id,'menu_id');
		$parent_id = $this->get_field_by_id($id,'parent_id');
		$fld_value = 'ordering';
		$fld_text = 'title';
		$this->db->select($fld_value.' as value, '.$fld_text.' as text');
		$this->db->where('menu_id',$menu_id);
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