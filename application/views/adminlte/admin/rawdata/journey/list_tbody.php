<?php

$array_data = array();
$array_data['signal_level'] = get_list_item("signal_level");
$array_data['response_time'] = get_list_item("response_time");
$array_data['response_time_nvt'] = get_list_item("response_time_nvt");

$screenshot_ext = get_val("select value from tb_setting where name = 'screenshot_ext'");

$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j.'" name="cid[]" value="'.$row->id.'" data-index="'.$j++.'" onclick="isChecked(this.checked);" type="checkbox">';
	
	$ar_class = array();
	$ar_class['signal_level'] = get_text_by_range($array_data['signal_level'], $row->signal_level, 'class');
	$ar_class['response_time'] = get_text_by_range($array_data['response_time'], ($row->response_time/$row->detail_count), 'class');
	
	$error_class = $row->status ? "danger" : "success";
	// $error_icon = $row->status ? ($row->status==2 ? "file-o" : ($row->status==3 ? "key" : "times")) : "check";
	$error_icon = $row->status ? "times" : "check";
	$error_type = $row->status ? ($row->status==2 ? "OTP Fail" : ($row->status==3 ? "APM Error" : "UI Fail")) : "Success";
	
	if($row->nvt_count){
		$ar_class['nvt_signal_level'] = get_text_by_range($array_data['signal_level'], $row->nvt_signal_level, 'class');
		$ar_class['nvt_response_time'] = get_text_by_range($array_data['response_time_nvt'], ($row->nvt_response_time), 'class');
	}else{
		$ar_class['nvt_signal_level'] = "";
		$ar_class['nvt_response_time'] = "";
	}
?>
<tr>
	<td><?php echo ++$i; ?></td>
	<td><?=$row->location_name;?></td>
    <td><?=$row->operator_name;?></td>
    <td><?=$row->journey_name;?></td>
    <td><?=$row->cellid;?></td>
    <td><?=$row->location_lat;?></td>
    <td><?=$row->location_lng;?></td>
    <td><?=$row->network_type;?></td>
    <td><span class="label label-<?=$ar_class['signal_level'];?>"><?=$row->signal_level;?></span></td>
    <td><?=$row->signal_quality<99 ? $row->signal_quality : '';?></td>
    <!-- <td><?=$row->ber<99 ? $row->ber : '';?></td> -->
    <td><span class="label label-<?=$ar_class['response_time'];?>"><?=$row->response_time;?></span></td>
    <td><?=$row->latency;?></td>
    <td><?=$row->packet_loss;?></td>
    <td><a class="status_tooltip" href="#" data-toggle="tooltip" title="<?=$row->message;?>">
		<span class="label label-<?=$error_class;?>"><?=$error_type;?></span>
	</a></td>
    <td>
    	<?php
        if($row->status):
			if(file_exists("userfiles/screenshot/monitor_journey_detail/".$row->screenshot_id.".".$screenshot_ext)):
			?>
            <a rel="popover" href="<?=base_url("userfiles/screenshot/monitor_journey_detail/".$row->screenshot_id.".".$screenshot_ext);?>" data-img="<?=base_url("userfiles/screenshot/monitor_journey_detail/".$row->screenshot_id.".".$screenshot_ext);?>" target="_blank"><i class="fa fa-image"></i></a>
            <?php
			endif;
		endif;
		?>
    </td>
    <td><?=$row->error_journey_detail_name;?></td>
    <td><span class="label label-<?=$ar_class['nvt_signal_level'];?>"><?=$row->nvt_signal_level;?></span></td>
    <td><span class="label label-<?=$ar_class['nvt_response_time'];?>"><?=$row->nvt_response_time;?></span></td>
    <td><?=$row->nvt_latency;?></td>
    <td><?=str_replace(' ', '&nbsp;', $row->monitor_datetime);?></td>
</tr>
<?php endforeach; ?>
