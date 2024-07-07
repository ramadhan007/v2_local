<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link','id'=>'td_name_'.$row->id));
	
	$list = '<a id="span_list_count_'.$row->id.'" href="javascript:openDeviceModal('.$row->id.');" class="btn btn-info btn-xs" role="button" style="width:50px;">'.$row->list.'</a>';
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
    <td><?php echo $row->id; ?></td>
    <td><?php echo $link; ?></td>
    <td><?php echo $row->location_name; ?></td>
    <td><?php echo $row->operator_name; ?></td>
    <td><?php echo $row->application_text; ?></td>
    <td align="center"><?php echo $list; ?></td>
    <td align="center"><?php echo $row->log_update; ?></td>
    <td><a href="#" class="btn btn-xs btn-<?=$row->published_class;?> btn_published" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->published_icon;?>"></i>&nbsp;<?=$row->published_text;?></a></td>
    <td align="center"> <img id="img_loading_<?=$row->id;?>" src="<?=base_url('images/loading1.gif');?>" style="visibility:hidden" /> </td>
</tr>
<?php endforeach; ?>