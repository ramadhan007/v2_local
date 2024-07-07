<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form>
                    <div class="form-group">
                        <label>Nama</label>
                        <p class="form-control-static"><?php echo $row->name; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p class="form-control-static"><?php echo $row->email; ?></p>
                    </div>
                    <div class="form-group">
                        <label>HP</label>
                        <p class="form-control-static"><?php echo $row->phone; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <p class="form-control-static"><?php echo $row->subject; ?></p>
                    </div>
                    <div class="form-group" style="margin-bottom:0px">
                        <label>Pesan</label>
                        <p class="form-control-static"><?php echo $row->message; ?></p>
                    </div>
                </form>
            </div>
		</div>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-success" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->