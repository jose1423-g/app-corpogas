<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

// cia (page title)
$qry = "SELECT PagesTitle FROM empresa WHERE EmpresaID = 1";
$title = DbGetFirstFieldValue($qry);
$title = (strlen($title)) ? $title : "Doxa";

$app = basename(__FILE__);
$app_title = 'Configuracion FTP';
segVerifyAuth($app);

$id_user = SessGetUserId();
// $g_nombre_usuario = GetUserName($id_user, 'NA');
$a_user = GetUserData($id_user);
$g_nombre_usuario = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno']);
// $nombre_usuario = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno'] . ' ' . $a_user['ApellidoMaterno']);
// $user_name = $a_user['UserName'];
// $id_perfil = $a_user['UsuarioPerfilId'];

// read data (grid)
$a_head_data = array('#', 'Estacion', 'Conf.', 'Servicio', 'IP', 'Usuario', '#pass', 'Puerto', 'Tipo', 'Ruta', 'Estatus', 'Acciones');

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
<!-- daterange picker -->
  <link rel="stylesheet" href="../vendor/daterangepicker/daterangepicker.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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
					<div class="modal-header bg-info">
						<h4 class="modal-title">Configuracion FTP Global</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="control-label">Protocolo</label>
							<select name="FTPServiceType" id="FTPServiceType" class="form-control">
								<option value="FTP">FTP</option>
								<option value="SFTP">SFTP</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">Direccion IP</label>
							<input type="text" name="FTPIp" id="FTPIp" class="form-control" maxlength="200">
						</div>
						<div class="form-group">
							<label class="control-label">Usuario</label>
							<input type="text" name="FTPUser" id="FTPUser" class="form-control" maxlength="100">
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" name="FTPPass" id="FTPPass" class="form-control" maxlength="40">
						</div>
						<div class="form-group">
							<label class="control-label">Puerto</label>
							<input type="text" name="FTPPort" id="FTPPort" class="form-control" maxlength="20">
						</div>
						<div class="form-group">
							<label class="control-label">Hora Envio</label>
							<div class="input-group timepicker" id="FTPSchedule_timepicker" data-target-input="nearest">
							  <input type="text" class="form-control datetimepicker-input" data-target="#FTPSchedule_timepicker" id="FTPSchedule" disabled/>
							  <div class="input-group-append" data-target="#FTPSchedule_timepicker" data-toggle="datetimepicker">
								  <div class="input-group-text"><i class="far fa-clock"></i></div>
							  </div>
							</div>
							<small>Hora a la que esta configurado el proceso automático</small>
						</div>
						<div class="form-group">
							<label class="control-label">Tipo Funcionamiento</label>
							<select name="FTPConnType" id="FTPConnType" class="form-control">
								<option value="1">G500</option>
								<option value="2">General</option>
							</select>
						</div>
						<div class="form-group" id="FTPFolderContainer">
							<label class="control-label">Ruta Destino</label>
							<input type="text" name="FTPFolder" id="FTPFolder" class="form-control" maxlength="100">
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
	
	<div id="DataModalEstacion" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="form-data-est" class="form-horizontal" action="" method="post">
					<input name="IdEstacion" id="IdEstacion" type="hidden">
					<div class="modal-header bg-secondary">
						<h4 class="modal-title">Configuracion FTP por estación</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					
						<div class="form-group">
							<div class="custom-control custom-radio">
								<input class="custom-control-input" type="radio" id="UsarFTPEstacion0" name="UsarFTPEstacion" value="0">
								<label for="UsarFTPEstacion0" class="custom-control-label">Usar configuración global</label>
							</div>
							<div class="custom-control custom-radio">
								<input class="custom-control-input" type="radio" id="UsarFTPEstacion1" name="UsarFTPEstacion" value="1">
								<label for="UsarFTPEstacion1" class="custom-control-label">Usar config especifica de estación</label>
							</div>
							<small id="UsarFTPEstacionHelp" class="form-text text-muted"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Protocolo</label>
							<select name="est_FTPServiceType" id="est_FTPServiceType" class="form-control est_fields">
								<option value="FTP">FTP</option>
								<option value="SFTP">SFTP</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">Direccion IP</label>
							<input type="text" name="est_FTPIp" id="est_FTPIp" class="form-control est_fields" maxlength="200">
						</div>
						<div class="form-group">
							<label class="control-label">Usuario</label>
							<input type="text" name="est_FTPUser" id="est_FTPUser" class="form-control est_fields" maxlength="100">
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" name="est_FTPPass" id="est_FTPPass" class="form-control est_fields" maxlength="40">
						</div>
						<div class="form-group">
							<label class="control-label">Puerto</label>
							<input type="text" name="est_FTPPort" id="est_FTPPort" class="form-control est_fields" maxlength="20">
						</div>
						<div class="form-group">
							<label class="control-label">Hora Envio</label>
							<div class="input-group timepicker" id="est_FTPSchedule_timepicker" data-target-input="nearest">
							  <input type="text" class="form-control datetimepicker-input est_fields_por_ahora_no" data-target="#est_FTPSchedule_timepicker" id="est_FTPSchedule" disabled/>
							  <div class="input-group-append" data-target="#est_FTPSchedule_timepicker" data-toggle="datetimepicker">
								  <div class="input-group-text"><i class="far fa-clock"></i></div>
							  </div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Tipo Funcionamiento</label>
							<select name="est_FTPConnType" id="est_FTPConnType" class="form-control est_fields">
								<option value="1">G500</option>
								<option value="2">General</option>
							</select>
						</div>
						<div class="form-group" id="est_FTPFolderContainer">
							<label class="control-label">Ruta Destino</label>
							<input type="text" name="est_FTPFolder" id="est_FTPFolder" class="form-control est_fields" maxlength="100">
						</div>
					</div> 
					<div class="modal-footer">
						<button type="button" name="button-save-est" id="button-save-est" class="btn btn-sm btn-primary">Guardar</button>
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
				  <div class="card-header bg-info p-2">Configuración Global
				  </div>
				  <!-- /.card-header -->
				  <div class="card-body">

					<div class="form-group">
						<label class="control-label">Protocolo</label>
						<input type="text" name="v_FTPServiceType" id="v_FTPServiceType" class="form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label">Direccion IP</label>
						<input type="text" name="v_FTPIp" id="v_FTPIp" class="form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label">Usuario</label>
						<input type="text" name="v_FTPUser" id="v_FTPUser" class="form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<input type="password" name="v_FTPPass" id="v_FTPPass" class="form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label">Puerto</label>
						<input type="text" name="v_FTPPort" id="v_FTPPort" class="form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label">Hora Envio</label>
						<input type="text" name="v_FTPSchedule" id="v_FTPSchedule" class="form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label">Tipo Funcionamiento</label>
						<input type="text" name="v_FTPConnTypeDesc" id="v_FTPConnTypeDesc" class="form-control-plaintext">
					</div>
					<div class="form-group" id="v_FTPFolderContainer">
						<label class="control-label">Ruta Destino</label>
						<input type="text" name="v_FTPFolder" id="v_FTPFolder" class="form-control-plaintext">
					</div>

					<div class="row">
						<div class="col-md-6">
							<button type="button" class="btn btn-primary" id="button-show-edit" data-toggle="modal" data-target="#DataModal"><i class="fas fa-edit"></i>&nbsp;Editar</button>
						</div>
					</div>
				  </div><!-- /.card-body -->
				</div>


			</div>
			<div class="col-md-8">
				<div class="card">
				  <div class="card-header bg-secondary p-2">
					Configuración por estación
				  </div>
				  <!-- /.card-header -->
				  <div class="card-body">
				<div class="table-responsive" style="overflow-x: hidden;">
					<table id="grid-table" class="display table table-sm table-striped table-bordered table-hover table-condensed" style="width: 100%">
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
<!-- date-range-picker -->
<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../vendor/moment/moment.min.js"></script>
<script src="../vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
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
<script src="ftp-config.js?v=1.0.8"></script>
</body>
</html>
