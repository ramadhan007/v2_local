<?php
$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
?>
<tr class="datarow" data-id="<?=$row->id;?>" data-offset="<?=$i+1;?>" data-index="<?=$j;?>" data-status="<?=$row->status_final;?>" data-status_time="<?=$row->status_time;?>">
	<td><?php echo ++$i; ?></td>
    <td><?php echo $row->id; ?></td>
    <!-- <td><?php echo $row->phone_number; ?></td> -->
    <td><?php echo $row->application_text; ?></td>
    <td><?php echo $row->location_name; ?></td>
    <td><?php echo $row->operator_name; ?></td>
    <td><a href="#" class="btn btn-xs btn-<?=$row->status_class;?> btn_status" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->status_icon;?>"></i>&nbsp;<span class="status_text"><?=$row->status_text;?></span></a></td>
    <td class="status_age">
    	<?php
        if(!$row->status_final){
			echo $row->status_age;
		}
		?>
    </td>
</tr>
<?php endforeach; ?>