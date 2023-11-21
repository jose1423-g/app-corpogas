<?php 


$title = "Acceso Denegado";
$title_app = "Acceso Denegado";
$error_alert = "<strong>Acceso Denegado</strong>No tiene acceso a esta aplicacion <button onclick=\"window.history.back();\" class=\"btn btn-warning\">Regresar</button>";
$error_alert = "<div class=\"alert alert-warning\" role=\"alert\">$error_alert</div>";
$user_data = array();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../vendor/fontawesome-free/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../vendor/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.min.css">

</head>
<body class="hold-transition sidebar-mini sidebar-collapse text-sm">
<div class="wrapper">

  <!-- Header -->
  <?php include('header.php'); ?>
  <!-- Left side column. contains the logo and sidebar -->

  <!-- Starts Main Page -->
  <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title_app; ?>
        <!--<small>Preview</small> -->
      </h1>
	  <?php echo $error_alert; ?>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-9 form1">
          <!-- general form elements -->
          <div class="box box-primary">
              <div class="box-body">
			  </div>
			</div>
          <!-- /.box -->
        </div>
        <div class="col-md-3 form2" id="advert_div">
        </div>
      </div>
      <!-- /.row -->
    </section>
  </div>
  <!-- /.content-wrapper -->
  <?php include('footer.php'); ?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<div class="modal modal-loading">
</div>
<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.min.js"></script>

</body>
</html>