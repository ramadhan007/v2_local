<?php

if ($_SERVER["REQUEST_METHOD"] <> "POST"){
	echo "Calling methode is illegal";
	exit;
}

session_start();

$session_name = "ssnrealussdmon";
$controller = $_REQUEST['controller'];
$process = $_REQUEST['process'];

if(isset($_SESSION[$session_name."_".$controller."_".$process."_progress_reported"])){
	$array = array(
		'type'=>'progress',
		'valuenow'=>$_SESSION[$session_name."_".$controller."_".$process."_progress_valuenow"]
	);
}else{
	$_SESSION[$session_name."_".$controller."_".$process."_progress_reported"] = true;
	session_write_close();
	$array = array(
		'type'=>'init',
		'valuemin'=>$_SESSION[$session_name."_".$controller."_".$process."_progress_valuemin"],
		'valuemax'=>$_SESSION[$session_name."_".$controller."_".$process."_progress_valuemax"]
	);
}
echo json_encode($array);

?>