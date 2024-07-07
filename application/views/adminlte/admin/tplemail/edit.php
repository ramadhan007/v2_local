<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
            <input type="hidden" id="task" name="task" value="" />
            <input type="hidden" name="id" value="<?php echo $this->session->userdata($this->controller.'_id'); ?>"/>
            <div class="form-group<?=form_group_error('category');?>">
                        <label>Category*</label>
                <input class="form-control" type="text" name="category" value="<?php echo set_value('category',(isset($row) ? $row->category : '')); ?>" size="55"/>
                <?php echo form_error('category'); ?>
            </div>
            <div class="form-group<?=form_group_error('tag');?>">
                <label>Tag Template*</label>
                <input class="form-control" type="text" name="tag" value="<?php echo set_value('tag',(isset($row) ? $row->tag : '')); ?>" size="55"/>
                <?php echo form_error('tag'); ?>
            </div>
            <div class="form-group<?=form_group_error('subject');?>">
                <label>Subject*</label>
                <input class="form-control" type="text" name="subject" value="<?php echo set_value('subject',(isset($row) ? $row->subject : '')); ?>" size="86"/>
                <?php echo form_error('subject'); ?>
            </div>
            <div class="form-group<?=form_group_error('body');?>">
                <label>Body Email</label>
                <?php echo $html['body']; ?>
                <?php echo form_error('body'); ?>
            </div>
            <div class="form-group<?=form_group_error('body_whatsapp');?>">
                <label>Body Whatsapp</label>
                <textarea name="body_whatsapp" id="body_whatsapp" style="width:100%; height:200px;" class="form-control" ><?php echo set_value('body_whatsapp',(isset($row) ? $row->body_whatsapp : '')); ?></textarea>
                <?php echo form_error('body_whatsapp'); ?>
            </div>
            <div class="form-group<?=form_group_error('body_sms');?>" style="margin-bottom:0px">
                <label>Body SMS</label>
                <textarea name="body_sms" id="body_sms" style="width:100%; height:200px;" class="form-control" ><?php echo set_value('body_sms',(isset($row) ? $row->body_sms : '')); ?></textarea>
                <?php echo form_error('body_sms'); ?>
            </div>
        </form>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<script>

$(document).ready(function(e) {
	
	$('#user_id').select2();
	
    $('.img-preview').click(function(e) {
        $(this).parent().find('input:file').trigger('click');
    });
	
	$('.btn-upload-img').click(function(e) {
        $(this).parent().find('input:file').trigger('click');
    });
	
	$(".file-preview").change(function(){
		readURL(this);
	});
	
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
			
            $(input).parent().find('img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

</script>