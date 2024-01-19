<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();


$app = basename(__FILE__);
$app_title = 'Usuarios';
$usuarios_active = 'active';
segVerifyAuth($app);

$id_user = SessGetUserId();

// $a_head_data = array('#', 'Sel', 'E.S', 'Razon Social', 'Gerente', 'Correo de GPV', 'Correo del Supervisor', 'EsActivo');
$a_head_data = array('#', 'Sel', 'Usuario', 'Perfil', 'Correo de GPV', 'EsActivo');
$a_head_data_2 = array( 'id', 'Sel','Estacion','No Estacion');

$qry = "SELECT UsuarioPerfilId, NombrePerfil FROM seg_usuarioperfil";
$a_perfiles = DbQryToArray($qry, true);
$perfiles;
foreach($a_perfiles as $a_pf) {
	$id_perfil_show = $a_pf['UsuarioPerfilId'];
	$nombre_perfil_show = $a_pf['NombrePerfil'];
	$perfiles .= "<option value=\"$id_perfil_show\">$nombre_perfil_show</option>";
}

$qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = $id_user";
$perfil  =  DbGetFirstFieldValue($qry);
if ($perfil == 13) {
	$btn_save = '';
	$btn_new = '';
} else if ($perfil == 16) {
	$btn_save = '<button type="button" id="btn-save" class="btn btn-sm btn-primary">Guardar</button>';
	$btn_new = '<button type="button" id="btn-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
} else if ($perfil == 12) {
	$btn_save = '<button type="button" id="btn-save" class="btn btn-sm btn-primary">Guardar</button>';
	$btn_new = '<button type="button" id="btn-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
}

?>

<?php include('../layouts/main.php'); ?>
<?php include('../layouts/main_content.php') ?>

	<div class="row px-5 mb-100">
		<div class="col-12">
			<!-- Modal -->
			<div class="modal fade" id="DataModal">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Agregar/Editar Usuarios</h5>
							<button type="button" class="btn-close" id="btn-x-close"></button>
						</div>
						<div class="modal-body p-0">
							<form id="form-data" class="form-horizontal" action="" method="post">
								<input type="hidden" id="IdUsuario" name="IdUsuario" value="-1">
								<input type="hidden" value="showEstation" id="op" name="op" DISABLED>
                				<input type="hidden" name="s_is_show_all" id="s_is_show_all" DISABLED>    
								<div class="modal-body">
									<div class="row">
										<div class="row col-6 h-75">
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
											<div class="form-group col-6">
												<div class="custom-control custom-switch">
													<input type="checkbox" class="custom-control-input" name="EsActivo" id="EsActivo">
													<label class="custom-control-label" for="EsActivo">Usuario Activo</label>
												</div>
											</div>
										</div>
										<!-- start table -->
										<div id="content-table" class="col-6">
											<div class="table-responsive" style="overflow-x: hidden;">
												<table id="table-estaciones" class="text-small display table table-bordered table-striped table-bordered table-hover table-condensed table-sm text-small" style="width: 100%">
													<thead>
														<div class="bg-primary rounded py-1 px-1 mb-1">
															<div class="d-flex">
																<h5 class="me-4  p-0 m-0">Aplicaciones para el perfil:</h5>
																<h4><span id="NombrePerfil"></span></h4>
															</div>
														</div>
														<div class="btn-group mb-2">
															<button type="button" data-toggle="tooltip" data-placement="top" title="Mostrar Solo los conceptos seleccionados" class="btn btn-sm btn-primary active" id="button-show-sel">Solo seleccionados</button>
															<button type="button" data-toggle="tooltip" data-placement="top" title="Mostrar Todos los conceptos" class="btn btn-sm btn-primary" id="button-show-all">Todos</button>
														</div>
														<tr>
														<?php
															foreach($a_head_data_2 as $head_data) {
																echo "<th>$head_data</th>";
															}
														?>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div> <!-- end table  -->
									</div>		
								</div> 
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary btn-sm" id="btn-close">Cerrar</button>
									<!-- <button type="button" name="button-save" id="btn-save" class="btn btn-sm btn-primary">Guardar</button> -->
									<?php echo $btn_save; ?>
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
					<!-- <button type="button" id="btn-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button> -->
					<?php echo $btn_new; ?>
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
	<script src="../js/usuarios.js?v=1.002"></script>

<?php include('../layouts/main_end.php'); ?>
