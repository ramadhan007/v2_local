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
                    <div class="form-group<?=form_group_error('parent_id');?>">
                        <label>Parent Item*</label>
                        <?php echo $html['parent_id']; ?>
                        <?php echo form_error('parent_id'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('title');?>">
                        <label>Title</label>
                        <input class="form-control" type="text" name="title" value="<?php echo set_value('title',(isset($row) ? $row->title : '')); ?>" size="55"/>
                        <?php echo form_error('title'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('alias');?>">
                        <label>Alias</label>
                        <input class="form-control" type="text" name="alias" value="<?php echo set_value('alias',(isset($row) ? $row->alias : '')); ?>" size="55"/>
                        <?php echo form_error('alias'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('icon');?>">
                        <label>Icon</label>
                        <div class="input-group">
                            <span class="form-control" style="width:auto;">
                            <li title="<?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>" id="li_icon" class="fa <?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>"></li>
                            <input type="hidden" id="icon" name="icon" value="<?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>" />
                            </span>
                            <span class="input-group-btn" style="float:left">
                                <button type="button" data-toggle="modal" data-target="#iconModal" class="btn btn-default">Select Icon</button>
                                <button type="button" class="btn btn-default" onclick="deleteIcon();">Delete Icon</button>
                            </span>
                        </div>
                        <?php echo form_error('icon'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('link');?>">
                        <label>Link</label>
                        <div class="input-group">
                            <input class="form-control" type="text" id="link" name="link" value="<?php echo set_value('link',(isset($row) ? $row->link : '')); ?>" size="86"/>
                            <span class="input-group-btn">
                                <button type="button" data-toggle="modal" data-target="#articleModal" class="btn btn-default">Select Article</button>
                            </span>
                        </div>
                        <?php echo form_error('link'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('usertype');?>">
                    	<label>Level Akses</label>
                        <?php echo $html['usertype']; ?>
                        <?php echo form_error('usertype'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('published');?>" style="margin-bottom:0px">
                        <label>Ditampilkan*</label>
                        <p class="form-control-static"><?php echo $html['published']; ?></p>
                        <?php echo form_error('published'); ?>
                    </div>
                    <?php if($this->session->userdata($this->controller.'_id')): ?>
                    <div class="form-group<?=form_group_error('ordering');?>" style="margin-bottom:0px">
                        <label>Urutan*</label>
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

<!-- <style>

@media screen and (min-width: 768px){
	.modal-dialog {
		width: 90%;
		height: auto;
		overflow: hidden;
	}
}

</style> -->

<!-- Icon Modal -->
<div class="modal autoheight fade" id="iconModal" style="width:auto; height:auto;">
    <div class="modal-dialog" style="width:50%; height:80%">
    
      <!-- Modal content-->
      <div class="modal-content" style="height:100%">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select Icon</h4>
        </div>
        <div class="modal-body" style="height:100%">
            <iframe width="100%" height="400px" src="<?php echo site_url($this->config->item("admin").'/icon/search/0/icon'); ?>" frameborder="0" style="overflow: hidden;"></iframe>
        </div>
      </div>
      
    </div>
</div>

<!-- Article Modal -->
<div class="modal autoheight fade" id="articleModal" style="width:auto; height:auto;">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
  	<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Select Article</h4>
    </div>
    <div class="modal-body">
    	<iframe width="100%" height="400px" src="<?php echo site_url($this->config->item("admin").'/article/search/0/link'); ?>" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
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
	
	/* $('#icon').change(function(e) {
		$('#li_icon').removeClass('fa-desktop');
        $('#li_icon').addClass($(this).val());
    }); */
	
	<?php if($id): ?>
	var def_parent_id = '<?php echo $row->parent_id; ?>';
	$('#parent_id').change(function(e) {
        if($(this).val()=='<?php echo $id; ?>'){
			alert('Cannot set parent to item itself! Please select another parent');
			$(this).val(def_parent_id);
		}
		else{
			def_parent_id = $(this).val();
		}
    });
	<?php endif ?>
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

function hideArticleModal()
{
	$("#articleModal").modal("hide");
}

</script>