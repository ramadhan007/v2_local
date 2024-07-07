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
                    <div class="form-group<?=form_group_error('name');?>">
                        <label>Name</label>
                        <input class="form-control" type="text" name="name" value="<?php echo set_value('name',(isset($row) ? $row->name : '')); ?>"/>
                        <?php echo form_error('name'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('activity_name');?>">
                        <label>Activity Name</label>
                        <input class="form-control" type="text" name="activity_name" value="<?php echo set_value('activity_name',(isset($row) ? $row->activity_name : '')); ?>"/>
                        <?php echo form_error('activity_name'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('start_timer');?>">
                        <label>Start Timer</label>
                        <input class="form-control" type="text" name="start_timer" value="<?php echo set_value('start_timer',(isset($row) ? $row->start_timer : '')); ?>"/>
                        <?php echo form_error('start_timer'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('application');?>">
                        <label>Application</label>
                        <?=$html['application'];?>
                        <?=form_error('application');?>
                    </div>
                    <div class="form-group<?=form_group_error('type');?>">
                        <label>Type</label>
                        <?=$html['type'];?>
                        <?=form_error('type');?>
                    </div>
                    <div class="form-group<?=form_group_error('platform');?>">
                        <label>Platform</label>
                        <?php echo $html['platform']; ?>
                        <?php echo form_error('platform'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('condition');?>">
                        <label>Custom WHERE Condition</label>
                        <textarea name="condition" id="condition" style="width:100%;" class="form-control" ><?php echo set_value('condition',(isset($row) ? $row->condition : '')); ?></textarea>
                        <?php echo form_error('condition'); ?>
                        <p class="form-control-static">Example:</p>
                        <pre>device.location_name = 'Jakarta' AND device.operator_name = 'WiFi'<br />AND device.platform_name = 'Android' AND (device.platform_version = '10' OR device.platform_version = '11')</pre>
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