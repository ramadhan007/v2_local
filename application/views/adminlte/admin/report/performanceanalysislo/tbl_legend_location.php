<tbody id="tbody_legend_location" data-array='<?=json_encode($array_location);?>' data-color='<?=json_encode($array_color);?>'>
<?php
if(count($array_color)==0){
	$array_color = get_array_color();
}
// print_r($array_color);
$rows = get_rows("select id, name from tb_operator where published order by id");
$i = -1;
for($j=-1; $j<count($rows); $j++):
if($j>-1) $row = $rows[$j];
if($j>-1) $field = $row->name;
?>
<tr class="table-bordered">
	<?php
	if($i==-1):?>
	<td align="left">&nbsp;</td>	
	<?php else: ?>
	<td align="left" class="table-bordered" data-name="<?=$row->name;?>">
		<svg width="30" height="3" style="vertical-align:middle">
			<rect width="30" height="30" style="fill:<?=$array_color[$i];?>;stroke-width:3;stroke:#ffffff" />
		</svg>
		<?=$row->name;?>
	</td>
	<?php endif;
	if($rows_location):
	foreach($rows_location as $row_location):
	?>
	<td class="table-bordered" style="padding-left:3px; padding-right:3px;">
	<?=($i>-1 ? $row_location->$field : $row_location->label);?>
	</td>
	<?php	
	endforeach;
	endif;
	?>
</tr>
<?php
$i++;
endfor;
?>
</tbody>