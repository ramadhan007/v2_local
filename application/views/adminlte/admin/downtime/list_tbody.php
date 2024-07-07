<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,substr(date_mysql2dmyhns($row->start_datetime),0,16),array('class'=>'link'));
?>
<tr>
	<td><?=$cb; ?></td>
	<td><?=++$i; ?></td>
	<td><?=$link; ?></td>
    <td><?=substr(date_mysql2dmyhns($row->end_datetime),0,16)?></td>
    <td><?=$row->remarks?></td>
    <td><a href="#" class="btn btn-xs btn-<?=$row->published_class;?> btn_published" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->published_icon;?>"></i>&nbsp;<?=$row->published_text;?></a></td>
</tr>
<?php endforeach; ?>