<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('check_insert_phone_contact'))
{
	function check_insert_phone_contact($phone){
		if($phone){
			if(!get_val("select count(*) from tb_phone_contact where phone = '".$phone."'")){
				run_query("insert into tb_phone_contact (phone) values ('".$phone."')");
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}

if ( ! function_exists('rand_char'))
{
	function rand_char($len)
	{
		$strhasil = '';
		for($i=1;$i<=$len;$i++)
		{
			$pil = rand(1,3);
			if($pil==1)
			{
				$val = chr(rand(48,57));
			}
			
			else if($pil==2)
			{
				$val = chr(rand(65,90));
			}
			else
			{
				$val = chr(rand(97,122));
			}
			$strhasil = $strhasil.$val;
		}
		return $strhasil;
	}
}

if ( ! function_exists('is_admin'))
{
	function is_admin()
	{
		$ar_uri = explode("/",uri_string());
		return $ar_uri[0]=='admin';
	}
}

if ( ! function_exists('fix_no_hp'))
{
	function fix_no_hp($nohp)
	{
		$string_to_add = '';
		$nohp = str_replace(' ','', $nohp);
		$nohp = str_replace('-','', $nohp);
		$nohp = str_replace('.','', $nohp);
		if(substr($nohp,0,1)!='+'){
			$string_to_add .= '+';
			if(substr($nohp,0,2)!='62'){
				$string_to_add .= '62';
			}
		}
		
		if($string_to_add=='+62'){
			if(substr($nohp,0,1)=='0'){
				$nohp = $string_to_add.substr($nohp,1);
			}else{
				$nohp = $string_to_add.$nohp;
			}
		}else{
			$nohp = $string_to_add.$nohp;
		}
		return $nohp;
	}
}

if ( ! function_exists('get_domain'))
{
	function get_domain()
	{
		$server = $_SERVER['HTTP_HOST'];
		return str_replace('www.','',$server);
	}
}

if ( ! function_exists('get_sekolah_id'))
{
	function get_sekolah_id()
	{
		$server = $_SERVER['HTTP_HOST'];
		$server = str_replace('www.','',$server);
		/* if(substr($server, -17)=='.umrohmalindo.com'){	//sekolah
			$sekolah_alias = substr($server, 0, strlen($server)-strlen('.umrohmalindo.com'));
		}else{
			$sekolah_alias = "";
		} */
		return get_val("SELECT id FROM tb_sekolah WHERE CONCAT('|', alias, '|') LIKE '%|$server|%'");
	}
}

if ( ! function_exists('get_array_color'))
{
	function get_array_color()
	{
		return array('#109618', '#3366cc', '#dc3912', '#ff9900', '#990099', '#0099c6', '#dd4477', '#66aa00', '#b82e2e', '#316395', '#994499', '#22aa99', '#aaaa11', '#6633cc', '#e67300', '#8b0707', '#651067', '#329262', '#5574a6', '#3b3eac', '#b77322', '#16d620', '#b91383', '#f4359e', '#9c5935', '#a9c413', '#2a778d', '#668d1c', '#bea413', '#0c5922', '#743411');
	}
}

if ( ! function_exists('get_query_range'))
{
	function get_query_range($array, $field, $get = "text")
	{
		if(count($array)>0){
			$retval = "CASE";
			foreach($array as $el){
				$retval .= " WHEN $field BETWEEN ".$el['value_min']." AND ".$el['value_max']." THEN '".$el[$get]."'";
			}
			$retval .= " END";
			return $retval;
		}
		return '';
	}
}

if ( ! function_exists('get_list_item'))
{
	function get_list_item($list_cat_tag, $order_by='val', $order_dir='asc')
	{
		$array_data = array();
		$rows = get_rows("select a.`val` AS value, a.`val_min` as value_min, a.`val_max` as value_max, a.`text`, a.`short`, a.`icon`, a.`class`
			from tb_list_item as a inner join tb_list_cat as b on a.list_cat_id = b.id
			where b.tag = '$list_cat_tag'
			order by $order_by $order_dir");
		if(count($rows)){
			foreach($rows as $row){
				$el_array = array();
				foreach($row as $key=>$val){
					$el_array[$key] = $val;
				}
				$array_data[] = $el_array;
			}
		}
		return $array_data;
	}
}

if ( ! function_exists('check_insert_list_item'))
{
	function check_insert_list_item($list_cat_tag, $text, $short = '', $val = '', $icon = ''){
		$short = $short ? $short : $text;
		$val = $val ? $val : $text;
		$row = get_row("SELECT 	COUNT(*) AS num, (SELECT id FROM tb_list_cat WHERE tag = '$list_cat_tag') AS cat_id
						FROM 	tb_list_item AS a INNER JOIN tb_list_cat AS b ON a.list_cat_id = b.id
						WHERE 	b.tag = '$list_cat_tag' AND a.`val` = '$val'");
		if(!$row->num){
			$data = array(
					'list_cat_id' => $row->cat_id,
					'text' => $text,
					'short' => $short,
					'val' => $val,
					'icon' => $icon,
				);
			$CI =& get_instance();
			$CI->db->insert('tb_list_item', $data);
			return $CI->db->insert_id();
		}
		else{
			return false;
		}
	}
}

if ( ! function_exists('show_log'))
{
	function show_log($var_log)
	{
		list($usec, $sec) = explode(" ",microtime());
		$string = ((float)$usec + (float)$sec);
		$string2 = explode(".", $string);
				
		echo "Log at ".date("Y-m-d H:i:s", time()).":".$string2[1].":";
		echo "<br />";
		var_dump($var_log);
		echo "<br />";
	}
}

if ( ! function_exists('timeAgoId'))
{
	function timeAgoId($time_ago, $simple=false)
	{
		$ago = $simple ? "" : " yang lalu";
		$time_ago = strtotime($time_ago);
		$cur_time   = time();
		$time_elapsed   = $cur_time - $time_ago;
		$seconds    = $time_elapsed ;
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );
		// Seconds
		if($seconds <= 60){
			return "baru saja";
		}
		//Minutes
		else if($minutes <=60){
			if($minutes==1){
				return "semenit$ago";
			}
			else{
				return "$minutes menit$ago";
			}
		}
		//Hours
		else if($hours <=24){
			if($hours==1){
				return "sejam$ago";
			}else{
				return "$hours jam$ago";
			}
		}
		//Days
		else if($days <= 7){
			if($days==1){
				return "kemarin";
			}else{
				return "$days hari$ago";
			}
		}
		//Weeks
		else if($weeks <= 4.3){
			if($weeks==1){
				return "seminggu$ago";
			}else{
				return "$weeks minggu$ago";
			}
		}
		//Months
		else if($months <=12){
			if($months==1){
				return "sebulan$ago";
			}else{
				return "$months bulan$ago";
			}
		}
		//Years
		else{
			if($years==1){
				return "one setahun$ago";
			}else{
				return "$years tahun$ago";
			}
		}
	}
}

if ( ! function_exists('timeAgo'))
{
	function timeAgo($time_ago)
	{
		$time_ago = strtotime($time_ago);
		$cur_time   = time();
		$time_elapsed   = $cur_time - $time_ago;
		$seconds    = $time_elapsed ;
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );
		// Seconds
		if($seconds <= 60){
			return "just now";
		}
		//Minutes
		else if($minutes <=60){
			if($minutes==1){
				return "one minute ago";
			}
			else{
				return "$minutes minutes ago";
			}
		}
		//Hours
		else if($hours <=24){
			if($hours==1){
				return "an hour ago";
			}else{
				return "$hours hrs ago";
			}
		}
		//Days
		else if($days <= 7){
			if($days==1){
				return "yesterday";
			}else{
				return "$days days ago";
			}
		}
		//Weeks
		else if($weeks <= 4.3){
			if($weeks==1){
				return "a week ago";
			}else{
				return "$weeks weeks ago";
			}
		}
		//Months
		else if($months <=12){
			if($months==1){
				return "a month ago";
			}else{
				return "$months months ago";
			}
		}
		//Years
		else{
			if($years==1){
				return "one year ago";
			}else{
				return "$years years ago";
			}
		}
	}
}

if ( ! function_exists('html_closetags'))
{
	function html_closetags ( $html )
        {
        #put all opened tags into an array
        preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
        $openedtags = $result[1];
        #put all closed tags into an array
        preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
        $closedtags = $result[1];
        $len_opened = count ( $openedtags );
        # all tags are closed
        if( count ( $closedtags ) == $len_opened )
        {
        return $html;
        }
        $openedtags = array_reverse ( $openedtags );
        # close tags
        for( $i = 0; $i < $len_opened; $i++ )
        {
            if ( !in_array ( $openedtags[$i], $closedtags ) )
            {
            $html .= "</" . $openedtags[$i] . ">";
            }
            else
            {
            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
            }
        }
        return $html;
    }
}

if ( ! function_exists('insert_html_id'))
{
	function insert_html_id($html, $identifier, $array_id=array()){
		$array_html = explode($identifier, $html);
		$html = $array_html[0];
		for($i=1;$i<count($array_html);$i++)
		{
			$html .= $array_id[$i-1].$array_html[$i];
		}
		return $html;
	}
}

if ( ! function_exists('joomla_calendar'))
{
	function joomla_calendar()
	{
		return '<link rel="stylesheet" href="'.base_url('assets/joomla_calendar/calendar-jos.css').'" type="text/css"  title="green"  media="all" />
		<script type="text/javascript" src="'.base_url('assets/joomla_calendar/calendar.js').'"></script>
		<script type="text/javascript" src="'.base_url('assets/joomla_calendar/calendar-setup.js').'"></script>
		<script type="text/javascript" src="'.base_url('assets/joomla_calendar/calendar-dn.js').'"></script>';
	}
}

if ( ! function_exists('html_joomla_date'))
{
	function html_joomla_date($element_name, $value='', $jsformat='%d-%m-%Y', $size='25', $maxlength='19', $class='')
	{
		$class = $class ? 'class="'.$class.'"' : '';
		$retval = '<input '.$class.' type="text" name="'.$element_name.'" id="'.$element_name.'" size="'.$size.'" maxlength="'.$maxlength.'" value="'.$value.'" />
					<input type="button" class="button" value="..." onClick="return showCalendar(\''.$element_name.'\',\''.$jsformat.'\');" />';
		return $retval;
	}
}

if ( ! function_exists('object2array'))
{
	function object2array($object)
	{
		$array = array();
		$object_vars = get_object_vars($object);
		foreach($object_vars as $name => $value)
		{
			$array[$name] = $value;
		}
		return $array;
	}
}

if ( ! function_exists('get_tpl_message'))
{
	function get_tpl_message($tag, $completed=FALSE)
	{
		$table = 'tb_tpl_message';
		$CI =& get_instance();
		$query = $CI->db->query("SELECT title, body FROM $table WHERE tag = '$tag';");
		$row = $query->row();
		if($row)
		{
			$retval = $completed ? array('title' => $row->title, 'body' => $row->body) : $row->body;
		}
		else
		{
			$retval = $completed ? array('title' => '', 'body' => '') : '';
		}
		return $retval;
	}
}

if ( ! function_exists('get_main_config'))
{
	function get_main_config($name, $with_date=false)
	{
		$table = 'tb_main_config';
		$CI =& get_instance();
		if($with_date){
			$query = $CI->db->query("SELECT `value`, updated FROM $table WHERE name = '$name';");
		}
		else{
			$query = $CI->db->query("SELECT `value` FROM $table WHERE name = '$name';");
		}
		$row = $query->row();
		if($row)
		{
			if($with_date){
				return $row;
			}
			else{
				return $row->value;
			}
		}
		else
		{
			return '';
		}
	}
}

if( ! function_exists('save_main_config'))
{
	function save_main_config($name, $value)
	{
		$table = 'tb_main_config';
		$CI =& get_instance();
		$query = $CI->db->query("SELECT name FROM $table WHERE name = '$name';");
		$row = $query->row();
		if($row)
		{
			$CI->db->where('name', $name);
			$CI->db->update($table, array('value' => $value, 'updated' =>  date('Y-m-d H:i:s')));
		}
		else
		{
			$CI->db->insert($table, array('name' => $name, 'value' => $value, 'updated' =>  date('Y-m-d H:i:s')));
		}
	}
}

if ( ! function_exists('get_text_by_value'))
{
	function get_text_by_value($array, $value, $text='text')
	{
		$retval = "";
		if(count($array)>0){
			foreach($array as $el){
				if($el['value']==$value)
				{
					return $el[$text];
				}
			}
		}
		return '';
	}
}

if ( ! function_exists('get_text_by_range'))
{
	function get_text_by_range($array, $value, $text='text')
	{
		$retval = "";
		if(count($array)>0){
			foreach($array as $el){
				if($value>=$el['value_min'] && $value<=$el['value_max'])
				{
					return $el[$text];
				}
			}
		}
		return '';
	}
}

if ( ! function_exists('html_hidden'))
{
	function html_hidden($element_name, $value, $show_text = '')
	{
		$retval = $show_text.'<input name="'.$element_name.'" type="hidden" value="'.$value.'"/>';
		return $retval;
	}
}

if ( ! function_exists('html_check'))
{
	function html_check($element_name, $value, $checked=FALSE, $show_text = '', $class = '', $id = '')
	{
		$class = $class ? 'class="'.$class.'"' : '';
		$checked = $checked ? ' checked="checked"' : '';
		$retval = '<input name="'.$element_name.'" id="'.$id.'" type="checkbox" value="'.$value.'" '.$checked.' '.$class.' />'.$show_text;
		return $retval;
	}
}

if ( ! function_exists('html_select'))
{
	function html_select($element_name, $array_data, $value, $title = '', $class = 'form-control', $size = '', $width='')
	{
		$class = $class ? 'class="'.$class.'"' : '';
		$size = $size ? 'size="'.$size.'"' : '';
		$width = $width ? 'style="width:'.$width.'px"' : '';
		$retval = '<select name="'.$element_name.'" id="'.$element_name.'" '.$class.' '.$size.' '.$width.'>';
		$retval .= $title ? '<option value="" '.set_select($element_name, '', (''==$value ? TRUE : FALSE)).' >'.$title.'</option>' : '';
		for($i=0;$i<count($array_data);$i++)
		{
			$retval .= '<option value="'.$array_data[$i]['value'].'" '.set_select($element_name, $array_data[$i]['value'], ($array_data[$i]['value']==$value ? TRUE : FALSE)).' >'.$array_data[$i]['text'].'</option>';
		}
		$retval .= '</select>';
		return $retval;
	}
}

if ( ! function_exists('html_select_multiple'))
{
	function html_select_multiple($element_name, $array_data, $list_value, $title = '', $class = 'form-control', $size = '', $width='')
	{
		$class = $class ? 'class="'.$class.'"' : '';
		$size = $size ? 'size="'.$size.'"' : '';
		$width = $width ? 'style="width:'.$width.'px"' : '';
		$retval = '<select multiple="multiple" name="'.$element_name.'[]" id="'.$element_name.'" '.$class.' '.$size.' '.$width.'>';
		$retval .= $title ? '<option value="">'.$title.'</option>' : '';
		$array_value = explode(',',$list_value);
		for($i=0;$i<count($array_value);$i++){
			$array_value[$i] = trim($array_value[$i]);
		}
		for($i=0;$i<count($array_data);$i++)
		{
			$retval .= '<option value="'.$array_data[$i]['value'].'" '.set_select($element_name, $array_data[$i]['value'], (in_array($array_data[$i]['value'],$array_value) ? TRUE : FALSE)).' >'.$array_data[$i]['text'].'</option>';
		}
		$retval .= '</select>';
		return $retval;
	}
}

if ( ! function_exists('html_select2_multiple'))
{
	function html_select2_multiple($element_name, $array_data, $list_value, $title = '', $class = 'form-control', $size = '', $width='')
	{
		$class = $class ? 'class="'.$class.'"' : '';
		$size = $size ? 'size="'.$size.'"' : '';
		$width = $width ? 'style="width:'.$width.'px"' : '';
		$retval = '<select multiple="multiple" name="'.$element_name.'[]" id="'.$element_name.'" '.$class.' '.$size.' '.$width.'>';
		$retval .= $title ? '<option value="">'.$title.'</option>' : '';
		
		$array_data1 = array();
		
		$array_value = explode(',',$list_value);
		for($i=0;$i<count($array_value);$i++){
			$array_value[$i] = trim($array_value[$i]);
			$text = get_text_by_value($array_data, $array_value[$i]);
			if($text) $array_data1[] = array('value' => $text, 'text' => $text, 'selected' => true);
		}
		
		foreach($array_data as $el_data){
			$found = false;
			foreach($array_data1 as $el_data1){
				if($el_data['value']==$el_data1['value']) $found = true;
			}
			if(!$found) $array_data1[] = array('value' => $el_data['value'], 'text' => $el_data['text'], 'selected' => false);
		}
		
		for($i=0;$i<count($array_data1);$i++)
		{
			$retval .= '<option value="'.$array_data1[$i]['value'].'" '.set_select($element_name, $array_data1[$i]['value'], $array_data1[$i]['selected']).' >'.$array_data1[$i]['text'].'</option>';
		}
		$retval .= '</select>';
		return $retval;
	}
}

if ( ! function_exists('html_radio'))
{
	function html_radio($element_name, $array_data, $value = '', $title = '', $break = FALSE, $class = '')
	{
		$class = $class ? 'class="'.$class.'"' : '';
		$retval = "";
		for($i=0;$i<count($array_data);$i++)
		{
			$retval .= '<label'.($break ? '' : ' class="radio-inline"').'><input type="radio" name="'.$element_name.'" id="'.$element_name.'_'.$i.'" value="'.$array_data[$i]['value'].'" '.set_radio($element_name, $array_data[$i]['value'], ($array_data[$i]['value']==$value ? TRUE : FALSE)).' '.$class.'>'.$array_data[$i]['text'].'</label>';
		}
		return $retval;
	}
}

if ( ! function_exists('html_date'))
{
	function html_date($element_name, $value='', $calendar_image='', $class='', $size='10', $maxlength='10')
	{
		$class = $class ? 'class="'.$class.'"' : '';
		$calendar_image = $calendar_image ? $calendar_image : base_url('images/calendar.png');
		$retval = '<input type="text" name="'.$element_name.'" '.$class.' value="'.$value.'" size="'.$size.'" maxlength="'.$maxlength.'" style="text-align:center" />'.
			'<a href="javascript:void(0);" onclick="displayDatePicker(\''.$element_name.'\');"><img src="'.$calendar_image.'" alt="calendar" border="0"></a>';
		return $retval;
	}
}

if ( ! function_exists('html_ckeditor'))
{
	function html_ckeditor($element_name, $value='', $class='form-control', $width = 'auto', $height = 'auto')
	{
		//load ckeditor helper
		$CI =& get_instance();
		$CI->load->helper('ckeditor');
		
		//Ckeditor's configuration
		$ckeditor = array(
 
			//ID of the textarea that will be replaced
			'id' 	=> 	$element_name,
			'path'	=>	'assets/ckeditor',
 
			//Optional values
			'config' => array(
				'toolbar' 	=> 	($CI->uri->segment(1)=='admin' ? 'Full' : 'Basic'), 	//Using the Full toolbar
				'width' 	=> 	$width,	//Setting a custom width
				'height' 	=> 	$height,	//Setting a custom height
				'filebrowserBrowseUrl' => base_url('filemanager/dialog.php?type=2&editor=ckeditor'),
				'filebrowserImageBrowseUrl' => base_url('filemanager/dialog.php?type=1&editor=ckeditor'),
				'filebrowserUploadUrl' => base_url('filemanager/dialog.php?type=2&editor=ckeditor'),
				'filebrowserImageUploadUrl' => base_url('filemanager/dialog.php?type=2&editor=ckeditor'),
			),
		);
		
		$class = $class ? 'class="'.$class.'"' : '';
		
		$retval = '<textarea name="'.$element_name.'" id="'.$element_name.'" '.$class.' >'.$value.'</textarea>'.
			display_ckeditor($ckeditor);
		return $retval;
	}
}

if ( ! function_exists('html_yesno_radio'))
{
	function html_yesno_radio($element_name, $value = '', $text_yes = "Ya", $text_no = "Tidak")
	{
		$array_data = array(
			'0' => array('value' => '1', 'text' => $text_yes),
			'1' => array('value' => '0', 'text' => $text_no),
			);
		return html_radio($element_name, $array_data, $value, '', FALSE);
	}
}

if ( ! function_exists('seno_date_add'))
{
	function seno_date_add($givendate,$yr=0,$mth=0,$day=0,$hour=0,$min=0,$sec=0)
	{
		$cd = strtotime($givendate);
		$newdate = date('Y-m-d H:i:s', mktime(date('H',$cd)+$hour,
		date('i',$cd)+$min, date('s',$cd)+$sec, date('m',$cd)+$mth,
		date('d',$cd)+$day, date('Y',$cd)+$yr));
		return $newdate;
	}
}

if ( ! function_exists('date_mysql2dmy'))
{
	function date_mysql2dmy($date = '', $format=0)
	{
		$date = $date ? $date : "0000-00-00";
		switch($format)
		{
			case 0:
				$format = 'd-m-Y';
				break;
			case 1:
				$format = 'j F Y';
				break;
		}
		return $date!="0000-00-00" ? date($format,strtotime($date)) : "";
	}
}

if ( ! function_exists('date_dmy2mysql'))
{
	function date_dmy2mysql($date = "")
	{
		return $date ? date('Y-m-d', strtotime($date)) : "0000-00-00";
	}
}

if ( ! function_exists('date_mysql2dmyhns'))
{
	function date_mysql2dmyhns($date = '', $format=0)
	{
		$date = $date ? $date : "0000-00-00 00:00:00";
		switch($format)
		{
			case 0:
				$format = 'd-m-Y H:i:s';
				break;
			case 1:
				$format = 'j F Y H:i:s';
				break;
		}
		return $date!="0000-00-00 00:00:00" ? date($format,strtotime($date)) : "";
	}
}

if ( ! function_exists('date_dmyhns2mysql'))
{
	function date_dmyhns2mysql($date = "")
	{
		return $date ? date('Y-m-d H:i:s', strtotime($date)) : "0000-00-00 00:00:00";
	}
}

if(!function_exists('make_dir'))
{
	function make_dir($dirname)
	{
		if(!file_exists($dirname))
		{
			if(mkdir($dirname))
			{
				$ret = true;
			}
			else
			{
				$ret = false;
			}
		}
		else
		{
			$ret = true;
		}
		return $ret;
	}
}

if(!function_exists('check_date'))
{
	function check_date($date, $allowempty=TRUE)
	{
		if($date)
		{
			if(!preg_match("/^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$/", $date))
			{
				return false;
			}
			else
			{
				$ardate = explode('-',$date);
				if(checkdate(intval($ardate[1]),intval($ardate[0]),intval($ardate[2])))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			if($allowempty){
				return true;
			}
			else{
				return false;
			}
		}
	}
}
?>