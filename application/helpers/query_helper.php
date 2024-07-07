<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_field_by_id'))
{
	function get_field_by_id($table, $id, $field){
		$CI =& get_instance();
		if(trim($field)!='*') $CI->db->select($field, FALSE);
		$CI->db->from($table);
		$CI->db->where('id',$id);
		$row = $CI->db->get()->row();
		if(count($row)){
			if(strpos(trim($field),',') || trim($field)=='*'){
				return $row;
			}
			else{
				return $row->$field;
			}
		}else{
			return '';
		}
	}
}

if ( ! function_exists('get_server_time'))
{
	function get_server_time()
	{
		$time_diff = -1;	// for server in malaysia
		return get_val("SELECT DATE_ADD(NOW(), INTERVAL $time_diff HOUR);");
	}
}

if ( ! function_exists('fix_code'))
{
	function fix_code($str)
	{
		$ar_str = explode("'",$str);
		for($i=0;$i<count($ar_str);$i++)
		{
			if($i%2==0)
			{
				$ar_str[$i] = str_replace(chr(13).chr(10), " ",$ar_str[$i]);
				$ar_str[$i] = str_replace(chr(9), " ",$ar_str[$i]);
				while(strpos($ar_str[$i],'  ')!==false)
				{
					$ar_str[$i] = str_replace('  ',' ',$ar_str[$i]);
				}
			}
		}
		return implode("'",$ar_str);
	}
}

if ( ! function_exists('get_rows'))
{
	function get_rows($query)
	{
		$CI =& get_instance();
		return $CI->db->query($query)->result();
	}
}

if ( ! function_exists('get_row'))
{
	function get_row($query, $fixlimit=true)
	{
		if($fixlimit){
			$query = fix_code($query);
			if(substr($query,-1)==';') $query = substr($query,0,-1);
			if(!strpos(strtolower($query),' limit ')){
				$query = $query." limit 0,1";
			}
			else{
				$query = substr($query,0,strpos(strtolower($query),' limit '))." limit 0,1";
			}
		}
		$CI =& get_instance();
		$rows = $CI->db->query($query)->result();
		if(count($rows)){
			return $rows[0];
		}
		else{
			return $rows;
		}
	}
}

if ( ! function_exists('get_val'))
{
	function get_val($query, $fixlimit=true)
	{
		if($fixlimit){
			$query = fix_code($query);
			if(substr($query,-1)==';') $query = substr($query,0,-1);
			if(!strpos(strtolower($query),' limit ')){
				$query = $query." limit 0,1";
			}
			else{
				$query = substr($query,0,strpos(strtolower($query),' limit '))." limit 0,1";
			}
		}
		$CI =& get_instance();
		$rows = $CI->db->query($query)->result();
		if(count($rows)){
			foreach($rows[0] as $key=>$val)
			{
				return $val;
			}
		}
		else{
			return '';
		}
	}
}

if ( ! function_exists('exe_mysql'))
{
	function exe_mysql($function)
	{
		$CI =& get_instance();
		$CI->db->select("($function) as value", FALSE);
		$row = $CI->db->get()->row();
		return $row->value;
	}
}

if ( ! function_exists('run_query'))
{
	function run_query($query)
	{
		$CI =& get_instance();
		return $CI->db->query($query);
	}
}

if ( ! function_exists('get_rows_array'))
{
	function get_rows_array($query)
	{
		$CI =& get_instance();
		$rows = $CI->db->query($query)->result();
		
		$array_data = array();
		foreach($rows as $row){
			$el_array = array();
			foreach($row as $key=>$val){
				$el_array[$key] = $val;
			}
			$array_data[] = $el_array;
		}
		return $array_data;
	}
}

if ( ! function_exists('get_row_array'))
{
	function get_row_array($query, $fixlimit=true)
	{
		if($fixlimit){
			$query = fix_code($query);
			if(substr($query,-1)==';') $query = substr($query,0,-1);
			if(!strpos(strtolower($query),' limit ')){
				$query = $query." limit 0,1";
			}
			else{
				$query = substr($query,0,strpos(strtolower($query),' limit '))." limit 0,1";
			}
		}
		$CI =& get_instance();
		$rows = $CI->db->query($query)->result();
		if(count($rows)){
			$row = $rows[0];
			
			$el_array = array();
			foreach($row as $key=>$val){
				$el_array[$key] = $val;
			}
			return $el_array;
			
		}
		else{
			return array();
		}
	}
}

if ( ! function_exists('get_array'))
{
	function get_array($query, $field_id = 'id', $field_name = 'name')
	{
		$CI =& get_instance();
		$rows = $CI->db->query($query)->result();
		
		$array_data = array();
		foreach($rows as $row){
			$array_data[] = array(
					'value' => $row->$field_id,
					'text' => $row->$field_name,
				);
		}
		return $array_data;
	}
}

if ( ! function_exists('get_table_fields'))
{
	function get_table_fields($table_name, $as_array = false, $ordinal_position_start=0, $ordinal_position_end=0){
		$str_cond = "";
		if($ordinal_position_start && $ordinal_position_end){
			$str_cond = "AND ordinal_position BETWEEN $ordinal_position_start AND $ordinal_position_end";
		}elseif($ordinal_position_start){
			$str_cond = "AND ordinal_position >= $ordinal_position_start";
		}elseif($ordinal_position_end){
			$str_cond = "AND ordinal_position <= $ordinal_position_end";
		}
		$rows_f = get_rows("SELECT 	column_name, ordinal_position
				FROM 	INFORMATION_SCHEMA.COLUMNS 
				WHERE 	TABLE_SCHEMA = 'umrohmal_umroh' 
						AND TABLE_NAME = '$table_name'
						$str_cond");
		if($as_array){
			$fields = array();
		}else{
			$fields = "";
		}
		foreach($rows_f as $row_f){
			if($as_array){
				$fields[] = $row_f->column_name;
			}else{
				$fields .= ($fields ? "," : "").$row_f->column_name;
			}
		}
		
		return $fields;
	}
}

if ( ! function_exists('get_table_data_ordinal_position'))
{
	function get_table_data_ordinal_position($table_name, $id, $ordinal_position_start=0, $ordinal_position_end=0){
		$fields = get_table_fields($table_name, false, $ordinal_position_start, $ordinal_position_end);
		if(strpos($fields,",")===false){
			return get_val(" select $fields from $table_name where id = '$id'  ", false);
		}else{
			return get_row(" select $fields from $table_name where id = '$id'  ", false);
		}
	}
}

?>