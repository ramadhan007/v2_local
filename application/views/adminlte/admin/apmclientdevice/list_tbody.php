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
    <td><?php echo $row->device_id; ?></td>
    <td><?php echo $link; ?></td>
    <td><?php echo $row->location_name; ?></td>
    <td><?php echo $row->operator_name; ?></td>
    <td><?php echo $row->appium_port; ?></td>
    <td><a href="#" class="btn btn-xs btn-<?=$row->published_class;?> btn_published" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->published_icon;?>"></i>&nbsp;<?=$row->published_text;?></a></td>
</tr>
<?php endforeach; ?>