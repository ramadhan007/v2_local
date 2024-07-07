<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	if(!$this->session->userdata($this->controller.'_name')){
		$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
		$title = strip_tags($row->title);
	}else{
		$link = "";
		$title = anchor($this->controller.'/edit/'.$row->id,strip_tags($row->title),array('class'=>'link'));
	}
	
	$text_published = '<i class="fa fa-check fa-lg"></i> '.($row->published=='1' ? 'YES' : 'NO');
	$class_published = ($row->published=='1' ? 'success' : 'danger');
	$published = "<span class=\"label label-$class_published\">$text_published</span>";
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
    <?php if(!$this->session->userdata($this->controller.'_name')): ?>
	<td><?php echo $link; ?></td>
    <?php endif; ?>
	<td><?php echo $title; ?></td>
	<td><?php echo str_replace('{[base_url]}','<span class="label label-info">BASE URL</span>/',$row->path); ?></td>
	<td><?php echo $published; ?></td>
</tr>
<?php endforeach; ?>