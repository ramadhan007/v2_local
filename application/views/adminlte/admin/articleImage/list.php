<script>

$(document).ready(function(e) {
    $(document).keyup(function(e){
		if(e.keyCode == 27) {
			parent.closeModal('imageModal');
		}
	});
});

parent.document.getElementById('imageModalTitle').innerHTML='<?php echo str_replace("'","\'",$this->view['toptitle']); ?>';
parent.document.getElementById('span_image_count_<?php echo $this->session->userdata($this->controller.'_article_id'); ?>').innerHTML = '<?php echo $total_rows; ?>';

</script>

<form class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Name, Title" on />
                    <a class="btn btn-primary btn-sm" href="javascript:submitTask('');"><i class="fa fa-search fa-lg"></i> Search</a>
                    <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); submitTask('');"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" style="float:right">
                    <a class="btn btn-success btn-sm" href="javascript:submitTask('add');"><i class="fa fa-file-o fa-lg"></i> Add</a>
                    <a class="btn btn-primary btn-sm" href="javascript:submitTask('edit');"><i class="fa fa-edit fa-lg"></i> Edit</a>
                    <a class="btn btn-danger btn-sm" href="javascript:submitTask('delete');"><i class="fa fa-trash-o fa-lg"></i> Delete</a>
                </div>
            </div>
        </div>
	</div>
    <!-- /.box-header -->
    
    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="1"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1">No</th>
                <th>Image Name</th>
                <th>Title</th>
                <th>Path</th>
                <th width="1">Tampil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0 + $offset;
            $j = 0;
            $numrows = count($rows);
            foreach($rows as $row):
                $cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
                $link = anchor($this->controller.'/edit/'.$row->id,$row->name,array('class'=>'link'));
                
                $text_published = '<i class="fa fa-check fa-lg"></i> '.($row->published=='1' ? 'YES' : 'NO');
                $class_published = ($row->published=='1' ? 'success' : 'danger');
                $published = "<span class=\"label label-$class_published\">$text_published</span>";
            ?>
            <tr>
                <td><?php echo $cb; ?></td>
                <td><?php echo ++$i; ?></td>
                <td><?php echo $link; ?></td>
                <td><?php echo strip_tags($row->title); ?></td>
                <td><?php echo str_replace('{[base_url]}','<span class="label label-info">BASE URL</span>/',$row->path); ?></td>
                <td><?php echo $published; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
	</div>
	<!-- /.box-body -->
	<div class="box-footer clearfix">
      	<div class="row">
            <div class="col-sm-6">
				<?php if($numrows): ?>
                Menampilkan <?php echo $offset+1; ?> sampai <?php echo $offset+$numrows; ?> dari <?php echo $total_rows; ?> entri
                <?php else: ?>
                Tidak ada entri
                <?php endif; ?>
            </div>
            <div class="col-sm-6">
               	<?php echo $pagination; ?>
            </div>
		</div>
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" id="numrows" name="numrows" value="<?php echo $numrows; ?>" />
</form>