<?php

$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$nc_color = $row->network_condition_val<24 ? "#449d44" : ($row->network_condition_val<54 ? "#ec971f" : "#c9302c");
?>
<tr>
    <td><?=$row->rt_state;?></td>
    <td><?=$row->nvt_result;?></td>
    <td><?=$row->signal_state;?></td>
    <td><span class="dot" style="vertical-align:sub; background-color:<?=$nc_color;?>"></span>&nbsp;<?=$row->network_condition;?></td>
    <td><?=$row->description;?></td>
    <td>
		<?=$row->num;?>
        <input type="hidden" class="num" value="<?=$row->num;?>" />
        <input type="hidden" class="num_poor" value="<?=$row->num_poor;?>" />
    </td>
</tr>
<?php endforeach; ?>