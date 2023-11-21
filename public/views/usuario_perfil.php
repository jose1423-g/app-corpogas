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
$app_title = 'Perfil de Usuario';
segVerifyAuth($app);

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

<?php include('../layouts/main.php'); ?>

<?php include('../layouts/main_content.php')  ?>

	<div class="row justify-content-center">   
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="exampleModalLabel">Cambia Password</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body p-0">
						<form id="form-data" class="form-horizontal" action="" method="post">
							<input type="hidden" id="IdUsuario" name="IdUsuario" value="<?php echo $id_user; ?>">
							<!-- <div class="modal-header">
								<h4 class="modal-title">Cambia Password</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div> -->
							<div class="modal-body">
								<div class="mb-3">
									<label class="form-label fw-bold">Contraseña Actual</label>
									<input type="password" name="passwd_actual" id="passwd_actual" class="form-control" placeholder="Contraseña Actual" maxlength="50">
								</div>
								<div class="mb-3">
									<label class="form-label text-success fw-bold">Nueva Contraseña</label>
									<input type="password" name="passwd_nuevo" id="passwd_nuevo" class="form-control" placeholder="Contraseña" maxlength="50">
								</div>
								<div class="mb-3">
									<label class="form-label text-success fw-bold">Confirma Contraseña Nueva</label>
									<input type="password" name="passwd_confirma" id="passwd_confirma" class="form-control" placeholder="Contraseña" maxlength="50">
								</div>
							</div> 
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
								<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Cambiar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- content -->
		<div class="col-4">
			<div class="card shadow-sm">
				<div class="card-body">	
					<div class="form-group">
						<label class="form-label fw-bold">Usuario</label>
						<input type="text" name="UserName" id="UserName" class="form-control form-control-sm" value="<?php echo $user_name; ?>">
					</div>
					<div class="form-group">
						<label class="form-label fw-bold">Nombre</label>
						<input type="text" name="Nombre" id="Nombre" class="form-control form-control-sm " value="<?php echo $nombre_usuario; ?>">
					</div>
					<div class="form-group">
						<label class="form-label fw-bold">Perfil</label>
						<input type="text" name="Perfil" id="Perfil" class="form-control form-control-sm " value="<?php echo $perfil; ?>">
					</div>
					<div class="row">
						<div class="col-md-6">
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
								<i class="fas fa-user-secret"></i> Cambiar password
							</button>
						</div>
					</div>
				</div><!-- /.card-body -->
			</div>
		</div>
	</div><!-- row -->
	
<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>

<!-- script -->
	<script src="../js/usuario_perfil.js?v=1.001"></script>

<?php include('../layouts/main_end.php'); ?>
