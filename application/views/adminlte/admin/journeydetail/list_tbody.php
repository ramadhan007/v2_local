<?php

$row = $this->model->min_max_ordering();
$min_ordering = $row->min_ordering;
$max_ordering = $row->max_ordering;

$i = 0 + $offset;
$j = 0;
$numrows = count($rows);
foreach($rows as $row):
	$cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
	$link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
	
	$list = '<a id="span_list_count_'.$row->id.'" href="javascript:openDetailModal('.$row->id.');" class="btn btn-info btn-xs" role="button" style="width:50px;">'.$row->list.'</a>';
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
    <td align="center"><?php echo $list; ?></td>
    <td style="text-align:center"><a href="#" class="btn btn-xs btn-<?=$row->upload_class;?> btn_uoload" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->upload_icon;?>"></i>&nbsp;<?=$row->upload_text;?></a></td>
    <td style="text-align:center"><?php echo $row->platform_text ? $row->platform_text : 'All'; ?></td>
    <td><a href="#" class="btn btn-xs btn-<?=$row->published_class;?> btn_published" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->published_icon;?>"></i>&nbsp;<?=$row->published_text;?></a></td>
    <td align="right">
        <?php if($row->ordering>$min_ordering): ?>
        <a href="javascript:document.getElementById('cb<?=$j-1;?>').checked=true; submitTask('orderup');" style="padding-right:2px">
            <i class="fa fa-caret-up"></i>
        </a>
        <?php endif; ?>
        <?php if($row->ordering<$max_ordering): ?>
        <a href="javascript:document.getElementById('cb<?=$j-1;?>').checked=true; submitTask('orderdown');" style="padding-right:2px">
            <i class="fa fa-caret-down"></i>
        </a>
        <?php endif; ?>
        <?php echo $row->ordering; ?>
    </td>
</tr>
<?php endforeach; ?>