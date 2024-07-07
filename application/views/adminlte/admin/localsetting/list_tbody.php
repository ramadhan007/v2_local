<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
?>
<tr>
	<td><?=$cb; ?></td>
	<td><?=++$i; ?></td>
	<td><?=$link; ?></td>
    <td><?=$row->value?></td>
    <td><?=$row->unit?></td>
</tr>
<?php endforeach; ?>