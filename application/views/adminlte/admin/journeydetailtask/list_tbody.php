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
?>
<tr>
	<td><?php echo $cb; ?></td>
	<td><?php echo ++$i; ?></td>
	<td><?php echo $link; ?></td>
    <td><?php echo $row->type_text; ?></td>
    <td><?php echo $row->find_by_text; ?></td>
    <td><a href="#" class="my_tooltip" title="<?=str_replace("\n","\n",$row->content);?>" data-toggle="tooltip"><?=substr($row->content,0,10).(strlen($row->content)>10 ? "..." : "");?></a></td>
    <td><a href="#" class="my_tooltip" title="<?=str_replace("\n","\n",$row->content_ios);?>" data-toggle="tooltip"><?=substr($row->content_ios,0,10).(strlen($row->content_ios)>10 ? "..." : "");?></a></td>
    <td><?php echo $row->handler_text; ?></td>
    <td><?php echo $row->timeout; ?></td>
    <td><?php echo $row->action; ?></td>
    <td><?php echo $row->wait; ?></td>
    <td><?php echo $row->input; ?></td>
    <td><a href="#" class="my_tooltip" title="<?=$row->start_timer_when_text?>" data-toggle="tooltip" ><?php echo $row->start_timer; ?></a></td>
    <td><a href="#" class="my_tooltip" title="<?=$row->end_timer_when_text?>" data-toggle="tooltip" ><?php echo $row->end_timer; ?></a></td>
    <td style="text-align:center"><a href="#" class="btn btn-xs btn-<?=$row->record_param_class;?> my_tooltip" role="button" data-id="<?=$row->id;?>" title="<?=$row->record_param_when_text?>" data-toggle="tooltip" ><i class="fa <?=$row->record_param_icon;?>"></i>&nbsp;<?=$row->record_param_text;?></a></td>
    <td style="text-align:center"><a href="#" class="btn btn-xs btn-<?=$row->upload_class;?> my_tooltip" role="button" data-id="<?=$row->id;?>" title="<?=$row->upload_when_text?>" data-toggle="tooltip" ><i class="fa <?=$row->upload_icon;?>"></i>&nbsp;<?=$row->upload_text;?></a></td>
    <td style="text-align:center"><?php echo $row->platform_text ? $row->platform_text : 'All'; ?></td>
    <td style="text-align:center"><a href="#" class="btn btn-xs btn-<?=$row->published_class;?> btn_published" role="button" data-id="<?=$row->id;?>" ><i class="fa <?=$row->published_icon;?>"></i>&nbsp;<?=$row->published_text;?></a></td>
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