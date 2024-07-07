<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->phone_number,array('class'=>'link'));
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
    <td><?php echo $row->id; ?></td>
    <td><?php echo $link; ?></td>
    <td><?php echo $row->device_name; ?></td>
    <td><?php echo $row->udid; ?></td>
    <td><?php echo $row->platform_name; ?></td>
    <td><?php echo $row->platform_version; ?></td>
    <td><?php echo $row->application_text; ?></td>
    <td><?php echo $row->location_name; ?></td>
    <td><?php echo $row->operator_name; ?></td>
    <td><a href="#" class="btn btn-xs btn-<?=$row->status_class;?> btn_status" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->status_icon;?>"></i>&nbsp;<?=$row->status_text;?></a></td>
    <td>
    	<?php
        if(!$row->status_final){
			echo $row->status_age;
		}
		?>
    </td>
    <td><?php echo $row->app_version; ?></td>
    <td><a href="#" class="btn btn-xs btn-<?=$row->published_class;?> btn_published" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->published_icon;?>"></i>&nbsp;<?=$row->published_text;?></a></td>
    <td></td>
</tr>
<?php endforeach; ?>