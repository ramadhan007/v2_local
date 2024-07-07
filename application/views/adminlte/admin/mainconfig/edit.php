<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
		<a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
    	<?php
		$active_tab = $this->session->userdata('admin/session_mainconfig_tab');
		$active_tab = $active_tab ? $active_tab : "#site";
		?>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li<?=$active_tab=='#site'?' class="active"':'';?>><a data-toggle="tab" href="#site">Konten</a></li>
                <li<?=$active_tab=='#system'?' class="active"':'';?>><a data-toggle="tab" href="#system">Sistem</a></li>
                <li<?=$active_tab=='#notif'?' class="active"':'';?>><a data-toggle="tab" href="#notif">Notifikasi</a></li>
            </ul>
            <form method="post" action="<?php echo $action; ?>">
            <input type="hidden" id="task" name="task" value="" />
            <div class="tab-content">
                <div class="tab-pane fade<?=$active_tab=='#site'?' in active':'';?>" id="site">
                    <h3 style="padding-bottom:15px;">Seting Site</h3>
                    <div class="form-group<?=form_group_error('site_title');?>">
                        <label>Judul Site</label>
                        <input class="form-control" type="text" name="site_title" value="<?php echo set_value('site_title', $config['site_title']); ?>" size="40"/>
                        <?php echo form_error('site_title'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('site_short');?>">
                        <label>Singkatan Site</label>
                        <input class="form-control" type="text" name="site_short" value="<?php echo set_value('site_short', $config['site_short']); ?>" size="40" />
                        <?php echo form_error('site_short'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('meta_keywords');?>">
                        <label>Meta Keywords</label>
                        <input class="form-control" type="text" id="meta_keywords" name="meta_keywords" value="<?php echo set_value('meta_keywords', $config['meta_keywords']); ?>" data-role="tagsinput" />
                        <?php echo form_error('meta_keywords'); ?>
                        <script>
                        
                        $(document).ready(function(e) {
                            $('#meta_keywords').tagsinput({
                                tagClass: 'label label-primary'
                            });
                        });
                        
                        </script>
                    </div>
                    <div class="form-group<?=form_group_error('meta_description');?>">
                        <label>Meta Description</label>
                        <textarea name="meta_description" id="meta_description" style="width:100%; height:100px;" class="form-control" ><?php echo set_value('meta_description', $config['meta_description']); ?></textarea>
                        <?php echo form_error('meta_description'); ?>
                    </div>
                </div>
                <div class="tab-pane fade<?=$active_tab=='#system'?' in active':'';?>" id="system">
                    <h3 style="padding-bottom:15px;">Seting Sistem</h3>
                    <div class="form-group<?=form_group_error('sender_email');?>">
                        <label>Sender Email</label>
                        <input class="form-control" type="text" name="sender_email" value="<?php echo set_value('sender_email', $config['sender_email']); ?>" size="40"/>
                        <?php echo form_error('sender_email'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('sender_name');?>">
                        <label>Sender Name</label>
                        <input class="form-control" type="text" name="sender_name" value="<?php echo set_value('sender_name', $config['sender_name']); ?>" size="81"/>
                        <?php echo form_error('sender_name'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('login_by');?>">
                        <label>Login Dengan</label>
                        <?php echo $html['login_by']; ?>
                        <?php echo form_error('login_by'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('user_activation');?>">
                        <label>User Activation</label>
                        <?php echo $html['user_activation']; ?>
                        <?php echo form_error('user_activation'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('timezone');?>">
                        <label>Time Zone</label>
                        <?php echo $html['timezone']; ?>
                        <?php echo form_error('timezone'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('currency');?>" style="margin-bottom:0px">
                        <label>Default Currency</label>
                        <?php echo $html['currency']; ?>
                        <?php echo form_error('currency'); ?>
                    </div>
                </div>
                <div class="tab-pane fade<?=$active_tab=='#notif'?' in active':'';?>" id="notif">
                    <h3 style="padding-bottom:15px;">Seting Notifikasi</h3>
                    <div class="form-group<?=form_group_error('enable_email');?>">
                        <label>Enable Email</label>
                        <?php echo $html['enable_email']; ?>
                        <?php echo form_error('enable_email'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('enable_whatsapp');?>">
                        <label>Enable Whatsapp</label>
                        <?php echo $html['enable_whatsapp']; ?>
                        <?php echo form_error('enable_whatsapp'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('enable_sms');?>">
                        <label>Enable SMS</label>
                        <?php echo $html['enable_sms']; ?>
                        <?php echo form_error('enable_sms'); ?>
                    </div>
                </div>
            </div>
        	</form>
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
	$('.select2').select2();
	$('.select2').width("100%");
	
	$('.nav-tabs a').on('shown.bs.tab', function(event){
        var x = $(event.target).attr('href');         // active tab
		$.post('<?php echo site_url('admin/session/set/mainconfig_tab'); ?>',{'value':x}, function(data){}, 'text');
    });
});

</script>