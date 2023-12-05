<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();


$app = basename(__FILE__);
$app_title = 'Gerentes';
$usuarios_active = 'active';
segVerifyAuth($app);

$a_head_data = array('#', 'Sel', 'E.S', 'Razon Social', 'Gerente', 'Correo de GPV', 'Correo del Supervisor', 'EsActivo');

$qry = "SELECT UsuarioPerfilId, NombrePerfil FROM seg_usuarioperfil";
$a_perfiles = DbQryToArray($qry, true);
$perfiles;
foreach($a_perfiles as $a_pf) {
	$id_perfil_show = $a_pf['UsuarioPerfilId'];
	$nombre_perfil_show = $a_pf['NombrePerfil'];
	$perfiles .= "<option value=\"$id_perfil_show\">$nombre_perfil_show</option>";
}

$qry = "SELECT IdEstacion, EstacionServicio, NoEstacion FROM estaciones";
$a_estaciones = DbQryToArray($qry, true);
$estaciones;
foreach ($a_estaciones as $row) {
    $id = $row['IdEstacion'];
    $nombre = utf8_decode($row['EstacionServicio']);
    $numero = $row['NoEstacion'];
    $estaciones .= "<option value='$id'>$numero $nombre</option>";
}

?>

<?php include('../layouts/main.php'); ?>
<?php include('../layouts/main_content.php') ?>

	<div class="row px-5">
		<div class="col-12">
			<!-- Modal -->
			<div class="modal fade" id="DataModal">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Agregar/Editar Gerente</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body p-0">
							<form id="form-data" class="form-horizontal" action="" method="post">
								<input type="hidden" id="IdUsuario" name="IdUsuario">
								<!-- <input type="hidden" id="IsPasswdMod" name="IsPasswdMod" value="0"> -->
								<div class="modal-body">
									<div class="row">
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold">Usuario</label>
												<input type="text" name="UserName" id="UserName" class="form-control"  maxlength="30" >
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold">Nombre</label>
												<input type="text" name="Nombre" id="Nombre" class="form-control"  maxlength="30" >
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold">Apellido Paterno</label>
												<input type="text" name="ApellidoPaterno" id="ApellidoPaterno" class="form-control"  maxlength="30" >
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold">Apellido Materno</label>
												<input type="text" name="ApellidoMaterno" id="ApellidoMaterno" class="form-control"  maxlength="30" >
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold">Contraseña</label>
												<input type="password" name="passwd" id="passwd" class="form-control"  maxlength="50">
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold">Perfil</label>
												<select class="form-control" name="UsuarioPerfilId_fk" id="UsuarioPerfilId_fk" >
													<option value="">-- Perfil --</option>
												<?php echo $perfiles ?>
												</select>
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold" for="Email">Email</label>
												<input type="email" name="Email" id="Email" class="form-control" >
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold" for="telefono">telefono</label>
												<input type="text" name="telefono" id="telefono" class="form-control" >
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold" for="IdEstacion_fk">Estacion de servicio</label>
												<select class="form-control" name="IdEstacion_fk" id="IdEstacion_fk" >
													<option value="">-- estacion --</option>
												<?php echo $estaciones; ?>
												</select>
											</div>
										</div>
										<div class="col-6">
											<div class="form-group">
												<label class="form-label fw-bold" for="EmailSupervisor">Email supervisor</label>
												<input type="email" name="EmailSupervisor" id="EmailSupervisor" class="form-control" >
											</div>
										</div>
										<div class="form-group">
											<div class="custom-control custom-switch">
												<input type="checkbox" class="custom-control-input" name="EsActivo" id="EsActivo">
												<label class="custom-control-label" for="EsActivo">Usuario Activo</label>
											</div>
										</div>
									</div>
								</div> 
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
									<button type="button" name="button-save" id="btn-save" class="btn btn-sm btn-primary">Guardar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- table -->
		<div class="col-12 conten bg-white mt-3 shadow-sm rounded-2 py-2">			
			<section class="content-header">
				<h1 class="fs-4"><?php echo $app_title; ?></h1>
			</section>
			<div class="table-responsive" style="overflow-x: hidden;">
				<table id="grid-table" class="text-small display table table-bordered table-striped table-bordered table-hover table-condensed table-sm" style="width: 100%">
					<thead class="text-center">
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
			<div class="row">
				<div class="col-sm-6">
					<button type="button" id="btn-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>
				</div>
				<div class="col-sm-6">
				</div>
			</div>
		</div>
	</div>

<?php include('../layouts/main_content_end.php')  ?>  
<!-- footer -->
<?php include('../layouts/footer.php'); ?>

<!-- script -->
	<script src="../js/usuarios.js?=1.001"></script>

<?php include('../layouts/main_end.php'); ?>
