<?php 

$package_name = $_GET['packagename'];
$url = "https://play.google.com/store/apps/details?id=".$package_name;
$content = http_load($url);

echo trim(http_get_between($content, '<div class="content" itemprop="softwareVersion">', '</div>'));

function get_ip() {
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

function http_load($url, $maxlength=0, $array_post=array())
{
	$ch = curl_init();
	$timeout = 0;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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

function http_get_after($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,$i+strlen($strcari));
	return trim($strtmp);
}

function http_get_before($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,0,$i);
	return trim($strtmp);
}

function http_get_between($strtext, $strawal, $strakhir)
{
	preg_match('@'.$strawal.'(.*?)'.$strakhir.'@si',$strtext,$data);
	if(count($data)>1){
		return $data[1];
	}else{
		return '';
	}
}

?>