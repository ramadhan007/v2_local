<form class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
    	<div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Tag, Title" on />
                    <a class="btn btn-primary btn-sm" href="javascript:submitTask('');" title="Search"><i class="fa fa-search fa-lg"></i></a>
                    <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); submitTask('');" title="Reset"><i class="fa fa-refresh fa-lg"></i></a>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" style="float:right">
                    <a class="btn btn-success btn-sm" href="javascript:submitTask('add');" title="Add"><i class="fa fa-file-o fa-lg"></i></a>
                    <a class="btn btn-primary btn-sm" href="javascript:submitTask('edit');" title="Edit"><i class="fa fa-edit fa-lg"></i></a>
                    <a class="btn btn-danger btn-sm" href="javascript:submitTask('delete');" title="Delete"><i class="fa fa-trash-o fa-lg"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    
    <div class="box-body table-responsive">
      <table class="table table-striped table-hover">
      	<thead>
            <tr>
                <th width="1"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1">No</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th width="1">Dipublish</th>
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
                <td><?php echo $row->remarks; ?></td>
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
</div>
<!-- /.box -->
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" id="numrows" name="numrows" value="<?php echo $numrows; ?>" />
</form>

<!-- Widget Modal -->
<div class="modal large autoheight fade" id="settingModal" style="width:auto; height:auto;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="settingModalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function openSettingModal(site_id)
{
	$('#settingModal iframe').attr('src','<?php echo site_url('admin/sitesetting/index/0'); ?>/' + site_id);
	$('#settingModal').modal("show");
}

function closeModal(modal_id)
{
	$('#' + modal_id).modal("hide");
}

</script>