<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('template_view_dir'))
{
	function template_view_dir($file_view, $override_template="")
	{
		$CI =& get_instance();
		if($override_template){
			if($override_template=='admin'){
				return $CI->config->item('template_admin').'/'.$file_view;
			}else{
				return $CI->config->item('template_user').'/'.$file_view;
			}
		}else{
			$uri = uri_string();
			$ar_uri = explode("/",$uri);
			if($ar_uri[0]=='admin'){
				return $CI->config->item('template_admin').'/'.$file_view;
			}
			else{
				return $CI->config->item('template_user').'/'.$file_view;
			}
		}
	}
}

if ( ! function_exists('fix_read_more'))
{
	function fix_read_more($text)
	{
		$CI =& get_instance();
		$visit_id = $CI->session->userdata('visit_id');
		$text = str_replace('{[promo_code]}','MALINDO '.strtoupper(base_convert($visit_id,10,36)),$text);
		return str_replace('{[readmore]}','',$text);
	}
}

if ( ! function_exists('put_base_url'))
{
	function put_base_url($rows)
	{
		if(is_array($rows)){
			foreach($rows as $row){
				foreach($row as $key=>$val){
					if(strpos($val,base_url())!==false){
						$row->$key = str_replace(base_url(),'{[base_url]}',$val);
					}
				}
			}
		}
		else{
			if(is_object($rows)){
				foreach($rows as $key=>$val){
					if(strpos($val,base_url())!==false){
						$rows->$key = str_replace(base_url(),'{[base_url]}',$val);
					}
				}
			}
			else{
				$rows = str_replace(base_url(),'{[base_url]}',$rows);
			}
		}
		return $rows;
	}
}

if ( ! function_exists('fix_base_url'))
{
	function fix_base_url($rows)
	{
		if(is_array($rows)){
			foreach($rows as $row){
				foreach($row as $key=>$val){
					if(strpos($val,'{[base_url]}')!==false){
						$row->$key = str_replace('{[base_url]}',base_url(),$val);
					}
				}
			}
		}
		else{
			if(is_object($rows)){
				foreach($rows as $key=>$val){
					if(strpos($val,'{[base_url]}')!==false){
						$rows->$key = str_replace('{[base_url]}',base_url(),$val);
					}
				}
			}
			else{
				$rows = str_replace('{[base_url]}',base_url(),$rows);
			}
		}
		return $rows;
	}
}

if ( ! function_exists('rem_base_url'))
{
	function rem_base_url($rows)
	{
		if(is_array($rows)){
			foreach($rows as $row){
				foreach($row as $key=>$val){
					if(strpos($val,'{[base_url]}')!==false){
						$row->$key = str_replace('{[base_url]}','',$val);
					}
				}
			}
		}
		else{
			if(is_object($rows)){
				foreach($rows as $key=>$val){
					if(strpos($val,'{[base_url]}')!==false){
						$rows->$key = str_replace('{[base_url]}','',$val);
					}
				}
			}
			else{
				$rows = str_replace('{[base_url]}','',$rows);
			}
		}
		return $rows;
	}
}

if ( ! function_exists('load_controller_image'))
{
	function load_controller_image($controller, $main_id, $name)
	{
		$row = get_row("SELECT 	*
						FROM 	tb_controller_image
						WHERE 	controller = '$controller' AND main_id = '$main_id' AND name = '$name' and published");
		return fix_base_url($row);
	}
}

if ( ! function_exists('load_article'))
{
	function load_article($alias, $full=false)
	{
		$fields = ($full ? "*" : "id, title, alias, intro");
		return get_row("SELECT $fields from tb_article WHERE alias = '$alias' and published");
	}
}

if ( ! function_exists('load_articles'))
{
	function load_articles($alias, $full=false)
	{
		$rowc = get_row("SELECT * FROM tb_category WHERE alias = '$alias'");
		$limit = $rowc->per_page ? " LIMIT 0,$rowc->per_page" : "";
		if($full){
			$rows = get_rows("SELECT * from tb_article WHERE concat(',',category_id,',') like '%,$rowc->id,%' and published='1' order by date_insert DESC".$limit);
		}
		else{
			$rows = get_rows("SELECT id, title, alias, intro from tb_article WHERE concat(',',category_id,',') like '%,$rowc->id,%' and published='1' order by date_insert DESC".$limit);
		}
		$return = array();
		$return['title'] = $rowc;
		$return['item'] = fix_base_url($rows);
		return $return;
	}
}

if ( ! function_exists('load_images'))
{
	function load_images($sekolah_id, $name)
	{
		$rows = get_rows("SELECT *
			FROM tb_image
			WHERE sekolah_id = '$sekolah_id' and name = '$name'");
		return fix_base_url($rows);
	}
}

if ( ! function_exists('load_category_images'))
{
	function load_category_images($alias, $name)
	{
		$rowc = get_row("SELECT id, image_per_page FROM tb_category WHERE alias = '$alias'");
		$limit = $rowc->image_per_page ? " LIMIT 0,$rowc->image_per_page" : "";
		$rowa = get_row("select * from tb_article limit 0,1");
		$fld_article = "";
		foreach($rowa as $key=>$val){
			if($key!='body') $fld_article = $fld_article.($fld_article ? ", " : "")."b.".$key." as article_".$key;
		}
		$rows = get_rows("SELECT a.*, $fld_article FROM tb_article_image AS a INNER JOIN tb_article AS b ON a.article_id = b.id
			WHERE b.published AND concat(',',b.category_id,',') like '%,$rowc->id,%' AND name = '$name' AND a.published order by b.date_insert DESC".$limit);
		return fix_base_url($rows);
	}
}

if ( ! function_exists('load_article_images'))
{
	function load_article_images($article_alias)
	{
		$rows = get_rows("SELECT a.*
			FROM tb_article_image AS a INNER JOIN tb_article AS b ON a.article_id = b.id
			WHERE b.alias = '$article_alias'");
		return fix_base_url($rows);
	}
}

if ( ! function_exists('load_article_image'))
{
	function load_article_image($article_id, $name)
	{
		$row = get_row("SELECT * FROM tb_article_image WHERE article_id = '$article_id' ".($name ? "AND name = '$name'" : ""));
		return fix_base_url($row);
	}
}

if ( ! function_exists('load_widget'))
{
	function load_widget($alias, $name)
	{
		$rowc = get_row("SELECT id, widget_per_page FROM tb_category WHERE alias = '$alias'");
		$limit = $rowc->widget_per_page ? " LIMIT 0,$rowc->widget_per_page" : "";
		$rows = get_rows("SELECT a.*, b.alias FROM tb_article_widget AS a INNER JOIN tb_article AS b ON a.article_id = b.id
			WHERE concat(',',b.category_id,',') like '%,$rowc->id,%' AND name = '$name' AND a.published='1' order by b.date_insert DESC".$limit);
		return fix_base_url($rows);
	}
}

if ( ! function_exists('load_article_widget'))
{
	function load_article_widget($article_id, $name='')
	{
		$row = get_row("SELECT * FROM tb_article_widget WHERE article_id = '$article_id' ".($name ? "AND name = '$name'" : ""));
		return fix_base_url($row);
	}
}

if ( ! function_exists('load_menu'))
{
	function load_menu($alias, $usertype=''){
		$menu_id = get_val("select id from tb_menu where alias = '$alias'");
		$cond_usertype = $usertype ? "AND usertype >= $usertype" : "";
		$query = "SELECT	id, title, alias, icon, link, base_controller, fc_count_menu_item_child(id) AS child
				FROM 	tb_menu_item
				WHERE	menu_id = '$menu_id' AND parent_id = '0' AND published = '1'
						$cond_usertype
				ORDER 	BY fc_menu_item_ordering(id)";
		$rows = get_rows($query);
		return $rows;
	}
}

if ( ! function_exists('load_sub_menu'))
{
	function load_sub_menu($parent_id, $usertype='')
	{
		$cond_usertype = $usertype ? "AND usertype >= $usertype" : "";
		$query = "SELECT	id, title, alias, icon, link, base_controller, fc_count_menu_item_child(id) AS child
					FROM 	tb_menu_item
					WHERE	parent_id = '$parent_id' AND published = '1'
							$cond_usertype
					ORDER 	BY fc_menu_item_ordering(id)";
		$rows = get_rows($query);
		return $rows;
	}
}

if ( ! function_exists('load_menu_category'))
{
	function load_menu_category($alias)
	{
		$category_id = get_val("select id from tb_category where alias = '$alias'");
		$query = "SELECT	id, title, alias, icon, alias as link, fc_count_category_child(id) AS child
				FROM 	tb_category
				WHERE	parent_id = '$category_id' AND published = '1'
				ORDER 	BY fc_category_ordering(id)";
		$rows = get_rows($query);
		return $rows;
	}
}

if ( ! function_exists('load_sub_menu_category'))
{
	function load_sub_menu_category($parent_id)
	{
		$query = "SELECT	id, title, alias, icon, alias as link, fc_count_category_child(id) AS child
				FROM 	tb_category
				WHERE	parent_id = '$parent_id' AND published = '1'
				ORDER 	BY fc_category_ordering(id)";
		$rows = get_rows($query);
		return $rows;
	}
}
?>