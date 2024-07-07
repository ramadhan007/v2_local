<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
    
    <?php echo validation_errors(); ?>
    
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
                    
                    <input type="hidden" id="task" name="task" value="" />
                    <input type="hidden" name="id" value="<?php echo $this->session->userdata($this->controller.'_id'); ?>"/>
                    <div class="row">
                    	<div class="col-md-2 col-sm-3">
                        	<img src="<?php echo $picture; ?>" class="img-preview img-rounded" style="cursor:pointer" width="130px">
                           	<input type="file" name="file_picture" style="display:none" class="file-preview" />
                            <div style="padding-top:10px;">
                            	<div class="btn-group">
                                    <a class="btn btn-sm btn-default btn-upload-picture"><i class="fa fa-upload"></i> <?php echo $picture ? "Ubah" : "Unggah"; ?></a>
                                    <?php if($picture): ?>
                                    <a class="btn btn-sm btn-default btn-delete-picture"><i class="fa fa-trash"></i> Hapus</a>
                                    <?php endif; ?>
                                    <input type="hidden" id="delete_picture" name="delete_picture" value="0" />
                            	</div>
							</div>
                            <?php echo $this->session->userdata($this->controller.'_upload_error_file_picture'); ?>
                        </div>
                        <div class="col-md-10 col-sm-9">
                            <div class="form-group<?=form_group_error('username');?>">
                                <label>Username<?=$this->login_by=='username'?"*":""; ?></label>
                                <input class="form-control" type="text" name="username" value="<?php echo set_value('username',(isset($row) ? $row->username : '')); ?>" size="50" />
                                <?php echo form_error('username'); ?>
                            </div>
                            <div class="form-group<?=form_group_error('name');?>">
                                <label>Nama*</label>
                                <input class="form-control" type="text" name="name" value="<?php echo set_value('name',(isset($row) ? $row->name : '')); ?>" size="50" />
                                <?php echo form_error('name'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group<?=form_group_error('phone');?>">
                        <label>No Telepon<?=$this->login_by=='phone'?"*":""; ?></label>
                        <input class="form-control" type="text" name="phone" value="<?php echo set_value('phone',(isset($row) ? $row->phone : '')); ?>" size="20"/>
                        <?php echo form_error('phone'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('email');?>">
                        <label>Email<?=$this->login_by=='email'?"*":""; ?></label>
                        <input class="form-control" type="text" name="email" value="<?php echo set_value('email',(isset($row) ? $row->email : '')); ?>" size="30"/>
                        <?php echo form_error('email'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('password');?>">
                        <label>Password*</label>
                        <input class="form-control" type="password" name="password" value="<?php echo set_value('password',(isset($row) ? $row->password : '')); ?>" size="20" placeholder="[tidak diubah]" />
                        <?php echo form_error('password'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('passconf');?>">
                        <label>Ulang Password*</label>
                        <input class="form-control" type="password" name="passconf" value="<?php echo set_value('passconf',''); ?>" size="20" placeholder="[tidak diubah]" />
                        <?php echo form_error('passconf'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('address');?>">
                        <label>Alamat</label>
                        <textarea name="address" id="address" rows="3" class="form-control" ><?php echo set_value('address',(isset($row) ? $row->address : '')); ?></textarea>
                        <?php echo form_error('address'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('usertype');?>">
                        <label>Tipe User*</label>
                        <?php echo $html['usertype']; ?>
                        <?php echo form_error('usertype'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('userrole');?>">
                        <label>Role User</label>
                        <?php echo $html['userrole']; ?>
                        <?php echo form_error('userrole'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('status');?>" style="margin-bottom:0px;">
                        <label>Status*</label>
                        <p class="form-control-static"><?php echo $html['status']; ?></p>
                        <?php echo form_error('status'); ?>
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

<script>

$(document).ready(function(e) {
	
	$('.img-preview').click(function(e) {
        $(this).parent().find('input:file').trigger('click');
    });
	
	$('.btn-upload-picture').click(function(e) {
        $(this).parent().parent().parent().find('input:file').trigger('click');
    });
	
	$('.btn-delete-picture').click(function(e) {
		if($('#delete_picture').val()=='0'){
			$('#delete_picture').val('1');
			$(this).html('<i class="fa fa-undo"></i> Batal');
		}else{
			$('#delete_picture').val('0');
			$(this).html('<i class="fa fa-trash"></i> Hapus');
		}
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