<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo $action; ?>">
                    <input type="hidden" id="task" name="task" value="" />
                    <input type="hidden" name="id" value="<?php echo $this->session->userdata($this->controller.'_id'); ?>"/>
                    <div class="form-group<?=form_group_error('start_datetime');?>">
                    	<label>Start Date Time</label>
                        <div class="input-group date" style="width:170px">
                            <input class="form-control input-datetime" type="text" id="start_datetime" name="start_datetime" value="<?php echo set_value('start_datetime',(isset($row) ? substr(date_mysql2dmyhns($row->start_datetime),0,16) : '')); ?>" style="text-align:center" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group<?=form_group_error('end_datetime');?>">
                    	<label>End Date Time</label>
                        <div class="input-group date" style="width:170px;">
                            <input class="form-control input-datetime" type="text" id="end_datetime" name="end_datetime" value="<?php echo set_value('end_datetime',(isset($row) ? substr(date_mysql2dmyhns($row->end_datetime),0,16) : '')); ?>" style="text-align:center;" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group<?=form_group_error('remarks');?>">
                        <label>Remarks</label>
                        <textarea name="remarks" id="remarks" style="width:100%; height:150px;" class="form-control" ><?php echo set_value('remarks',(isset($row) ? $row->remarks : '')); ?></textarea>
                        <?php echo form_error('remarks'); ?>
            		</div>
                    <div class="form-group<?=form_group_error('published');?>">
                        <label>Published</label>
                        <?php echo $html['published']; ?>
                        <?php echo form_error('published'); ?>
                    </div>
                </form>
            </div>
        </div>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<script>

$(document).ready(function(e) {
    $('.input-datetime').datetimepicker({
		format : "DD-MM-YYYY HH:mm"
	});
});

</script>