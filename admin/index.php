<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

// session_start();

// // cia (page title)
// $qry = "SELECT PagesTitle FROM empresa WHERE EmpresaID = 1";
// $title = DbGetFirstFieldValue($qry);
// $title = (strlen($title)) ? $title : "Doxa";

// $app = basename(__FILE__);
// $app_title = 'Inicio';
// $index_active = 'active';
// segVerifyAuth($app);

// $id_user = SessGetUserId();
// $g_nombre_usuario = GetUserName($id_user, 'NA');

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../vendor/fontawesome-free/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../vendor/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
  <link rel="apple-touch-icon" sizes="57x57" href="../apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="../apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="../apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="../apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="../apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="../apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="../apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="../apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="../android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="../favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
  <link rel="manifest" href="../manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="../ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
  <style>
	.info-box {
		cursor: pointer;
	}
	.info-box:hover {
		transform: scale(1.05);
	}
  </style>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse text-sm">
<div class="wrapper">
  <!-- Navbar -->
  <?php include('header.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $app_title; ?></h1>
	</section>
    <!-- Main content -->
    <div class="content">
	
		<div class="container h-100">
			<div class="row align-items-center h-100">
				<div class="col-6 mx-auto">
					<div class="text-center">
						<img src="../images/logos/logo_home.png?v=2">
					</div>
				</div>
			</div>
		</div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- footer -->
  <?php include('footer.php'); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.min.js"></script>
<!-- Page  JavaScript -->
<script src="index.js?v=1.001"></script>
</body>
</html>
