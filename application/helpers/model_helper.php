<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('model_get_by_id'))
{
	function model_get_by_id($table, $id, $fields = '')
	{
		$CI =& get_instance();
		if($fields) $CI->db->select($fields, FALSE);
		$CI->db->where('id', $id);
		return $CI->db->get($table)->row();
	}
}

if ( ! function_exists('model_get_by_field'))
{
	function model_get_by_field($table, $field_name, $field_value)
	{
		$CI =& get_instance();
		$CI->db->where($field_name, $field_value);
		return $CI->db->get($table)->row();
	}
}

if ( ! function_exists('model_get_field_by_id'))
{
	function model_get_field_by_id($table, $id, $field)
	{
		$CI =& get_instance();
		$CI->db->select($field, FALSE);
		$CI->db->from($table);
		$CI->db->where('id',$id);
		$row = $CI->db->get()->row();
		if(strpos(trim($field),',')){
			return $row;
		}
		else{
			return $row->$field;
		}
	}
}

if ( ! function_exists('model_get_field_by_id_join'))
{
	function model_get_field_by_id_join($array_table = array(), $id, $field)
	{
		$array_field = explode(',',str_replace(' ','',$field));
		$query = "SELECT";
		foreach($array_field as $el)
		{
			$query .= " ".$el." AS ".str_replace('.','_',$el).",";
		}
		$query = rtrim($query,",");
		$query .= "\nFROM ".$array_table[0]['table']." AS t0";
		for($i=1;$i<count($array_table);$i++)
		{
			$query .= "\nINNER JOIN ".$array_table[$i]['table']." AS t".$i." ON ".$array_table[$i]['on'];
		}
		$query .= "\nWHERE t0.id = '$id'";
		
		$CI =& get_instance();
		$row = $CI->db->query($query)->row();
		if(strpos(trim($field),',')){
			return $row;
		}
		else{
			$ret_field = str_replace('.','_',$field);
			return $row->$ret_field;
		}
	}
}

if ( ! function_exists('model_get_field_by_field'))
{
	function model_get_field_by_field($table, $keyfield, $keyval, $field)
	{
		$CI =& get_instance();
		$CI->db->select($field, FALSE);
		$CI->db->from($table);
		$CI->db->where($keyfield,$keyval);
		$row = $CI->db->get()->row();
		if(strpos(trim($field),',')){
			return $row;
		}
		else{
			if($row){
				return $row->$field;
			}else{
				return '';
			}
		}
	}
}

if ( ! function_exists('model_get_list'))
{
	function model_get_list($table, $value='id', $text = 'name', $field_published=false, $where_fields = '', $where_vals = '', $order_by='', $order_dir='asc')
	{
		$CI =& get_instance();
		
		if($where_fields){
			if(is_array($where_fields)){
				for($i=0;$i<count($where_fields);$i++){
					$CI->db->where($where_fields[$i], $where_vals[$i]);
				}
			}else{
				$CI->db->where($where_fields, $where_vals);
			}
		}
		
		$CI->db->select($value.' AS value, '.$text.' AS text', FALSE);
		if($field_published) $CI->db->where('published','1');
		if($order_by) $CI->db->order_by($order_by, $order_dir);
		$rows = $CI->db->get($table)->result();
		$ret_array = array();
		foreach($rows as $row)
		{
			$ret_array[] = array('value' => $row->value, 'text' => $row->text);
		}
		return $ret_array;
	}
}

if ( ! function_exists('model_count_all'))
{
	function model_count_all($table, $cari='', $array_field=array(), $where_fields = '', $where_vals = '')
	{
		$CI =& get_instance();
		
		if($where_fields){
			if(is_array($where_fields)){
				for($i=0;$i<count($where_fields);$i++){
					$CI->db->where($where_fields[$i], $where_vals[$i]);
				}
			}else{
				$CI->db->where($where_fields, $where_vals);
			}
		}
		
		$cari = str_replace("'","''",$cari);
		if($cari){
			$where = "";
			foreach($array_field as $field)
			{
				$where = $where.($where ? " OR " : "")."$field LIKE '%$cari%'";
			}
			if($where) $CI->db->where("($where)");
		}
		
		$CI->db->from($table);
		return $CI->db->count_all_results();
	}
}

if ( ! function_exists('model_get_paged_list'))
{
	function model_get_paged_list($table, $limit = 10, $offset = 0, $select_fields='', $order_by='', $order_dir='asc', $cari='', $array_field=array(), $where_fields = '', $where_vals = '')
	{
		$CI =& get_instance();
		
		if($select_fields) $CI->db->select($select_fields, FALSE);
		
		if($where_fields){
			if(is_array($where_fields)){
				for($i=0;$i<count($where_fields);$i++){
					$CI->db->where($where_fields[$i], $where_vals[$i]);
				}
			}else{
				$CI->db->where($where_fields, $where_vals);
			}
		}
		
		$cari = str_replace("'","''",$cari);
		if($cari){
			$where = "";
			foreach($array_field as $field)
			{
				$where = $where.($where ? " OR " : "")."$field LIKE '%$cari%'";
			}
			if($where) $CI->db->where("($where)");
		}
		
		if($order_by){
			if(is_array($order_by)){
				for($i=0;$i<count($order_by);$i++){
					$CI->db->order_by($order_by[$i], $order_dir[$i]);
				}
			}else{
				$CI->db->order_by($order_by, $order_dir);
			}
		}
		
		if($limit=='all' || !$limit){
			return $CI->db->get($table);
		}else{
			return $CI->db->get($table, $limit, $offset);
		}
	}
}

if ( ! function_exists('model_get_all'))
{
	function model_get_all($table, $order_by='', $order_dir='asc', $where_fields = '', $where_vals = '')
	{
		$CI =& get_instance();
		if($where_fields){
			if(is_array($where_fields)){
				for($i=0;$i<count($where_fields);$i++){
					$CI->db->where($where_fields[$i], $where_vals[$i]);
				}
			}else{
				$CI->db->where($where_fields, $where_vals);
			}
		}
		if($order_by) $CI->db->order_by($order_by, $order_dir);
		return $CI->db->get($table);
	}
}

if ( ! function_exists('model_save'))
{
	function model_save($table, $data)
	{
		$CI =& get_instance();
		$CI->db->insert($table, $data);
		return $CI->db->insert_id();
	}
}

if ( ! function_exists('model_update'))
{
	function model_update($table, $id, $data, $field_id = 'id')
	{
		$CI =& get_instance();
		$CI->db->where($field_id, $id);
		$CI->db->update($table, $data);
	}
}

if ( ! function_exists('model_delete'))
{
	function model_delete($table, $ids=array(), $field_id = 'id')
	{
		$CI =& get_instance();
		$CI->db->where_in($field_id, $ids);
		$CI->db->delete($table);
	}
}

if ( ! function_exists('model_delete_tag'))
{
	function model_delete_tag($table, $ids=array(), $user_delete = '', $date_delete = '', $field_id = 'id')
	{
		$CI =& get_instance();
		$CI->db->where_in($field_id, $ids);
		$data = array();
		$data = array('deleted'=>'1');
		if($user_delete) $data['user_delete'] = $user_delete;
		if($date_delete) $data['date_delete'] = $date_delete;
		$CI->db->update($table, $data);
	}
}

if ( ! function_exists('model_check_data'))
{
	function model_check_data($table, $keyfield, $keyval, $checkfield='')
	{
		if(!$checkfield) $checkfield = $keyfield;
		$CI =& get_instance();
		
		$CI->db->select($checkfield, FALSE);
		$CI->db->from($table);
		$CI->db->where($keyfield,$keyval);
		$query = $CI->db->get();
		if($query->num_rows()>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

if ( ! function_exists('model_check_field_db_not'))
{
	function model_check_field_db_not($table, $id, $field, $value)
	{
		$CI =& get_instance();
		
		$CI->db->from($table);
		$CI->db->where('id',$id);
		$CI->db->where("$field <>",$value);
		if($this->db->count_all_results()){
			return true;
		}
		else{
			return false;
		}
	}
}

if ( ! function_exists('model_get_fields'))
{
	function model_get_fields($table)
	{
		$CI =& get_instance();
		$fields = $CI->db->list_fields($table);
		$retarray = array();
		foreach ($fields as $field)
		{
		   $retarray[] = $field;
		}
		return $retarray;
	}
}
?>