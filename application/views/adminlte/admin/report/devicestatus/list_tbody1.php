<?php
$numrows = count($rows);
foreach($rows as $row):
?>
<td><?=$offset;?></td>
<td><?php echo $row->id; ?></td>
<!-- <td><?php echo $row->phone_number; ?></td> -->
<td><?php echo $row->application_text; ?></td>
<td><?php echo $row->location_name; ?></td>
<td><?php echo $row->operator_name; ?></td>
<td><a href="#" class="btn btn-xs btn-<?=$row->status_class;?> btn_status" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->status_icon;?>"></i>&nbsp;<span class="spn_status_text"><?=$row->status_text;?></span></a></td>
<td>
    <?php
    if(!$row->status_final){
        echo $row->status_age;
    }
    ?>
</td>
<?php endforeach; ?>