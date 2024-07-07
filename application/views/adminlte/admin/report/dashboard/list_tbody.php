<?php

$array_data = array();
$array_data['ux_index'] = get_list_item("user_experience_index");
$array_data['it_availability'] = get_list_item("it_availability");
$array_data['eu_availability'] = get_list_item("end_user_availability");

$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$ar_class = array();
	$ar_class['ux_index'] = get_text_by_range($array_data['ux_index'], $row->ux_index, 'class');
	$ar_class['it_availability'] = get_text_by_range($array_data['it_availability'], $row->it_availability, 'class');
	$ar_class['eu_availability'] = get_text_by_range($array_data['eu_availability'], $row->eu_availability, 'class');
?>
<tr>
	<td><input name="rdo_selected" type="radio" data-id="<?=$row->journey_id;?>" value="" onchange="loadDetail(this)" /></td>
	<td><?=$row->journey_name;?>&nbsp;<img id="journey_loading_<?=$row->journey_id;?>" src="<?=base_url('images/loading.gif');?>" height="10" style="vertical-align:baseline; display:none" /></td>
    <td><?=$row->num_page;?></td>
    <td><?=$row->total_journey;?></td>
    <td><?=$row->total_success;?></td>
    <td><?=$row->total_error;?></td>
    <td><?=$row->response_time_min;?></td>
    <td><?=$row->response_time_median;?></td>
    <td><?=$row->response_time_avg;?></td>
    <td><?=$row->nineth_percentile;?></td>
    <td><?=$row->response_time_max;?></td>
    <td><span class="signal_level label label-<?=$ar_class['it_availability'];?>"><?=$row->it_availability;?>%</span></td>
    <td><span class="signal_level label label-<?=$ar_class['eu_availability'];?>"><?=$row->eu_availability;?>%</span></td>
    <!-- <td><?=$row->nvt_signal_level;?></td>
    <td><?=$row->nvt_response_time;?></td>
    <td><?=$row->nvt_access;?></td> -->
</tr>
<?php endforeach; ?>