<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-primary" href="javascript:submitTask('edit1');"><i class="fa fa-edit"></i> Edit</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
    	<div class="nav-tabs-custom">
			<?php
            $active_tab = $this->session->userdata('admin/session_mainconfig_tab');
            $active_tab = $active_tab ? $active_tab : "#site";
            ?>
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
                    <div class="form-group">
                        <label>Judul Site</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['site_title']; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Singkatan Site</label>
                        <p class="form-control-static"><?php echo $config['site_short']; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <p class="form-control-static" style="line-height:200%; height:unset">
                            <?php
                            $ar_keyword = explode(",",$config['meta_keywords']);
                            foreach($ar_keyword as $keyword):
                            ?>
                            <span class="label label-primary" style="font-size:unset;"><?=$keyword;?></span>
                            <?php
                            endforeach;
                            ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label>Meta Description</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['meta_description']; ?></p>
                    </div>
                </div>
                <div class="tab-pane fade<?=$active_tab=='#system'?' in active':'';?>" id="system">
                    <h3 style="padding-bottom:15px;">Seting Sistem</h3>
                    <div class="form-group">
                        <label>Sender Email</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['sender_email']; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Sender Name</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['sender_name']; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Login Dengan</label>
                        <p class="form-control-static" style="height:unset"><?php echo ucfirst($config['login_by']); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Aktivasi Pengguna</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['user_activation']=='1' ? 'Ya' : 'Tidak'; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Time Zone</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['timezone']; ?></p>
                    </div>
                    <div class="form-group" style="margin-bottom:0px">
                        <label>Default Currency</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['currency']; ?></p>
                    </div>
                </div>
                <div class="tab-pane fade<?=$active_tab=='#notif'?' in active':'';?>" id="notif">
                    <h3 style="padding-bottom:15px;">Seting Notifikasi</h3>
                    <div class="form-group">
                        <label>Enable Email</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['enable_email']=='1' ? 'Ya' : 'Tidak'; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Enable Whatsapp</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['enable_whatsapp']=='1' ? 'Ya' : 'Tidak'; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Enable SMS</label>
                        <p class="form-control-static" style="height:unset"><?php echo $config['enable_sms']=='1' ? 'Ya' : 'Tidak'; ?></p>
                    </div>
                </div>
            </div>
            </form>
		</div>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:-15px">
		<a class="btn btn-primary" href="javascript:submitTask('edit1');"><i class="fa fa-edit"></i> Edit</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<script>

$(document).ready(function(e) {
	$('.nav-tabs a').on('shown.bs.tab', function(event){
        var x = $(event.target).attr('href');         // active tab
		$.post('<?php echo site_url('admin/session/set/mainconfig_tab') ?>',{'value':x}, function(data){}, 'text');
    });
});

</script>