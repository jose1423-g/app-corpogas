<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

// // cia (page title)
// $qry = "SELECT PagesTitle FROM empresa WHERE EmpresaID = 1";
// $title = DbGetFirstFieldValue($qry);
// $title = (strlen($title)) ? $title : "Doxa";

$app = basename(__FILE__);
$app_title = 'Usuarios';
$usuarios_active = 'active';
$valor =  segVerifyAuth($app);
// echo $valor;
// exit();

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

<?php include('../layouts/main.php'); ?>
<?php include('../layouts/main_content.php') ?>

	<div class="row px-5">
		<div class="col-12">
			<!-- Modal -->
			<div class="modal fade" id="DataModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Agregar/Editar Usuario </h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body p-0">
							<form id="form-data" class="form-horizontal" action="" method="post">
								<input type="hidden" id="IdUsuario" name="IdUsuario">
								<input type="hidden" id="IsPasswdMod" name="IsPasswdMod" value="0">
								<div class="modal-body">
									<div class="form-group">
										<label class="form-label fw-bold">Usuario</label>
										<input type="text" name="UserName" id="UserName" class="form-control" placeholder="Usuario" maxlength="30" required>
									</div>
									<div class="form-group">
										<label class="form-label fw-bold">Nombre</label>
										<input type="text" name="Nombre" id="Nombre" class="form-control" placeholder="Nombre" maxlength="30" required>
									</div>
									<div class="form-group">
										<label class="form-label fw-bold">Apellido Paterno</label>
										<input type="text" name="ApellidoPaterno" id="ApellidoPaterno" class="form-control" placeholder="Apellido Paterno" maxlength="30" required>
									</div>
									<div class="form-group">
										<label class="form-label fw-bold">Apellido Materno</label>
										<input type="text" name="ApellidoMaterno" id="ApellidoMaterno" class="form-control" placeholder="Apellido Materno" maxlength="30">
									</div>
									<div class="form-group">
										<label class="form-label fw-bold">Contraseña</label>
										<input type="password" name="passwd" id="passwd" onchange="$('#IsPasswdMod').val(1);" class="form-control" placeholder="Contraseña" maxlength="50">
									</div>
									<div class="form-group">
										<label class="form-label fw-bold">Perfil</label>
										<select class="form-control" name="UsuarioPerfilId_fk" id="UsuarioPerfilId_fk">
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
									<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
									<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button>
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
					<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>
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
