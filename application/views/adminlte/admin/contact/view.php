<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-default" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Back</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <form>
            <input type="hidden" id="task" name="task" value="" />
            <div class="form-group">
                <label>Nama*</label>
                <p class="form-control-static"><?php echo $row->name; ?></p>
            </div>
            <div class="form-group">
                <label>Logo</label>
                <img src="<?php echo $logo; ?>" class="img-rounded img-responsive" height="200px">
            </div>
        </form>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-default" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Back</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->