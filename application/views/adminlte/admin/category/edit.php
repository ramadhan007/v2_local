<?php
$id = $this->session->userdata($this->controller.'_id');
?>
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
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <div class="form-group<?=form_group_error('parent_id');?>">
                        <label>Parent Item</label>
                        <?php echo $html['parent_id']; ?>
                        <?php echo form_error('parent_id'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('title');?>">
                        <label>Title*</label>
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
                            <li id="li_icon" class="fa <?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>"></li>
                            <input type="hidden" id="icon" name="icon" value="<?php echo set_value('icon',(isset($row) ? $row->icon : '')); ?>" />
                            </span>
                            <span class="input-group-btn" style="float:left">
                                <button type="button" data-toggle="modal" data-target="#iconModal" class="btn btn-default">Select Icon</button>
                                <button type="button" class="btn btn-default" onclick="deleteIcon();">Delete Icon</button>
                            </span>
                        </div>
                        <?php echo form_error('icon'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('body');?>">
                        <label>Body</label>
                        <?php echo $html['body']; ?>
                        <?php echo form_error('body'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('thumb');?>">
                        <label>Thumb</label>
                        <div class="input-group">
                            <input class="form-control" type="text" id="thumb" name="thumb" value="<?php echo set_value('thumb',(isset($row) ? $row->thumb : '')); ?>" size="55"/>
                            <span class="input-group-btn">
                                <button type="button" data-toggle="modal" data-target="#thumbModal" class="btn btn-default">Select</button>
                            </span>
                        </div>
                        <?php echo form_error('thumb'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('meta_enable');?>">
                        <label>Enable Meta</label>
                        <p class="form-control-static"><?php echo $html['meta_enable']; ?></p>
                        <?php echo form_error('meta_enable'); ?>
                    </div>
                    <div id="div_meta" style="display:none">
                        <div class="form-group<?=form_group_error('meta_keywords');?>">
                            <label>Meta Keywords</label>
                            <input class="form-control" type="text" name="meta_keywords" value="<?php echo set_value('meta_keywords',(isset($row) ? $row->meta_keywords : '')); ?>" size="55"/>
                            <?php echo form_error('meta_keywords'); ?>
                        </div>
                        <div class="form-group<?=form_group_error('meta_description');?>">
                            <label>Meta Description</label>
                            <textarea name="meta_description" id="meta_description" style="width:100%; height:200px;" class="form-control" ><?php echo set_value('meta_description',(isset($row) ? $row->meta_description : '')); ?></textarea>
                            <?php echo form_error('meta_description'); ?>
                        </div>
                    </div>
                    <div class="form-group<?=form_group_error('per_page');?>">
                        <label>Per Page</label>
                        <input class="form-control" type="text" name="per_page" value="<?php echo set_value('per_page',(isset($row) ? $row->per_page : '')); ?>" style="width:50px; text-align:center" />
                        <?php echo form_error('per_page'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('image_per_page');?>">
                        <label>Image Per Page</label>
                        <input class="form-control" type="text" name="image_per_page" value="<?php echo set_value('image_per_page',(isset($row) ? $row->image_per_page : '')); ?>" style="width:50px; text-align:center" />
                        <?php echo form_error('image_per_page'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('widget_per_page');?>">
                        <label>Widget Per Page</label>
                        <input class="form-control" type="text" name="widget_per_page" value="<?php echo set_value('widget_per_page',(isset($row) ? $row->widget_per_page : '')); ?>" style="width:50px; text-align:center" />
                        <?php echo form_error('widget_per_page'); ?>
                    </div>
                    <?php if($this->session->userdata($this->controller.'_id')): ?>
                    <div class="form-group<?=form_group_error('ordering');?>">
                        <label>Urutan*</label>
                        <?php echo $html['ordering']; ?>
                        <?php echo form_error('ordering'); ?>
                    </div>
                    <?php endif; ?>
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
		<a class="btn btn-success" href="javascript:$('form').submit();"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<!-- Modal -->
<div class="modal fade" id="thumbModal" style="width:auto; height:auto;">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
  	<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Select Image</h4>
    </div>
    <div class="modal-body">
    	<iframe width="100%" height="400px" src="<?php echo base_url('filemanager/dialog.php?type=1&amp;field_id=thumb'); ?>" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
    </div>
  </div>
  
</div>
</div>

<!-- Icon Modal -->
<div class="modal autoheight fade" id="iconModal" style="width:auto; height:auto;">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
  	<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Select Icon</h4>
    </div>
    <div class="modal-body">
    	<iframe width="100%" height="400px" src="<?php echo site_url('admin/icon/search/0/icon'); ?>" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
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
	
	$('input:radio[name=meta_enable]').change(function(e) {
        showElement('meta_enable','div_meta');
    });
	
	showElement('meta_enable','div_meta');
	
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

function showElement(check_element, display_element){
	if($('input:radio[name=' + check_element + ']:checked').val()=='1'){
		$('#' + display_element).css('display','inline').animate({
        	opacity: 1,
        	width: "100%"
		}, 1000);
	}
	else{
		$('#' + display_element).animate({
			opacity: 0,
			width: 0
		}, 1000, function() {
			$(this).css('display','none');
		});
	}
}

</script>