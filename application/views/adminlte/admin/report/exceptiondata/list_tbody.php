<?php

$rows_setting = get_rows("select name, value from tb_setting where name like 'screenshot%'");
$setting = array();

foreach($rows_setting as $row_setting){
	$setting[$row_setting->name] = $row_setting->value;
}

$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
?>
<tr>
	<td><?php echo ++$i; ?></td>
	<td><?=$row->location_name;?></td>
    <td><?=$row->operator_name;?></td>
    <td><?=$row->application_name;?></td>
    <td><?=$row->page_name;?></td>
    <td><?=$row->monitor_date;?></td>
    <td><?=$row->time_start;?></td>
    <td><?=$row->time_end;?></td>
    <td><?=$row->error_duration;?></td>
    <td><?=$row->error_type;?></td>
    <td><?=$row->message;?></td>
    <td>
    	<?php
		if(file_exists($setting["screenshot_path_rel"]."/".$row->id.".".$setting["screenshot_ext"])):
		?>
		<a rel="popover" href="<?=base_url($setting["screenshot_path_rel"]."/".$row->id.".".$setting["screenshot_ext"]);?>" data-img="<?=base_url($setting["screenshot_path_rel"]."/".$row->id.".".$setting["screenshot_ext"]);?>" target="_blank"><i class="fa fa-image"></i></a>
		<?php
		endif;
		?>
    </td>
</tr>
<?php endforeach; ?>