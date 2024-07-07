<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('');"><i class="fa fa-save"></i> Save</a>
		<?php if(!$this->session->userdata($this->controller.'_id')): ?>
        <a class="btn btn-success" href="javascript:submitTask('new');"><i class="fa fa-save"></i> Save &amp; New</a>
        <?php endif; ?>
        <a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
            <input type="hidden" id="task" name="task" value="" />
            <input type="hidden" name="id" value="<?php echo $this->session->userdata($this->controller.'_id'); ?>"/>
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
            <div class="form-group<?=form_group_error('body');?>">
                <label>Body</label>
                <?php echo $html['body']; ?>
                <?php echo form_error('body'); ?>
            </div>
            <?php if($this->session->userdata($this->controller.'_id')): ?>
            <div class="form-group">
                <label>Images</label>
                <div class="input-group">
                    <span id="span_image_count_<?php echo $this->session->userdata($this->controller.'_id'); ?>" class="form-control" style="width:auto;">
                        <?php echo set_value('image',(isset($row) ? $row->image : '0')); ?>
                    </span>
                    <span class="input-group-btn" style="float:left">
                        <button type="button" class="btn btn-default" onclick="openImageModal('<?php echo $this->session->userdata($this->controller.'_id'); ?>')">Manage Images</button>
                    </span>
                </div>
            </div>
            <?php endif; ?>
            <?php if($this->session->userdata($this->controller.'_id')): ?>
            <div class="form-group">
                <label>Widgets</label>
                <div class="input-group">
                    <span id="span_widget_count_<?php echo $this->session->userdata($this->controller.'_id'); ?>" class="form-control" style="width:auto;">
                        <?php echo set_value('widget',(isset($row) ? $row->widget : '0')); ?>
                    </span>
                    <span class="input-group-btn" style="float:left">
                        <button type="button" class="btn btn-default" onclick="openWidgetModal('<?php echo $this->session->userdata($this->controller.'_id'); ?>')">Manage Widgets</button>
                    </span>
                </div>
            </div>
            <?php endif; ?>
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
            <div class="form-group<?=form_group_error('category');?>">
                <label>Category</label>
                <div class="input-group" style="width:100%">
                    <?php
                    $size = substr_count($html['category_id'],'<option');
                    $size = $size>10 ? 10 : $size;
                    // echo str_replace('<select ','<select size="'.$size.'" ',$html['category_id[]']);
                    echo $html['category_id'];
                    ?>
                </div>
                <?php echo form_error('category_id'); ?>
            </div>
            <div class="form-group" style="margin-bottom:0px">
                <label>Published*</label>
                <p class="form-control-static"><?php echo $html['published']; ?></p>
                <?php echo form_error('published'); ?>
            </div>
        </form>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-success" href="javascript:submitTask('');"><i class="fa fa-save"></i> Save</a>
		<?php if(!$this->session->userdata($this->controller.'_id')): ?>
        <a class="btn btn-success" href="javascript:submitTask('new');"><i class="fa fa-save"></i> Save &amp; New</a>
        <?php endif; ?>
        <a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<!-- Image Modal -->
<div class="modal large autoheight fade" id="imageModal" style="width:auto; height:auto;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="imageModalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Widget Modal -->
<div class="modal large autoheight autoheight fade" id="widgetModal" style="width:auto; height:auto;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="widgetModalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	
	$('#category_id').select2();
	
	$('input:radio[name=meta_enable]').change(function(e) {
		showElement('meta_enable','div_meta');
    });
	
	showElement('meta_enable','div_meta');
	
});

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

function openImageModal(article_id)
{
	$('#imageModal iframe').attr('src','<?php echo site_url('admin/articleimage/index/0'); ?>/' + article_id);
	$('#imageModal').modal("show");
}

function openWidgetModal(article_id)
{
	$('#widgetModal iframe').attr('src','<?php echo site_url('admin/articlewidget/index/0'); ?>/' + article_id);
	$('#widgetModal').modal("show");
}

function closeModal(modal_id)
{
	$('#' + modal_id).modal("hide");
}

</script>