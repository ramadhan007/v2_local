<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ArticleWidgetModel extends CI_Model {
	
	// table name
	private $table= 'tb_article_widget';

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
		$substract = $usetop ? "0" : "1";
		$query = "SELECT id as value, title as text
			FROM $this->table
			ORDER BY ordering";
		$rows = get_rows($query);
		$ret_array = $prev_array;
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
	}
	
	// get number of persons in database
	function count_all($cari='', $article_id){
		return model_count_all($this->table, $cari, array('name','title'), 'article_id', $article_id);
	}
	
	// get persons with paging
	function get_paged_list($limit = 10, $offset = 0, $cari='', $article_id){
		return model_get_paged_list($this->table, $limit, $offset, 'id, name, title, published', 'name', 'asc', $cari, array('name', 'title'), 'article_id', $article_id);
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