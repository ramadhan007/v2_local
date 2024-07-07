<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$text_published = '<i class="fa fa-check fa-lg"></i> '.($row->published=='1' ? 'YES' : 'NO');
	$class_published = ($row->published=='1' ? 'success' : 'danger');
	$published = "<span class=\"label label-$class_published\">$text_published</span>";
	
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->title,array('class'=>'link'));
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
	<td><?php echo $row->alias; ?></td>
	<td align="center"><li class="fa <?php echo $row->icon; ?>"></li></td>
	<td><?php echo $row->link; ?></td>
    <td><?php echo $row->usertype1; ?></td>
	<td><?php echo $row->ordering; ?></td>
	<td><?php echo $published; ?></td>
</tr>
<?php endforeach; ?>