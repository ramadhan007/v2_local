<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ArticleModel extends CI_Model {
	
	// table name
	private $table= 'tb_article';

	function __construct(){
		parent::__construct();
	}
	
	//count images
	function count_image($id)
	{
		$count = get_val("SELECT COUNT(*) FROM tb_article_image WHERE article_id = '$id'");
		return $count ? $count : 0;
	}
	
	//count widget
	function count_widget($id)
	{
		$count = get_val("SELECT COUNT(*) FROM tb_article_widget WHERE article_id = '$id'");
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
	function get_list($prev_array = array(), $usetop=false)
	{
		$substract = $usetop ? "0" : "1";
		$query = "SELECT id as value, title as text
			FROM $this->table
			ORDER BY date_insert desc";
		$rows = get_rows($query);
		$ret_array = $prev_array;
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
	}
	
	// get number of persons in database
	function count_all($cari='', $category_id=''){
		$ar_where_field = array();
		$ar_where_val = array();
		
		$sekolah_id = $this->session->userdata('active_sekolah_id');
		$ar_where_field[] = 'sekolah_id';
		$ar_where_val[] = $sekolah_id;
		
		if($category_id){
			$ar_where_field[] = "concat(',',category_id,',') like";
			$ar_where_val[] = "%,".$category_id.",%";
		}
		
		return model_count_all($this->table, $cari, array('title','alias','keywords'), $ar_where_field, $ar_where_val);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari='', $category_id=''){
		$ar_where_field = array();
		$ar_where_val = array();
		
		$sekolah_id = $this->session->userdata('active_sekolah_id');
		$ar_where_field[] = 'sekolah_id';
		$ar_where_val[] = $sekolah_id;
		
		if($category_id){
			$ar_where_field[] = "concat(',',category_id,',') like";
			$ar_where_val[] = "%,".$category_id.",%";
		}
		
		return model_get_paged_list($this->table, $limit, $offset, 'id, title, alias, fc_count_article_image(id) as image, fc_count_article_widget(id) as widget, published', 'date_insert', 'desc', $cari, array('title','alias','keywords'), $ar_where_field, $ar_where_val);
	}
	
	function get_all(){
		$category_id = $this->session->userdata($this->controller.'_category_id');
		if($category_id){
			return model_get_all($this->table, 'id', 'asc', 'category_id', $category_id);
		}else{
			return model_get_all($this->table, 'id', 'asc');
		}
	}
	
	function update_get_ordering($id, $new_ordering)
	{
		//get old ordering
		$old_ordering = $this->get_ordering($id);
		
		//get category id
		$category_id = $this->get_field_by_id($id,'category_id');
		
		//get max ordering same category
		$max_ordering = $this->get_max_ordering($category_id);
		
		//get min ordering same category
		$min_ordering = $this->get_min_ordering($category_id);
		
		if($new_ordering > $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering-1 WHERE category_id = '$category_id' AND (ordering > $old_ordering AND ordering <= $new_ordering)";
			run_query($query);
		}
		elseif($new_ordering < $old_ordering)
		{
			$query = "UPDATE $this->table set ordering = ordering+1 WHERE category_id = '$category_id' AND (ordering < $old_ordering AND ordering >= $new_ordering)";
			run_query($query);
		}
		return $new_ordering;
	}
	
	function get_ordering($id)
	{
		$ordering = $this->get_field_by_id($id, 'ordering');
		return $ordering ? $ordering : 0;
	}
	
	function get_min_ordering($category_id)
	{
		$this->db->select_min('ordering');
		$this->db->where('category_id',$category_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	function get_max_ordering($category_id)
	{
		$this->db->select_max('ordering');
		$this->db->where('category_id',$category_id);
		$row = $this->db->get($this->table)->row();
		return $row->ordering ? $row->ordering : 0;
	}
	
	//get array for menu list (select, radio)
	function get_list_ordering($id)
	{
		$category_id = $this->get_field_by_id($id,'category_id');
		$fld_value = 'ordering';
		$fld_text = 'title';
		$this->db->select($fld_value.' as value, '.$fld_text.' as text');
		$this->db->where('category_id',$category_id);
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