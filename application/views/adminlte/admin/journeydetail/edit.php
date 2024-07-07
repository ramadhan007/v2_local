<script>

$(document).ready(function(e) {
    $(document).keyup(function(e){
		if(e.keyCode == 27) {
			history.back(-1);
		}
	});
});

parent.document.getElementById('detailModalTitle').innerHTML='<?php echo str_replace("'","\'",$this->view['toptitle']); ?>';

</script>

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
                    <div class="form-group<?=form_group_error('name');?>">
                        <label>Name</label>
                        <input class="form-control" type="text" name="name" value="<?php echo set_value('name',(isset($row) ? $row->name : '')); ?>"/>
                        <?php echo form_error('name'); ?>
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
                    <?php if($this->session->userdata($this->controller.'_id')): ?>
                    <div class="form-group<?=form_group_error('ordering');?>">
                        <label>Ordering</label>
                        <?php echo $html['ordering']; ?>
                        <?php echo form_error('ordering'); ?>
                    </div>
                    <?php endif; ?>
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