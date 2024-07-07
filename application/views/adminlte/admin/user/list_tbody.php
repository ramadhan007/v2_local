<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$dis = ($this->user['id']==$row->id) ? 'disabled="disabled"' : "";
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" '.$dis.' onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
	
	$usertype = str_replace(' ','&nbsp;',$row->usertype1);
	$userrole = str_replace(' ','&nbsp;',$row->userrole1);
	
	//status
	$array_data = array(
			'0' => array('value' => '0', 'text' => 'Baru', 'class' => 'warning',
				'icon' => 'minus-square'),
			'1' => array('value' => '1', 'text' => 'Aktif', 'class' => 'success',
				'icon' => 'check'),
			'2' => array('value' => '2', 'text' => 'Diblok', 'class' => 'danger',
				'icon' => 'cross'),
		);
	
	$text_status = '<i class="fa fa-'.get_text_by_value($array_data, $row->status, 'icon').' fa-lg"></i> '.get_text_by_value($array_data, $row->status);
	$class_status = get_text_by_value($array_data, $row->status, 'class');
	$status = "<span class=\"label label-$class_status\">$text_status</span>";
	
	$last_login = str_replace(' ','&nbsp;',date_mysql2dmyhns($row->lastvisitDate));
?>
<tr>
	<td align="center"><?php echo $cb; ?></td>
	<td align="center"><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
	<?php if($this->login_by=='username'): ?>
	<td><?php echo $row->username; ?></td>
	<?php endif; ?>
	<td><?php echo $row->email; ?></td>
    <td><?php echo $row->phone; ?></td>
	<td><?php echo $usertype; ?></td>
    <td><?php echo $userrole; ?></td>
	<td align="center"><?php echo $status; ?></td>
	<td align="center"><?php echo $last_login; ?></td>
</tr>
<?php endforeach; ?>