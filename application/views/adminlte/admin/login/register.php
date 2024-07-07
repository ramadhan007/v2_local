<?php
$template_name = $this->config->item('template_admin');
?>

<div class="login-box" style="margin-top:0px">
  <div class="login-logo" style="margin-bottom:0px">
    <a href="index2.html"><span style="font-size:smaller">Pendaftaran Pengguna</span><br /><strong><?php echo get_main_config('site_title'); ?></strong></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"></p>

    <form action="<?php echo $form_action; ?>" method="post" onSubmit="return doRegister(this);">
      
      <div class="form-group has-feedback">
        <input id="name" name="name" type="text" class="form-control" placeholder="Nama" value="" required="required">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="phone" name="phone" type="tel" class="form-control" placeholder="No HP / Whatsapp" value="" required="required">
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="password" name="password" type="password" class="form-control" placeholder="Password" value="" required="required">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="passconf" name="passconf" type="password" class="form-control" placeholder="Konfirmasi Password" value="" required="required">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <?=$html['usertype'];?>
        <p id="p_info_usertype"></p>
      </div>
      <div id="div_sekolah_id" class="form-group has-feedback" style="display:none">
        <?=$html['sekolah_id'];?>
      </div>
      <div id="div_nik" class="form-group has-feedback" style="display:none">
        <input id="nik" name="nik" type="text" class="form-control" placeholder="NIP / NIS" value="">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      
      <div>
      	<p id="p_login_message" class="login-box-msg" style="display:none; padding-left:0px; padding-right:0px; color:#F55">
    	
		</p>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input name="agree" type="checkbox" value="yes" <?=(set_value('agree','')=='yes' ? 'checked="checked"' : '');?>> Saya setuju terma &amp; syarat
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          	<button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-user-plus"></i>&nbsp;Daftar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    
    <div style="margin-top:5px">
    	<a href="<?=site_url($this->config->item("admin")."/login");?>" class="text-center">Saya sudah punya akun</a>
   	</div>

    <!-- <p id="p_login_message" class="login-box-msg" style="display:none; padding-left:0px; padding-right:0px;">
    	
    </p> -->
    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script>

$(document).ready(function(e) {
	$('#usertype').change(function(e) {
		var usertype = parseInt($(this).val());
        if(usertype>3){
			$('#div_sekolah_id').css('display','block');
			$('#sekolah_id').select2();
			$('#p_info_usertype').html('');
			$('#div_nik').css('display','block');
			if(usertype==5){
				$('#nik').attr('placeholder','Nomor Induk Siswa');
			}else{
				$('#nik').attr('placeholder','Nomor Induk Pegawai');
			}
		}else{
			$('#div_sekolah_id').css('display','none');
			if(usertype==3){
				$('#p_info_usertype').html('Sebagai Admin Sekolah, Anda berkewajiban mendaftarkan sekolah Anda setelah Anda login');
			}else{
				$('#p_info_usertype').html('');
			}
			$('#div_nik').css('display','none');
		}
    });
    $('#sekolah_id').select2();
	$('#sekolah_id').on('select2:select', function (e) {
		alert($(this).val());
	});
});

function doRegister(objform){
	$('#p_login_message').html('');
	$('#p_login_message').css('display','none');
	$(objform).find('.login_imgloading').css('visibility','visible');
	$.post('<?=site_url($this->config->item('admin').'/register/doRegister'); ?>',
		{
			'name' : $(objform).find("input[name='name']").val(),
			'phone' : $(objform).find("input[name='phone']").val(),
			'password' : $(objform).find("input[name='password']").val(),
			'passconf' : $(objform).find("input[name='passconf']").val(),
			'usertype' : $(objform).find("select[name='usertype']").val(),
			'sekolah_id' : $(objform).find("select[name='sekolah_id']").val(),
			'nik' : $(objform).find("input[name='nik']").val(),
			'agree' : ($(objform).find("input[name='agree']").is(":checked") ? 'yes' : ''),
		},
		function(data){
			if(data.status=='1'){
				showInfo('Pendaftaran Berhasil','Pendaftaran Anda telah berhasil, silahkan login!', "window.location.href='<?=site_url($this->config->item('admin').'/login');?>'")
			}else{
				$('#p_login_message').html(data.message);
				$('#p_login_message').css('display','inline');
			}
			$(objform).find('.login_imgloading').css('visibility','hidden');
		}, 'json');
	return false;
}

</script>