<?php $id = $this->session->userdata($this->controller.'_id'); ?>
<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<?php if(!$this->session->userdata($this->controller.'_id')): ?>
        <a class="btn btn-success" href="javascript:submitTask('new');"><i class="fa fa-save"></i> Save &amp; New</a>
        <?php endif; ?>
        <a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo $action; ?>">
                    <input type="hidden" id="task" name="task" value="" />
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <div class="form-group<?=form_group_error('text');?>">
                        <label>Text</label>
                        <input class="form-control" type="text" id="text" name="text" value="<?php echo set_value('text',(isset($row) ? $row->text : '')); ?>" size="55"/>
                        <?php echo form_error('text'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('short');?>">
                        <label>Singkatan</label>
                        <input class="form-control" type="text" id="short" name="short" value="<?php echo set_value('short',(isset($row) ? $row->short : '')); ?>" size="55"/>
                        <?php echo form_error('short'); ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 form-group<?=form_group_error('val');?>">
                            <label>Nilai</label>
                            <input class="form-control" type="text" id="val" name="val" value="<?php echo set_value('val',(isset($row) ? $row->val : '')); ?>" size="55"/>
                            <?php echo form_error('val'); ?>
                        </div>
                        <div class="col-sm-4 form-group<?=form_group_error('val_min');?>">
                            <label>Nilai Min</label>
                            <input class="form-control" type="text" id="val_min" name="val_min" value="<?php echo set_value('val_min',(isset($row) ? $row->val_min : '')); ?>" size="55"/>
                            <?php echo form_error('val_min'); ?>
                        </div>
                        <div class="col-sm-4 form-group<?=form_group_error('val_max');?>">
                            <label>Nilai Max</label>
                            <input class="form-control" type="text" id="val_max" name="val_max" value="<?php echo set_value('val_max',(isset($row) ? $row->val_max : '')); ?>" size="55"/>
                            <?php echo form_error('val_max'); ?>
                        </div>
                   	</div>
                    <div class="row">
                    	<div class="col-sm-6 form-group<?=form_group_error('icon');?>">
                            <label>Icon</label>
                            <div class="input-group">
                                <span class="form-control" style="width:auto;">
                                <li title="<?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>" id="li_icon" class="fa <?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>"></li>
                                <input type="hidden" id="icon" name="icon" value="<?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>" />
                                </span>
                                <span class="input-group-btn" style="float:left">
                                    <button type="button" data-toggle="modal" data-target="#iconModal" class="btn btn-primary">Select Icon</button>
                                    <button type="button" class="btn btn-danger" onclick="deleteIcon();">Delete Icon</button>
                                </span>
                            </div>
                            <?php echo form_error('icon'); ?>
                        </div>
                        <div class="col-sm-6 form-group<?=form_group_error('class');?>">
                            <label>Class</label>
                            <div class="input-group">
                            	<?php echo $html['class']; ?>
                                <div class="input-group-addon">
                                    <span id="span_sample" class="label label-info">Sample</span>
                                </div>
                            </div>
                            <?php echo form_error('class'); ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:0px">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<?php if(!$this->session->userdata($this->controller.'_id')): ?>
        <a class="btn btn-success" href="javascript:submitTask('new');"><i class="fa fa-save"></i> Save &amp; New</a>
        <?php endif; ?>
        <a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<!-- Icon Modal -->
<div class="modal autoheight fade" id="iconModal" style="width:auto; height:auto;">
    <div class="modal-dialog" style="width:95%; height:95%">
    
      <!-- Modal content-->
      <div class="modal-content" style="height:100%">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select Icon</h4>
        </div>
        <div class="modal-body" style="height:100%">
            <iframe width="100%" height="400px" src="<?php echo site_url('admin/icon/search/0/icon'); ?>" frameborder="0" style="overflow: hidden;"></iframe>
        </div>
      </div>
      
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	
	function reposition() {
		var modal = $(this),
			dialog = modal.find('.modal-dialog');
		modal.css('display', 'block');
		
		// Dividing by two centers the modal exactly, but dividing by three 
		// or four works better for larger screens.
		dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
		//dialog.css("margin-left", Math.max(0, ($(window).width() - dialog.width()) / 2));
	}
	
	// Reposition when a modal is shown
    $('.modal').on('show.bs.modal', reposition);
	
	// Reposition when the window is resized
    $(window).on('resize', function() {
        $('.modal:visible').each(reposition);
    });
	
	$('#class').change(function(e) {
        $('#span_sample').removeClass();
		$('#span_sample').addClass('label');
		$('#span_sample').addClass('label-' + $(this).val());
    });
	
	$('#class').trigger('change');
	
});

function deleteIcon()
{
	$('#icon').val('');
	$('#li_icon').attr('class','fa');
}

function hideIconModal(icon_value)
{
	$('#icon').val(icon_value);
	$('#li_icon').attr('class','fa ' + icon_value);
	$("#iconModal").modal("hide");
}

</script>