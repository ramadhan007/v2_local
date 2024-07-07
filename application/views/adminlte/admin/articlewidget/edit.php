<script>
parent.document.getElementById('widgetModalTitle').innerHTML='<?php echo $this->view['toptitle']; ?>';
</script>
<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo $action; ?>">
                    <input type="hidden" id="task" name="task" value="" />
                    <input type="hidden" name="id" value="<?php echo $this->session->userdata($this->controller.'_id'); ?>"/>
                    <div class="form-group<?=form_group_error('name');?>">
                        <label>Name</label>
                        <input class="form-control" type="text" name="name" value="<?php echo set_value('name',(isset($row) ? $row->name : '')); ?>" size="55"/>
                        <?php echo form_error('name'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('title');?>">
                        <label>Title*</label>
                        <input class="form-control" type="text" name="title" value="<?php echo set_value('title',(isset($row) ? $row->title : '')); ?>" size="55"/>
                        <?php echo form_error('title'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('body');?>">
                        <label>Body</label>
                        <textarea name="body" id="body" style="width:100%; height:150px;" class="form-control" ><?php echo set_value('body',(isset($row) ? $row->body : '')); ?></textarea>
                        <?php echo form_error('body'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('published');?>" style="margin-bottom:0px">
                        <label>Published*</label>
                        <p class="form-control-static"><?php echo $html['published']; ?></p>
                        <?php echo form_error('published'); ?>
                    </div>
                </form>
            </div>
        </div>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-success" href="javascript:submitTask('');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->