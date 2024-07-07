<form id="frm_cari" class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-sm-12">
                <div class="input-group">
                    <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Title, Alias, Keywords" on />
                     <div class="input-group-btn">
                    	<a class="btn btn-primary btn-sm" href="javascript:document.getElementById('frm_cari').submit();"><i class="fa fa-search fa-lg"></i> Search</a>
                    	<a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); document.getElementById('frm_cari').submit();"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                   	</div>
                </div>
            </div>
        </div>
	</div>
    <!-- /.box-header -->
	
    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="1">No</th>
                <th>Name</th>
                <th>Icon</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0 + $offset;
            $j = 0;
            $numrows = count($rows);
            foreach($rows as $row):
            ?>
            <tr>
                <td><?php echo ++$i; ?></td>
                <td><a href="javascript:ReturnID('<?php echo $row->value; ?>');"><?php echo $row->text; ?></a></td>
                <td><i class="fa <?php echo $row->value; ?>"></i></td>
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

<script type="application/javascript">

$(document).ready(function(e) {
	$('#filter_cari').keypress(function(e) {
		if(e.which==13){
			document.getElementById('frm_cari').submit();
		}
    });
});

function ReturnID(icon_value)
{
	parent.hideIconModal(icon_value);
}

</script>