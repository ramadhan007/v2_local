<?php
$template_name = $this->config->item('template_admin');
$template_base = base_url("templates/".$template_name);
$login_data = $this->session->userdata('login_data_admin');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=strip_tags(get_main_config('site_title')); ?> - <?=$this->view['doctitle']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- jQuery 3 -->
    <script src="<?=$template_base ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
	<script src="<?=$template_base ?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    
    <!-- Bootstrap 3.3.7 -->
    <script src="<?=$template_base ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?=$template_base ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Bootstrap Tags Input -->
    <link href="<?=base_url(); ?>assets/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Date Time Picker -->
    <link href="<?=base_url(); ?>assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
    <!-- Font Awesome -->
    <?php $fa_version_dir = "font-awesome-4.7.0"; ?>
    <link rel="stylesheet" href="<?=base_url("assets/font-awesome/$fa_version_dir/css/font-awesome.min.css"); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=$template_base ?>/bower_components/Ionicons/css/ionicons.min.css">
    <!-- daterange picker -->
  	<link rel="stylesheet" href="<?=$template_base ?>/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
  	<link rel="stylesheet" href="<?=$template_base ?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Select2 -->
  	<link rel="stylesheet" href="<?=$template_base ?>/bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=$template_base ?>/dist/css/AdminLTE.min.css">
    
    <!-- iCheck -->
  	<!-- <link rel="stylesheet" href="<?=$template_base ?>/plugins/iCheck/square/blue.css"> -->
    
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=$template_base ?>/dist/css/skins/skin-blue.min.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Google Font -->
    <link rel="stylesheet" href="<?=$template_base ?>/dist/css/https_fonts.googleapis.com_cssfamily=Source+Sans+Pro 300,400,600,700,300italic,400italic,600italic.css">
    
    <!-- Custom CSS Template -->
    <link rel="stylesheet" href="<?=base_url() ?>css/templates/<?=$template_name; ?>.css">
  
  	<!-- Custom JavaScript -->
    <script type="text/javascript" src="<?=base_url(); ?>js/custom.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>js/<?=$this->js_list; ?>.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>js/modal.js"></script>
    
    <script type="text/javascript">

		/* function Logout(){
			if(confirm('Anda yakin akan logout?')){
				window.location='<?=site_url($this->config->item('admin').'/login/logout'); ?>';
			}
		} */
		
		function Logout(){
			getConfirm('Konfirmasi Logout', 'Anda yakin akan logout?', "doLogout();");
		}
		
		function doLogout(){
			window.location = '<?=site_url($this->config->item('admin').'/login/logout'); ?>';
		}
		
	</script>
    
    <style>
	
	.sidebar-form .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
		padding: 4px 12px;
		height: 30px;
		font-size:12px;
	}
	
	/* .select2-container .select2-dropdown .select2-search, .select2-container .select2-dropdown .select2-results {
		font-size:12px;
	} */
	
	.font12px{
		font-size:12px;
	}
	
	</style>
  
</head>
<body class="hold-transition <?php $class = $this->session->userdata('admin/session_body_class'); echo $class ? $class : "skin-blue sidebar-mini"; ?>">	<!-- show collapse: skin-blue sidebar-mini sidebar-collapse -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?=site_url('admin'); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><?=get_main_config('site_short'); ?></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?=get_main_config('site_title'); ?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <!-- <span style="padding-left:10px; font-weight:bold; font-size:14px"><?=$this->view['toptitle'];?></span> -->
      </a>
      
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="<?=base_url(); ?>" target="_blank" title="Visit Front End">
              <i class="fa fa-globe"></i>
            </a>
          </li>
          
          <?php include("assets/notification.php"); ?>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=fix_base_url($login_data['user']->picture); ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?=$login_data['user']->name; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?=fix_base_url($login_data['user']->picture); ?>" class="img-circle" alt="User Image">

                <p>
                  <?=$login_data['user']->name; ?> - <?=$login_data['user']->usertype1; ?>
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?=site_url('admin/user/profile');?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="javascript:Logout();" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=fix_base_url($login_data['user']->picture); ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=$login_data['user']->name; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> <?=$login_data['user']->usertype1; ?></a>
        </div>
        
      </div>
      
	<script type="text/javascript">
	  
	$(document).ready(function(e) {
		$('#active_sekolah_id').select2();
		$("#active_sekolah_id").select2({ selectCssClass: "font12px", dropdownCssClass: "font12px" });
		$('#active_sekolah_id').on('select2:select', function (e) {
			document.getElementById('frm_sekolah_id').submit();
		});
        /* $('#active_sekolah_id').change(function(e) {
            document.getElementById('frm_sekolah_id').submit();
        }); */
    });
	  
	</script>
      
      <!-- admin menu -->
      <?php require_once('assets/menu.php'); ?>
      
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      	<?php
        $this->view['icon'] = get_controller_icon($this->controller);
		?>
        <?php if($this->view['icon']): ?><i class="fa <?=$this->view['icon'];?>"></i>&nbsp;<?php endif; ?><?=$this->view['toptitle']; ?>
        <small id="subtitle" style="display:<?=($this->view['subtitle'] ? 'inline' : 'none');?>"><?=$this->view['subtitle']; ?></small>
      </h1>
      <ol class="breadcrumb">
        <?php
	  	$sess_cookie_name = $this->config->item('sess_cookie_name');
		if(isset($_SESSION[$sess_cookie_name.'_'.'breadcrumb'])):
			?>
            <li><a href="<?=site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <?php
			$ar_breadcrumb = $_SESSION[$sess_cookie_name.'_'.'breadcrumb'];
			$i = 0;
			foreach($ar_breadcrumb as $breadcrumb):
				$i++;
				if($i<count($ar_breadcrumb)):
      			?>
        		<li><a href="<?=$breadcrumb['link']; ?>"><?=$breadcrumb['text']; ?></a></li>
                <?php else: ?>
        		<li class="active"><?=$breadcrumb['text']; ?></li>
                <?php endif; ?>
        <?php
        	endforeach;
		else:
		?>
        	<li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
        <?php
		endif;
		?>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          
        	<?php $this->load->view($template_name."/".$this->view['content']); ?>
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer> -->

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  
<!-- List Item Modal -->
<div class="modal autoheight fade" id="notifModal">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="notifModalTitle" class="modal-title"></h4>
            </div>
            <div id="notifModalBody" class="modal-body">
                <p>
                Ini adalah body
                </p>
            </div>
            <div class="modal-footer">
            	<button id="notifMOdalBtnFollowUp" type="button" onClick="notifFollowUp();" data-id="" class="btn btn-success">Sudah Saya Follow Up</button>
          		<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
	        </div>
        </div>
    </div>
</div>
  
</div>
<!-- ./wrapper -->

<!-- date-range-picker -->

<?php include('assets/modal.php'); ?>

<script src="<?=$template_base ?>/bower_components/moment/min/moment.min.js"></script>
<script src="<?=$template_base ?>/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="<?=$template_base ?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Select2 -->
<script src="<?=$template_base ?>/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- Slimscroll -->
<script src="<?=$template_base ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?=$template_base ?>/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?=$template_base ?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=$template_base ?>/dist/js/demo.js"></script>
<!-- CK Editor -->
<script src="<?=$template_base ?>/bower_components/ckeditor/ckeditor.js"></script>

<!-- Bootstrap Tags Input -->
<script src="<?=base_url(); ?>assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script src="<?=base_url(); ?>assets/bootstrap-tagsinput/bootstrap-tagsinput-angular.min.js"></script>

<!-- Bootstrap Date Time Picker -->
<script src="<?=base_url(); ?>assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>

<!-- loadingoverlay -->
<script src="<?=base_url(); ?>assets/loadingoverlay/loadingoverlay.min.js"></script>
<script src="<?=base_url(); ?>js/templates/<?=$template_name; ?>.js"></script>

<!-- underscore -->
<script src="<?=base_url(); ?>assets/underscore/underscore-min.js"></script>

<!-- JQuery Form -->
<script src="<?=base_url(); ?>assets/jquery/jquery.form.js"></script>

<!-- iCheck -->
<!-- <script src="<?=$template_base ?>/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script> -->

<script>

$(document).ready(function(e) {
    $('body').on('addClass removeClass', function (e, oldClass, newClass) {
		if((oldClass=='skin-blue sidebar-mini' && newClass=='skin-blue sidebar-mini sidebar-collapse') || (oldClass=='skin-blue sidebar-mini sidebar-collapse' && newClass=='skin-blue sidebar-mini'))
		{
			$.post('<?=base_url('admin/session/set/body_class');?>',{'value':newClass}, function(data){}, 'text');
		}
	});
	$('[data-toggle="tooltip"]').tooltip();
});

</script>

</ body>
</html>
