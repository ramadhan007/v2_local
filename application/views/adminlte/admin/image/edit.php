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
                    <?php if(!$this->session->userdata($this->controller.'_name')): ?>
                    <div class="form-group<?=form_group_error('name');?>">
                        <label>Image Name*</label>
                        <input class="form-control" type="text" name="name" value="<?php echo set_value('name',(isset($row) ? $row->name : '')); ?>" size="55"/>
                        <?php echo form_error('name'); ?>
                    </div>
                    <?php endif; ?>
                    <div class="form-group<?=form_group_error('title');?>">
                        <label>Title</label>
                        <input class="form-control" type="text" name="title" value="<?php echo set_value('title',(isset($row) ? $row->title : '')); ?>" size="55"/>
                        <?php echo form_error('title'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('path');?>">
                        <label>Path*</label>
                        <div class="input-group">
                            <input class="form-control" type="text" id="path" name="path" value="<?php echo set_value('path',(isset($row) ? $row->path : '')); ?>" size="55"/>
                            <span class="input-group-btn">
                                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default">Select</button>
                            </span>
                        </div>
                        <?php echo form_error('path'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('description');?>">
                        <label>Description</label>
                        <textarea name="description" id="description" style="width:100%; height:150px;" class="form-control" ><?php echo set_value('description',(isset($row) ? $row->description : '')); ?></textarea>
                        <?php echo form_error('description'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('link');?>">
                        <label>Link</label>
                        <input class="form-control" type="text" id="link" name="link" value="<?php echo set_value('link',(isset($row) ? $row->link : '')); ?>" size="55"/>
                        <?php echo form_error('link'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('show_caption');?>">
                        <label>Show Caption</label>
                        <p class="form-control-static"><?php echo $html['show_caption']; ?></p>
                        <?php echo form_error('show_caption'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('published');?>" style="margin-bottom:0px">
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
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<!-- Modal -->
<div class="modal fade" id="myModal" style="width:auto; height:auto;">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
  	<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Select Image</h4>
    </div>
    <div class="modal-body">
    	<iframe width="100%" height="400px" src="<?php echo base_url('filemanager/dialog.php?type=1&amp;field_id=path'); ?>" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
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
});

</script>

