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
$app_title = 'Usuarios';
// $usuarios_active = 'active';
// segVerifyAuth($app);

// $id_user = SessGetUserId();
// $g_nombre_usuario = GetUserName($id_user, 'NA');

// // read data (grid)
$a_head_data = array('#', 'Usuario', '#ApellidoPaterno', '#ApellidoMaterno', '#Nombre', 'Nombre', '#UsuarioPerfilId', 'Perfil', '#EsActivo', 'Estatus', '#passwd', 'Acciones');
// $a_grid_data = array();

// // catalogos
// $where_admin = " AND EsAdmin = 0";
// if (strlen($id_user)) {
// 	$qry = "SELECT t2.EsAdmin
// 			FROM seg_usuarios t1
// 			LEFT JOIN seg_usuarioperfil t2 ON t1.UsuarioPerfilId = t2.UsuarioPerfilId
// 			WHERE t1.IdUsuario = $id_user";
// 	$es_admin = DbGetFirstFieldValue($qry);
// 	if ($es_admin == 1) {
// 		$where_admin = "";
// 	}
// }
// $qry = "SELECT UsuarioPerfilId, NombrePerfil FROM seg_usuarioperfil WHERE EsActivo = 1 $where_admin ORDER BY Nivel, NombrePerfil";
// $a_perfiles = DbQryToArray($qry, true);


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
  <!-- DataTables -->
  <link rel="stylesheet" href="../vendor/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../vendor/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini sidebar-collapse text-sm">
	
	<div id="DataModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="form-data" class="form-horizontal" action="" method="post">
					<input type="hidden" id="IdUsuario" name="IdUsuario">
					<input type="hidden" id="IsPasswdMod" name="IsPasswdMod" value="0">
					<div class="modal-header">						
						<h4 class="modal-title">Agregar/Editar Usuario</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label">Usuario</label>
							<input type="text" name="UserName" id="UserName" class="form-control" placeholder="Usuario" maxlength="30" required>
						</div>
						<div class="form-group">
							<label class="control-label">Nombre</label>
							<input type="text" name="Nombre" id="Nombre" class="form-control" placeholder="Nombre" maxlength="30" required>
						</div>
						<div class="form-group">
							<label class="control-label">Apellido Paterno</label>
							<input type="text" name="ApellidoPaterno" id="ApellidoPaterno" class="form-control" placeholder="Apellido Paterno" maxlength="30" required>
						</div>
						<div class="form-group">
							<label class="control-label">Apellido Materno</label>
							<input type="text" name="ApellidoMaterno" id="ApellidoMaterno" class="form-control" placeholder="Apellido Materno" maxlength="30">
						</div>
						<div class="form-group">
							<label class="control-label">Contraseña</label>
							<input type="password" name="passwd" id="passwd" onchange="$('#IsPasswdMod').val(1);" class="form-control" placeholder="Contraseña" maxlength="50">
						</div>
						<div class="form-group">
							<label class="control-label">Perfil</label>
							<select class="form-control" name="UsuarioPerfilId" id="UsuarioPerfilId">
								<option value="">-- Perfil --</option>
							<?php
								foreach($a_perfiles as $a_pf) {
									$id_perfil_show = $a_pf['UsuarioPerfilId'];
									$nombre_perfil_show = $a_pf['NombrePerfil'];
									echo "<option value=\"$id_perfil_show\">$nombre_perfil_show</option>";
								}
							?>
							</select>
						</div>
					    <div class="form-group">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" name="EsActivo" id="EsActivo">
								<label class="custom-control-label" for="EsActivo">Usuario Activo</label>
							</div>
					    </div>
					</div> 
					<div class="modal-footer">
						<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button>
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
			<div class="col-lg-12">
				<div class="table-responsive" style="overflow-x: hidden;">
					<table id="grid-table" class="display table table-striped table-bordered table-hover table-condensed" style="width: 100%">
						<thead>
							<tr>
							<?php
								foreach($a_head_data as $head_data) {
									echo "<th>$head_data</th>";
								}
							?>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<button type="button" id="button-add" title="Agregar" class="btn btn-primary"><span class="fa fas fa-plus"></span>Nuevo</button>
				</div>
				<div class="col-sm-6">
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
<!-- DataTables -->
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Toastr -->
<script src="../vendor/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.min.js"></script>
<!-- Page  JavaScript -->
<script src="usuarios.js?v=1.0.1"></script>
</body>
</html>
