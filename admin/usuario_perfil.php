<?php 

// require_once("sys_root.inc.php");
// require_once("$SYS_ROOT/php/knl/db.inc.php");
// require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

// session_start();

// // cia (page title)
// $qry = "SELECT PagesTitle FROM empresa WHERE EmpresaID = 1";
// $title = DbGetFirstFieldValue($qry);
// $title = (strlen($title)) ? $title : "Doxa";

// $app = basename(__FILE__);
// $app_title = 'Perfil de Usuario';
// segVerifyAuth($app);

// $id_user = SessGetUserId();
// // $g_nombre_usuario = GetUserName($id_user, 'NA');
// $a_user = GetUserData($id_user);
// $g_nombre_usuario = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno']);
// $nombre_usuario = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno'] . ' ' . $a_user['ApellidoMaterno']);
// $user_name = $a_user['UserName'];
// $id_perfil = $a_user['UsuarioPerfilId'];

// $perfil = "";
// if (strlen($id_perfil)) {
// 	$qry = "SELECT NombrePerfil FROM seg_usuarioperfil WHERE UsuarioPerfilId = $id_perfil";
// 	$perfil = DbGetFirstFieldValue($qry);
// }

// read data (grid)

// catalogos

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
</head>
<body class="hold-transition sidebar-mini sidebar-collapse text-sm">
	
	<div id="DataModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="form-data" class="form-horizontal" action="" method="post">
					<input type="hidden" id="IdUsuario" name="IdUsuario" value="<?php echo $id_user; ?>">
					<div class="modal-header">
						<h4 class="modal-title">Cambia Password</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label">Contraseña Actual</label>
							<input type="password" name="passwd_actual" id="passwd_actual" class="form-control" placeholder="Contraseña Actual" maxlength="50">
						</div>
						<div class="form-group">
							<label class="control-label text-success">Nueva Contraseña</label>
							<input type="password" name="passwd_nuevo" id="passwd_nuevo" class="form-control" placeholder="Contraseña" maxlength="50">
						</div>
						<div class="form-group">
							<label class="control-label text-success">Confirma Contraseña Nueva</label>
							<input type="password" name="passwd_confirma" id="passwd_confirma" class="form-control" placeholder="Contraseña" maxlength="50">
						</div>
					</div> 
					<div class="modal-footer">
						<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Cambiar</button>
						<button type="button" name="button-cancel" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>


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
      <div class="container-fluid">
        <div class="row">
			<div class="col-md-4">

				<div class="card">
				  <!-- <div class="card-header p-2">
				  </div>-->
				  <!-- /.card-header -->
				  <div class="card-body">

					<div class="form-group">
						<label class="control-label">Usuario</label>
						<input type="text" name="UserName" id="UserName" class="form-control-plaintext" value="<?php echo $user_name; ?>">
					</div>
					<div class="form-group">
						<label class="control-label">Nombre</label>
						<input type="text" name="Nombre" id="Nombre" class="form-control-plaintext" value="<?php echo $nombre_usuario; ?>">
					</div>
					<div class="form-group">
						<label class="control-label">Perfil</label>
						<input type="text" name="Perfil" id="Perfil" class="form-control-plaintext" value="<?php echo $perfil; ?>">
					</div>

					<div class="row">
						<div class="col-md-6">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#DataModal"><i class="fas fa-user-secret"></i>&nbsp;Cambia Password</button>
						</div>
					</div>
				  </div><!-- /.card-body -->
				</div>


			</div>
		</div>
        <!-- /.row -->
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
<!-- Toastr -->
<script src="../vendor/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.min.js"></script>
<!-- Page  JavaScript -->
<script src="usuario_perfil.js?v=1.0.1"></script>
</body>
</html>
