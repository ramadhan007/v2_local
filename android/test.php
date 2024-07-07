<?php

$str = "SELECT fn_insert_monitor_journey_nvt('21529686','3G','20378342','-81','1.045','*123#','1','Sisa limit 50000
1 PRIO Film Disc 30%
2 Info Tagihan
3 Upgrade Unlimited
4 Booster
5 Ganti Plan
6 Int'l
7 Fitur
8 PRIO Info
9 Cara Bayar
','2019-09-04 11:58:41','4') as id";

// $str = "select 'Connection Ok'";

$str = "SELECT fn_insert_monitor_journey( '54', '4', '-5.14938', '119.478', '2020-06-06 05:52:25') as id;";

// $str = "SELECT fn_insert_monitor_journey_detail('21465732','51','4G','135726854','-85.0','1.48','','0','success','2020-06-06 00:10:28','1') AS id";

$str = "SELECT fn_insert_monitor_journey_nvt('21467778','4G','8260865','-108.0','3','','0','nvt','2020-06-06 01:40:05','1') AS id";

$str_id = "WOW";

if(strpos($str,"fn_insert_monitor_journey(")!==FALSE){
	$str = str_replace("( '", "('", $str);
	$ar = explode("'", $str);
	$str_id = $ar[1];
}elseif(strpos($str,"fn_insert_monitor_journey_detail(")!==FALSE || strpos($str,"fn_insert_monitor_journey_nvt(")!==FALSE){
	$ar = explode("'", $str);
	$monitor_journey_id = $ar[1];
	$str_id = $monitor_journey_id;
	// get device_id;
}elseif(strpos($str,"tb_device")!==FALSE){
	$str = strtolower($str);
	$str_id = http_get_after($str, "where id = ");
	$str_id = str_replace("'","",$str_id);
	$str_id = str_replace(";","",$str_id);
	$str_id;
}

exit();

$str = str_replace("( '", "('", $str);

if(strpos($str,"fn_insert_monitor_journey_nvt(")!==FALSE){
	$ar = explode("'", $str);
	echo $ar[1]; exit();
}

echo $str;

echo "\n\nBecome:\n\n";

echo fix_sql($str);

function fix_sql($str){
	if(strpos($str, "('")!==FALSE && strpos($str,"')")!==FALSE){
		$str = str_replace("', '", "',", $str);
		$ar = explode("','", $str);
		
		for($i=0;$i<count($ar); $i++){
			if($i==0){
				if(strpos($ar[$i],"('")!==FALSE){
					$str_intro = http_get_before($ar[$i], "('");
					$str_temp = http_get_after($ar[$i], "('");
					$str_temp = str_replace("'", "''", $str_temp);
					$str_temp = $str_intro."('".$str_temp;
				}else{
					$str_temp = str_replace("'", "''", $ar[$i]);
				}
				$ar[$i] = $str_temp;
			}
			elseif($i==count($ar)-1){
				if(strpos($ar[$i],"')")!==FALSE){
					$str_intro = http_get_after($ar[$i], "')");
					$str_temp = http_get_before($ar[$i], "')");
					$str_temp = str_replace("'", "''", $str_temp);
					$str_temp = $str_temp."')".$str_intro;
				}else{
					$str_temp = str_replace("'", "''", $ar[$i]);
				}
				$ar[$i] = $str_temp;
			}
			else{
				$ar[$i] = str_replace("'", "''", $ar[$i]);
			}
		}
		
		return implode("','", $ar);
	}else{
		return $str;
	}
}

function http_get_after($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,$i+strlen($strcari));
	return $strtmp;
}

function http_get_before($strtext, $strcari)
{
	$i = strpos($strtext,$strcari);
	$strtmp = substr($strtext,0,$i);
	return $strtmp;
}

function http_get_between($strtext, $strawal, $strakhir)
{
	return http_get_before(http_get_after($strtext, $strawal), $strakhir);
}


?>