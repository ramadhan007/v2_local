<script>

$(document).ready(function(e) {
    $(document).keyup(function(e){
		if(e.keyCode == 27) {
			parent.closeModal();
		}
	});
});

</script>

<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:parent.closeModal();"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
                    <input type="hidden" id="task" name="task" value="" />
                    <input type="hidden" name="id" value="<?php echo $this->session->userdata($this->controller.'_id'); ?>"/>
                    <div class="form-group">
                        <?php echo $contactdetail; ?>
                        <!-- <textarea name="contactdetail" id="contactdetail" style="width:100%; height:440px;" class="form-control" ><?php echo $contactdetail; ?></textarea> -->
                    </div>
                </form>
            </div>
        </div>
	</div>
    <!-- /.box-body -->
</div>
<!-- /.box -->