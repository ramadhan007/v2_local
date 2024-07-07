<?php
$template_name = $this->config->item('template_admin');

// echo "<!-- ".$_SERVER['HTTP_REFERER']." -->";

?>

<div class="login-box" style="margin-top:0px">
  <div class="login-logo" style="margin-bottom:0px">
    <a href="index2.html"><span style="font-size:smaller">User Login</span><br /><strong><?php echo get_main_config('site_title'); ?></strong></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"></p>

    <form action="<?php echo $form_action; ?>" method="post" onSubmit="return doLogin(this);">
      <div class="form-group has-feedback">
        <input type="text" id="login" name="login"  class="form-control" placeholder="Username" value="<?php echo $login; ?>" required="required">
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>" required="required">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div>
      	<p id="p_login_message" class="login-box-msg" style="display:none; padding-left:0px; padding-right:0px; color:#F55">
    	
		</p>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input name="remember" type="checkbox" value="yes" <?php echo $checked; ?>> Ingat login saya
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          	<button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i>&nbsp;Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    
    <div style="margin-top:5px">
    	<a href="#">Lupa password</a><br>
    	<a href="<?=site_url($this->config->item("admin")."/register");?>" class="text-center">Pendaftaran Pengguna</a>
	</div>

    <!-- <p id="p_login_message" class="login-box-msg" style="display:none; padding-left:0px; padding-right:0px;">
    	
    </p> -->
    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script>

function doLogin(objform){
	$('#p_login_message').html('');
	$('#p_login_message').css('display','none');
	$(objform).find('.login_imgloading').css('visibility','visible');
	
	$('#p_login_message').html('Checking credential <i class="fa fa-spinner fa-spin"></i>');
	$('#p_login_message').css('display','inline');
	
	$.post('<?=site_url($this->config->item('admin').'/login/doLogin'); ?>',
		{
			'login' : $(objform).find("input[name='login']").val(),
			'password' : $(objform).find("input[name='password']").val(),
			'remember' : ($(objform).find("input[name='remember']").is(":checked") ? 'yes' : ''),
		},
		function(data){
			// console.log(data);
			// return false;
			if(data.status=='1'){
				window.location.href = '<?=site_url($this->config->item('admin').'/report/dashboard');?>';
			}else{
				$('#p_login_message').html(data.message);
				$('#p_login_message').css('display','inline');
			}
			$(objform).find('.login_imgloading').css('visibility','hidden');
		}, 'json');
	return false;
}

</script>