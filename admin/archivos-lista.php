<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

// cia (page title)
$qry = "SELECT PagesTitle FROM empresa WHERE EmpresaID = 1";
$title = DbGetFirstFieldValue($qry);
$title = (strlen($title)) ? $title : "Doxa";

$app = basename(__FILE__);
$app_title = 'Lista de archivos';
segVerifyAuth($app);

$id_user = SessGetUserId();
// $g_nombre_usuario = GetUserName($id_user, 'NA');
$a_user = GetUserData($id_user);
$g_nombre_usuario = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno']);
// $nombre_usuario = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno'] . ' ' . $a_user['ApellidoMaterno']);
// $user_name = $a_user['UserName'];
// $id_perfil = $a_user['UsuarioPerfilId'];

// read data (grid)
$a_head_data = array('#', 'Estacion', 'Archivo', 'Fecha Recib', 'Hora Recib', 'Dir. IP', 'Estatus Envio', 'Fecha Envio', 'Hora Envio', '#Mensaje', '#MensajeEnvio', '');

// catalogos
// estaciones
$html_estaciones = "<option>Todas</option>";
$qry = "SELECT IdEstacion, PL FROM estaciones ORDER BY PL";
$a_estaciones = DbQryToArray($qry, true);
foreach($a_estaciones as $a_estacion) {
	$id = $a_estacion['IdEstacion'];
	$desc = $a_estacion['PL'];
	$html_estaciones .= "<option value=\"$id\">$desc</option>";
}

$a_estatus = array(0 => 'Pendiente enviar', 1 => 'Envio fallido', 2 => 'Enviado');
$html_estatus = "<option>Todos</option>";
foreach($a_estatus as $id => $desc) {
	$html_estatus .= "<option value=\"$id\">$desc</option>";
}

// fecha desde / hasta default
$a_today = DtDbToArray(DtDbToday());
$first_day_month_db = $a_today['y'] . $a_today['m'] . '01'; // primer dia del mes
$fecha_desde = DtDbToShow($first_day_month_db);
$fecha_hasta = DtDbToShow(DtDbToday());

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
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../vendor/select2/css/select2.min.css">
  <link rel="stylesheet" href="../vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
				<div class="modal-header" id="enviando-header">
					Envio de archivos
				</div>
				<div class="modal-body" id="enviando-body">
					Se enviarán todos los archivos pendientes de envio o con envio fallido
				</div>
				<div class="modal-footer" id="enviando-footer">
					<button type="button" name="button-send" id="button-send" class="btn btn-sm btn-primary">Enviar</button>
					<button type="button" name="button-cancel" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- aqui voy, agregando un modal para consultar el detalle y enviar el archivo -->
	<div id="DataModalFile" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					Datos de archivo
				</div>
				<div class="modal-body">
					<input type="hidden" name="v_IdFile" id="v_IdFile">
					<div class="form-group">
						<label class="control-label control-label-sm">Estacion</label>
						<input type="text" name="v_PL" id="v_PL" class="form-control-sm form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label control-label-sm">Archivo</label>
						<input type="text" name="v_FileName" id="v_FileName" class="form-control-sm form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label control-label-sm">Fecha/Hora Recibido</label>
						<input type="text" name="v_Fecha" id="v_Fecha" class="form-control-sm form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label control-label-sm">IP Origen</label>
						<input type="text" name="v_IPOrigen" id="v_IPOrigen" class="form-control-sm form-control-plaintext">
					</div>
					<div class="form-group">
						<label class="control-label control-label-sm">Estatus Envio</label>
						<span class="form-control-plaintext" id="v_EstatusEnvio"></span>
						<small class="text-muted" id="v_MensajeEnvio"></small>
					</div>
				</div>
				<div class="modal-footer">
				<button type="button" name="button-send-file" id="button-send-file" class="btn btn-sm btn-primary">Enviar</button>
					<button type="button" name="button-cancel-file" id="button-cancel-file" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
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
	    <!-- filtro -->
        <div class="row">
          <div class="col-md-12">
            <div class="card collapse show" id="collapsible-card">
				  <div class="card-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="s_Concepto" class="col-form-label col-form-label-sm">Archivo</label>
									<input type="search" class="form-control form-control-sm input_text" name="s_FileName" id="s_FileName">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="s_IdEstacion" class="col-form-label col-form-label-sm">Estacion</label>
								<select class="form-control form-control-sm" id="s_IdEstacion">
									<?php echo $html_estaciones; ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<label class="col-form-label col-form-label-sm">Fecha Recib.Desde</label>
								<div class="input-group input-group-sm date" id="datetimepicker4" data-target-input="nearest">
									<input type="text" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker" name="s_FechaDesde" id="s_FechaDesde"  value="<?php echo $fecha_desde; ?>"/>
									<div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
									</div>
								</div>
						</div>
						<div class="col-md-2">
							<label class="col-form-label col-form-label-sm">Fecha Recib.Hasta</label>
								<div class="input-group input-group-sm date" id="datetimepicker5" data-target-input="nearest">
									<input type="text" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker5" data-toggle="datetimepicker" name="s_FechaHasta" id="s_FechaHasta" value="<?php echo $fecha_hasta; ?>"/>
									<div class="input-group-append" data-target="#datetimepicker5" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
									</div>
								</div>
						</div>
						<div class="col-md-2">
							<label class="col-form-label col-form-label-sm">Fecha Envio</label>
								<div class="input-group input-group-sm date" id="datetimepicker6" data-target-input="nearest">
									<input type="text" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker6" data-toggle="datetimepicker" name="s_FechaEnvio" id="FechaEnvio" value="<?php echo $fecha_hasta; ?>"/>
									<div class="input-group-append" data-target="#datetimepicker6" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
									</div>
								</div>
						</div>
						<div class="col-md-1">
							<label class="col-form-label col-form-label-sm">Estatus</label>
							<div class="input-group input-group-sm">
								<select class="form-control form-control-sm" name="s_EstatusEnvio" id="s_EstatusEnvio">
									<?php
										echo $html_estatus;
									?>
								</select>
								<span class="input-group-append">
									<button class="btn btn-info" id="button-search"><i class="fa fa-filter"></i></button>
								</span>
							</div>
						</div>
						<button class="btn btn-primary" type="button" id="button-show-send"><i class="fa fa-paper-plane"></i> Enviar pendientes</button>
					</div>

					<div class="float-right">
						<i class="fa fa-chevron-up" title="Ocultar cuadro de b&uacute;squeda" style="cursor: pointer;" id="icon_collapse_filter"></i>
					</div>
				  </div>
            </div>
			
			<div class="row">
			<div class="col-md-11">
			</div>
			<div class="col-md-1">
			<div class="card card-body collapse" id="collapse-show-filter" style="padding: 1px;">
				<div class="float-right">
					<i class="fa fa-chevron-down" title="Mostrar cuadro de b&uacute;squeda" style="cursor: pointer;" id="icon_collapse_show_filter"></i>
				</div>
			</div>
			</div>
			</div>
			
          </div>
          <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
	  
        <div class="row">
			<div class="col-md-12">
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
<script src="../vendor/moment/moment-with-locales.js"></script>
<!-- Select2 -->
<script src="../vendor/select2/js/select2.full.min.js"></script>
<script src="../vendor/select2/js/i18n/es.js"></script>
<!-- DataTables -->
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Toastr -->
<script src="../vendor/toastr/toastr.min.js"></script>
<!-- date-range-picker -->
<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.min.js"></script>
<!-- Page  JavaScript -->
<script src="archivos-lista.js?v=1.0.4"></script>
</body>
</html>
