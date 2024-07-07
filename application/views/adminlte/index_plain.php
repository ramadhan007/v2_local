<?php
$template_name = $this->config->item('template_admin');
$template_base = base_url("templates/".$template_name);
?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title><?php echo strip_tags(get_main_config('site_title')); ?> - <?php echo $this->view['doctitle']; ?></title>
  	<!-- Tell the browser to be responsive to screen width -->
  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  	
    <!-- jQuery 3 -->
  	<script src="<?php echo $template_base ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
	<script src="<?php echo $template_base ?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    
    <!-- Bootstrap 3.3.7 -->
	<script src="<?php echo $template_base ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  	<!-- Bootstrap 3.3.7 -->
  	<link rel="stylesheet" href="<?php echo $template_base ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Bootstrap Tags Input -->
    <link href="<?php echo base_url(); ?>assets/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Date Time Picker -->
    <link href="<?php echo base_url(); ?>assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
  	<!-- Font Awesome -->
  	<?php $fa_version_dir = "font-awesome-4.7.0"; ?>
    <link rel="stylesheet" href="<?php echo base_url("assets/font-awesome/$fa_version_dir/css/font-awesome.min.css"); ?>">
  	<!-- Ionicons -->
  	<link rel="stylesheet" href="<?php echo $template_base ?>/bower_components/Ionicons/css/ionicons.min.css">
    <!-- bootstrap datepicker -->
  	<link rel="stylesheet" href="<?php echo $template_base ?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Select2 -->
  	<link rel="stylesheet" href="<?php echo $template_base ?>/bower_components/select2/dist/css/select2.min.css">
  	<!-- Theme style -->
  	<link rel="stylesheet" href="<?php echo $template_base ?>/dist/css/AdminLTE.min.css">
    
    <!-- iCheck -->
  	<!-- <link rel="stylesheet" href="<?php echo $template_base ?>/plugins/iCheck/square/blue.css"> -->
  	
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  	<link rel="stylesheet" href="<?php echo $template_base ?>/dist/css/skins/all-skins.min.css">

  	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  	<!--[if lt IE 9]>
  	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  	<![endif]-->

  	<!-- Google Font -->
    <link rel="stylesheet" href="<?php echo $template_base ?>/dist/css/https_fonts.googleapis.com_cssfamily=Source+Sans+Pro 300,400,600,700,300italic,400italic,600italic.css">
    
    <!-- Custom CSS Template -->
    <link rel="stylesheet" href="<?php echo base_url() ?>css/templates/<?php echo $template_name; ?>.css">
  
  	<!-- Custom JavaScript -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/<?php echo $this->js_list; ?>.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/modal.js"></script>
    
    <script type="text/javascript">

		function Logout(){
			if(confirm('Anda yakin akan logout?')){
				window.location='<?php echo site_url('admin/login/logout'); ?>';
			}
		}
		
	</script>
  
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background-color:#ecf0f5">
<div class="wrapper">

  
  <!-- Left side column. contains the logo and sidebar -->
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px;">
    <!-- Main content -->
    <section class="content" style="padding:0px;">
      <div class="row">
        <div class="col-md-12" style="overflow:hidden">
          
        	<?php $this->load->view($template_name."/".$this->view['content']); ?>
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- bootstrap datepicker -->
<?php include('assets/modal.php'); ?>

<script src="<?php echo $template_base ?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Select2 -->
<script src="<?php echo $template_base ?>/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo $template_base ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $template_base ?>/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $template_base ?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $template_base ?>/dist/js/demo.js"></script>
<!-- CK Editor -->
<script src="<?php echo $template_base ?>/bower_components/ckeditor/ckeditor.js"></script>

<!-- Bootstrap Tags Input -->
<script src="<?php echo base_url(); ?>assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap-tagsinput/bootstrap-tagsinput-angular.min.js"></script>

<!-- Bootstrap Date Time Picker -->
<script src="<?php echo base_url(); ?>assets/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>

<!-- loadingoverlay -->
<script src="<?php echo base_url(); ?>assets/loadingoverlay/loadingoverlay.min.js"></script>
<script src="<?php echo base_url(); ?>js/templates/<?php echo $template_name; ?>.js"></script>

<!-- underscore -->
<script src="<?php echo base_url(); ?>assets/underscore/underscore-min.js"></script>

<!-- JQuery Form -->
<script src="<?php echo base_url(); ?>assets/jquery/jquery.form.js"></script>

<!-- iCheck -->
<!-- <script src="<?php echo $template_base ?>/plugins/iCheck/icheck.min.js"></script>
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
	$('[data-toggle="tooltip"]').tooltip();
});

</script>

</ body>
</html>
