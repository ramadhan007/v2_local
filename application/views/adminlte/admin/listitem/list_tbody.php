<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->text,array('class'=>'link'));
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
    <td><?php echo $row->short; ?></td>
	<td><?php echo $row->val; ?></td>
    <td><?php echo $row->val_min; ?></td>
    <td><?php echo $row->val_max; ?></td>
    <td align="center"><li class="fa <?php echo $row->icon; ?>"></li></td>
    <td align="center"><span class="label label-<?php echo $row->class; ?>">Sample</span></td>
</tr>
<?php endforeach; ?>