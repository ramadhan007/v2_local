<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
	
	$picture = ($row->picture ? '<a href="'.fix_base_url($row->picture).'" target="_blank"><i class="fa fa-image"></i></a>' : '&nbsp;');
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
    <td><?php echo $row->email; ?></td>
    <td><?php echo $row->phone; ?></td>
    <td><?php echo $picture; ?></td>
</tr>
<?php endforeach; ?>