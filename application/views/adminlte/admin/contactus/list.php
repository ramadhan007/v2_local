<form class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Tag, Title" on />
                    <a class="btn btn-primary btn-sm" href="javascript:submitTask('');"><i class="fa fa-search fa-lg"></i> Search</a>
                    <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); submitTask('');"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" style="float:right">
                    <a class="btn btn-danger btn-sm" href="javascript:submitTask('delete');"><i class="fa fa-trash-o fa-lg"></i> Delete</a>
                    <a class="btn btn-primary btn-sm" href="javascript:openContactModal();"><i class="fa fa-phone fa-lg"></i> Contact Detail</a>
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
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0 + $offset;
            $j = 0;
            $numrows = count($rows);
            foreach($rows as $row):
                $cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
                $link = anchor($this->controller.'/view/'.$row->id,$row->name,array('class'=>'link'));
            ?>
            <tr>
                <td align="center"><?php echo $cb; ?></td>
                <td><?php echo ++$i; ?></td>
                <td><?php echo $link; ?></td>
                <td><?php echo $row->email; ?></td>
                <td><?php echo $row->phone; ?></td>
                <td><?php echo $row->subject; ?></td>
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

<!-- Contact Detail Modal -->
<div class="modal large autoheight fade" id="contactModal" style="width:auto; height:auto;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="contactModalTitle" class="modal-title">Contact Detail</h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function openContactModal()
{
	$('#contactModal iframe').attr('src','<?php echo site_url('admin/contactus/contactdetail'); ?>');
	$('#contactModal').modal("show");
}

function closeModal()
{
	$('#contactModal').modal("hide");
}

</script>