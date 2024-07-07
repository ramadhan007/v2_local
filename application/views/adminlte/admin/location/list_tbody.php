<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
	
	$list = '<a id="span_list_count_'.$row->id.'" href="javascript:openDeviceModal('.$row->id.');" class="btn btn-info btn-xs" role="button" style="width:50px;">'.$row->list.'</a>';
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
    <!-- <td align="center"><?php echo $list; ?></td> -->
    <td><a href="#" class="btn btn-xs btn-<?=$row->published_class;?> btn_published" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->published_icon;?>"></i>&nbsp;<?=$row->published_text;?></a></td>
</tr>
<?php endforeach; ?>