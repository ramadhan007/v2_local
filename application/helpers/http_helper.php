<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('http_get_ip_detail'))
{
	function http_get_ip_detail($ip) {
		return http_load("http://ip-api.com/json/$ip");
	}
}

if ( ! function_exists('http_get_ip'))
{
	function http_get_ip() {
      foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
         if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
               if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                  return $ip;
               }
            }
         }
      }
   }
}

if ( ! function_exists('http_load'))
{
	function http_load($url, $maxlength=0, $array_post=array())
	{
		$ch = curl_init();
		$timeout = 0;
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if($array_post)
		{
			$fields_string = '';
			foreach($array_post as $key=>$value)
			{
				$fields_string .= $key.'='.$value.'&';
			}
			//echo $fields_string; exit;
			rtrim($fields_string, '&');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		if($maxlength>0)
		{
			curl_setopt($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD, $maxlength);
		}
		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}
}

if ( ! function_exists('http_get_after'))
{
	function http_get_after($strtext, $strcari)
	{
		$i = strpos($strtext,$strcari);
		$strtmp = substr($strtext,$i+strlen($strcari));
		return trim($strtmp);
	}
}

if ( ! function_exists('http_get_before'))
{
	function http_get_before($strtext, $strcari)
	{
		$i = strpos($strtext,$strcari);
		$strtmp = substr($strtext,0,$i);
		return trim($strtmp);
	}
}

if ( ! function_exists('http_get_between'))
{
	function http_get_between($strtext, $strawal, $strakhir)
	{
		return http_get_before(http_get_after($strtext, $strawal), $strakhir);
	}
}

function http_get_between_tags($string, $tagname) {
    $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
    preg_match_all($pattern, $string, $matches);
    return $matches;
}

if ( ! function_exists('http_fix_html'))
{
	function http_fix_html($strtext)
	{
		$strtext = str_replace(chr(13).chr(10),' ',$strtext);
		$strtext = str_replace(chr(9),' ',$strtext);
		$strtext = str_replace(chr(10),' ',$strtext);
		while(strpos($strtext,'  ')!==false){
			$strtext = str_replace('  ',' ',$strtext);
		}
		$strtext = str_replace('> <','><',$strtext);
		return $strtext;
	}
}

?>