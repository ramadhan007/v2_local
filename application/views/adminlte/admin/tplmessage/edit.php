<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:$('form').submit();"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo $action; ?>">
                    <input type="hidden" id="task" name="task" value="" />
                    <input type="hidden" name="id" value="<?php echo $this->session->userdata($this->controller.'_id'); ?>"/>
                    <div class="form-group<?=form_group_error('tag');?>">
                        <label>Tag Template*</label>
                        <input class="form-control" type="text" name="tag" value="<?php echo set_value('tag',(isset($row) ? $row->tag : '')); ?>" size="55"/>
                        <?php echo form_error('tag'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('title');?>">
                        <label>Title*</label>
                        <input class="form-control" type="text" name="title" value="<?php echo set_value('title',(isset($row) ? $row->title : '')); ?>" size="86"/>
                        <?php echo form_error('title'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('body');?>" style="margin-bottom:0px">
                        <label>Body Message*</label>
                        <?php echo $html['body']; ?>
                        <?php echo form_error('body'); ?>
                    </div>
                </form>
            </div>
        </div>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-success" href="javascript:$('form').submit();"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->