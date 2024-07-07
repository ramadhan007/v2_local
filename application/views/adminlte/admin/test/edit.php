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
                    <div class="form-group <?=form_group_error('name');?>">
                        <label>Nama Test*</label>
                        <input class="form-control" type="text" name="name" value="<?php echo set_value('name',(isset($row) ? $row->name : '')); ?>"/>
                        <?php echo form_error('name'); ?>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="remarks" rows="2"><?php echo set_value('remarks',(isset($row) ? $row->remarks : '')); ?></textarea>
                        <?php echo form_error('remarks'); ?>
                    </div>
                    <div class="form-group" style="margin-bottom:0px;">
                        <label>Ditampilkan*</label>
                        <p class="form-control-static"><?php echo $html['published']; ?></p>
                        <?php echo form_error('published'); ?>
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

<script>

var num_setting = 0;

function addSetting()
{
	num_setting++;
	obj_div = '<div id="div_setting_' + num_setting + '"><div style="float:left; width:19%"><input class="form-control" type="text" name="setting_name[]" value=""/></div><div style="float:left; width:1%; text-align:center;"><p class="form-control-static">:</p></div><div style="float:right; width:2%; text-align:center;"><p class="form-control-static">[<a href="javascript:removeSetting('+ num_setting +');">X</a>]</p></div><div style="float:right; width:78%"><input class="form-control" type="text" name="setting_val[]" value=""/></div></div>';
	
	$('#div_setting_container').append(obj_div);
}

function removeSetting(n){
	$('#div_setting_' + n).remove();
}

</script>