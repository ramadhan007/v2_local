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
            <div class="form-group<?=form_group_error('user_id');?>">
                <label>Link User</label>
                <?php echo $html['user_id']; ?>
                <?php echo form_error('name'); ?>
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