<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
	
	// $list = '<a href="'.site_url('admin/listitem/index/'.$row->id).'" class="btn btn-info btn-xs" role="button" style="width:50px;">'.$row->list.'</a>';
	
	$list = '<a id="span_list_count_'.$row->id.'" href="javascript:openListItemModal('.$row->id.');" class="btn btn-info btn-xs" role="button">Edit List ('.$row->list.')</a>';
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
    <td><?php echo $row->tag; ?></td>
    <td align="center"><?php echo ucfirst($row->type); ?></td>
    <td align="center"><?php echo $list; ?></td>
</tr>
<?php endforeach; ?>