<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_logged_in'))
{
	function is_logged_in($type='')
	{
		$ar_return = array();
		$CI =& get_instance();
		$login_data = $CI->session->userdata('login_data'.$type);
		// $login_data = $_SESSION['login_data'.$type];
		if($login_data && $login_data['is_logged_in'])
		{
			$ar_return['logged_in'] = true;
			foreach($login_data['user'] as $key=>$value){
				$ar_return[$key] = $value;
			}
			$ar_return['picture'] = $ar_return['picture'] ? $ar_return['picture'] : base_url('images/no-user.png');
		}
		else
		{
			$ar_return['logged_in'] = false;
			$row = get_row("select a.id, a.username, a.email, a.phone, a.name, a.picture, a.usertype, '' as usertype1, '' as template, '' as menu, '' as sekolah_ids from tb_user as a limit 0,1");
			foreach($row as $key=>$value){
				$ar_return[$key] = '';
			}
		}
		return $ar_return;
	}
}

if ( ! function_exists('controller_view'))
{
	function controller_view()
	{
		$ar_return = array();
		$ar_return['doctitle'] = '';
		$ar_return['title'] = '';
		$ar_return['icon'] = '';
		$ar_return['toptitle'] = '';
		$ar_return['subtitle'] = '';
		$ar_return['keywords'] = '';
		$ar_return['description'] = '';
		$ar_return['author'] = 'Malindo Travel';
		$ar_return['image'] = '';
		$ar_return['image_width'] = '';
		$ar_return['image_height'] = '';
		$ar_return['content'] = '';
		$ar_return['sidebar-left'] = '';
		$ar_return['sidebar-right'] = '';
		return $ar_return;
	}
}

if ( ! function_exists('get_controller_icon'))
{
	function get_controller_icon($controller)
	{
		$sql = "SELECT	a.icon
			FROM 	tb_menu_item AS a
			WHERE	a.`base_controller` = '$controller'";
		return get_val($sql);
	}
}

if ( ! function_exists('check_user_access'))
{
	function check_user_access($user_id, $controller)
	{
		$CI =& get_instance();
		$sql = "SELECT	COUNT(*)
			FROM 	tb_menu_item AS a
					INNER JOIN tb_menu AS b ON a.`menu_id` = b.`id`
					INNER JOIN tb_user AS c ON (c.`usertype` <= b.`usertype` AND c.`usertype` <= a.`usertype`)
			WHERE	c.`id` = '$user_id'	AND REPLACE(a.`base_controller`,'{[admin]}','".$CI->config->item("admin")."') = '$controller' AND b.`published` AND a.`published`";
		// echo $sql; exit();
		return get_val($sql) > 0;
	}
}

if ( ! function_exists('get_controller_function'))
{
	function get_controller_function($link)
	{
		$retval = "";
		$text = str_replace(site_url(),"",$link);
		while(substr($text,0,1)=="/")
		{
			$text = substr($text,1);
		}
		$ar = explode("/",$text);
		if(count($ar))
		{
			$retval = $retval.$ar[0];
		}
		if(count($ar)>1)
		{
			$retval = $retval."/".$ar[1];
		}
		return $retval;
	}
}

if ( ! function_exists('get_join_unique_slug'))
{
	function get_join_unique_slug($controller, $text, $id='')
	{
		$slug = sluggify($text);
		$CI =& get_instance();
		$CI->db->where('alias',$slug);
		if($id) $CI->db->where('id <>',$id);
		$jml = $CI->db->count_all_results('tb_alias');
		if($jml){
			$i = 0;
			while($jml){
				$CI->db->where('alias',$slug.'-'.++$i);
				if($id) $CI->db->where('id <>',$id);
				$jml = $CI->db->count_all_results('tb_alias');
			}
			return $slug.'-'.$i;
		}
		else
		{
			return $slug;
		}
	}
}

if ( ! function_exists('get_unique_slug'))
{
	function get_unique_slug($table, $field, $text, $id='')
	{
		$slug = sluggify($text);
		$CI =& get_instance();
		$CI->db->where($field,$slug);
		if($id) $CI->db->where('id <>',$id);
		$jml = $CI->db->count_all_results($table);
		if($jml)
		{
			$i = 0;
			while($jml){
				$CI->db->where($field,$slug.'-'.++$i);
				if($id) $CI->db->where('id <>',$id);
				$jml = $CI->db->count_all_results($table);
			}
			return $slug.'-'.$i;
		}
		else
		{
			return $slug;
		}
	}
}

if ( ! function_exists('sluggify'))
{
	function sluggify($url)
	{
		# Prep string with some basic normalization
		$url = strtolower($url);
		$url = strip_tags($url);
		$url = stripslashes($url);
		$url = html_entity_decode($url);
		
		# Remove quotes (can't, etc.)
		$url = str_replace('\'', '', $url);
		
		# Replace non-alpha numeric with hyphens
		$match = '/[^a-z0-9]+/';
		$replace = '-';
		$url = preg_replace($match, $replace, $url);
		
		$url = trim($url, '-');
		
		return $url;
	}
}

if ( ! function_exists('reset_breadcrumb'))
{
	function reset_breadcrumb()
	{
		$CI =& get_instance();
		$sess_cookie_name = $CI->config->item('sess_cookie_name');
		unset($_SESSION[$sess_cookie_name.'_'.'breadcrumb']);
	}
}

if ( ! function_exists('set_breadcrumb'))
{
	function set_breadcrumb($link, $text, $reset=false)
	{
		$CI =& get_instance();
		$sess_cookie_name = $CI->config->item('sess_cookie_name');
		if($reset)
		{
			$ar_breadcrumb = array('0'=>array('link' => $link, 'text' => $text));
			$_SESSION[$sess_cookie_name.'_'.'breadcrumb'] = $ar_breadcrumb;
		}
		else
		{
			if(isset($_SESSION[$sess_cookie_name.'_'.'breadcrumb']))
			{
				$ar_breadcrumb = $_SESSION[$sess_cookie_name.'_'.'breadcrumb'];
				
				//check first
				$j = count($ar_breadcrumb);
				if($j>1)
				{
					if(get_controller_function($ar_breadcrumb[$j-1]['link'])==get_controller_function($link))
					{
						$ar_breadcrumb = $ar_breadcrumb;
					}
					elseif(get_controller_function($ar_breadcrumb[$j-2]['link'])==get_controller_function($link))
					{
						array_pop($ar_breadcrumb);
					}
					else
					{
						$ar_breadcrumb[] = array('link' => $link, 'text' => $text);
					}
				}
				else
				{
					$ar_breadcrumb[] = array('link' => $link, 'text' => $text);
				}
				$_SESSION[$sess_cookie_name.'_'.'breadcrumb'] = $ar_breadcrumb;
			}
		}
	}
}

if ( ! function_exists('get_filter_limit'))
{
	function get_filter_limit($default)
	{
		$array_option = array(2, 8, 10, 20, 50, 100, 200, 500, 'all');
		$array_data = array();
		foreach($array_option as $option){
			$array_data[] = array('value'=>$option, 'text'=>$option);
		}
		$html = html_select('filter_limit', $array_data, $default, '', 'form-control input-sm');
		return str_replace('<select ', '<select onchange="CustomFreshContent(0, true);" ',$html);
	}
}

?>