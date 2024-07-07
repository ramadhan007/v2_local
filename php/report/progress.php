<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("dbconnecti.php");

$params = array(
	'host' => 'localhost',
	'user' => 'senosoft',
	'db' => 'senosoft_realussdmon_report',
	'pass' => '$seno4r15%',
);

$db = new DBConnecti($params);

$sql = "select * from tb_report_date";
$result = $db->query($sql);
if(!$result['status']) exit(json_encode($result));

$ids = json_decode($result['rows'][0]['ids']);

$total = 0;
$processed = 0;

foreach($ids as $key=>$val){
	$total = $total + ($val->id_max - $val->id_min) + 1;
	$processed = $processed + ($val->id_cur - $val->id_min) + 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Report Progress</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css";?>">
  <script src="<?="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js";?>"></script>
  <script src="<?="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js";?>"></script>
</head>
<body>

<div class="container">
  <h3>RealUSSDMon Reporting Progress</h3>
  <p>Date: <?=$result['rows'][0]['date_start'];?> to <?=$result['rows'][0]['date_end'];?></p>
  <p>Status: <span id="span_status">----------</span>&nbsp;<img id="img_loading" src="../../images/loading1.gif" style="display:none" /></p>
  <p>Elapsed: <span id="span_elapsed">--:--:--</span></p>
  <p>ETA: <span id="span_eta">--:--:--</span></p>
  <div class="progress">
    <div id="div_progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
      0%
    </div>
  </div>
  
  <button id="btn_stop" type="button" class="btn btn-danger">Force Stop</button>
  
</div>

<script type="text/javascript">

var myTimer;

function showLoading(boolShow){
	if(boolShow){
		$('#img_loading').css('display','inline-block');
	}else{
		$('#img_loading').css('display','none');
	}
}

$(document).ready(function(e) {
	showLoading(true);
    $.get('getprogress.php',{},
	function(data){
		// console.log(JSON.stringify(data));
		if(data.status){
			$('#div_progress').attr('aria-valuenow',data.processed);
			$('#div_progress').attr('aria-valuemax',data.total)
			var percent = String((data.processed*100/data.total).toFixed(2));
			$('#div_progress').css('width',percent + "%");
			$('#div_progress').html(percent + "%");
			$('#span_status').html(data.progress_status);
			$('#span_elapsed').html(msec2time(data.processed_msec));
			$('#span_eta').html(msec2time(data.remained_msec));
			if(!data.completed){
				myTimer = setInterval(refreshProgress, 10000);
			}else{
				$('#btn_stop').attr('disabled','disabled');
			}
			showLoading(false);
		}
	},'json');
	
	$('#btn_stop').click(function(e) {
		if(confirm("Are sure willing to stop the running task?")){
			$.get('forcestop.php',{},
			function(data){
				console.log(JSON.stringify(data));
				if(data.status){
					alert("Reporting task is successfuly stopped!");
				}else{
					alert("Failed to stop reporting task");
				}
			},'json');
		}
    });
});

function refreshProgress(){
	if(document.visibilityState=='visible'){
		showLoading(true);
		$.get('getprogress.php',{},
		function(data){
			if(data.status){
				$('#div_progress').attr('aria-valuenow',data.processed);
				$('#div_progress').attr('aria-valuemax',data.total)
				var percent = String((data.processed*100/data.total).toFixed(2));
				$('#div_progress').css('width',percent + "%");
				$('#div_progress').html(percent + "%");
				$('#span_status').html(data.progress_status);
				$('#span_elapsed').html(msec2time(data.processed_msec));
				$('#span_eta').html(msec2time(data.remained_msec));
				if(data.completed){
					clearInterval(myTimer);
					$('#btn_stop').attr('disabled','disabled');
				}
			}
			showLoading(false);
		},'json');
	}
}

function msec2time(msec){
	var dd = Math.floor(msec / 1000 / 24 / 60 / 60);
	msec -= dd * 1000 * 24 * 60 * 60;
	var hh = Math.floor(msec / 1000 / 60 / 60);
	msec -= hh * 1000 * 60 * 60;
	var mm = Math.floor(msec / 1000 / 60);
	msec -= mm * 1000 * 60;
	var ss = Math.floor(msec / 1000);
	msec -= ss * 1000;
	
	return (dd>0 ? dd + 'd ' : '') + pad(hh,2) + ":" + pad(mm,2) + ":" + pad(ss,2);
}

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

</script>

</body>
</html>
