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
$app_title = 'Categorias';
$usuarios_active = 'active';
segVerifyAuth($app);

$id_user = SessGetUserId();

// read data (grid)
$a_head_data = array('#', 'Sel', 'Categoria', 'Estatus', 'Encargado');


$qry = "SELECT IdUsuario, Nombre FROM seg_usuarios WHERE EsActivo = 1";
$a_perfiles = DbQryToArray($qry, true);

$qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = $id_user";
$perfil  =  DbGetFirstFieldValue($qry);
if ($perfil == 13) {
	$btn_save = '';
	$btn_new = '';
} else if ($perfil == 16) {
	$btn_save = '<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button>';
	$btn_delete = '<button type="button" class="btn btn-danger btn-sm" id="btn-delete">Eliminar</button>';
	$btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
} else if ($perfil == 12) {
	$btn_save = '<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button>';
	$btn_delete = '<button type="button" class="btn btn-danger btn-sm" id="btn-delete">Eliminar</button>';
	$btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
}

?>

<?php include('../layouts/main.php'); ?>
<?php include('../layouts/main_content.php') ?>

	<div class="row px-5">
		<div class="col-12">
			<!-- Modal -->
			<div class="modal fade" id="DataModal" tabindex='-1'>
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Agregar/Editar Categoria </h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body p-0">
							<form id="form-data" class="form-horizontal" action="" method="post">
							<input type="hidden" id="id_categoria" name="id_categoria">
							<!-- <input type="hidden" id="IsPasswdMod" name="IsPasswdMod" value="0"> -->
							<div class="modal-body">
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label class="form-label fw-bold">Nombre Categoria</label>
											<input type="text" name="Categoria" id="Categoria" class="form-control form-control-sm">
										</div>
									</div>
									<div class="col-12">
										<div class="form-group col-12">
											<label class="form-label fw-bold" for="IdUsuario_fk">Encargado</label>
											<select type="text" class="form-control form-control-sm" name="IdUsuario_fk" id="IdUsuario_fk">
												<option value="">Selecciona un valor</option>
												<?php
													foreach($a_perfiles as $a_pf) {
														$id_perfil_show = $a_pf['IdUsuario'];
														$nombre_perfil_show = $a_pf['Nombre'];
														echo "<option value=\"$id_perfil_show\">$nombre_perfil_show</option>";
													}
												?>
											</select>
										</div> 
									</div>
									<div class="col-12">
										<div class="form-group">
											<div class="custom-control custom-switch">
												<input type="checkbox" class="custom-control-input" name="EsActivo" id="EsActivo">
												<label class="custom-control-label" for="EsActivo">Activo</label>
											</div>
										</div>
									</div>
								</div>
							</div> 
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
								<!-- <button type="button" class="btn btn-danger btn-sm" id="btn-delete">Eliminar</button> -->
								<!-- <button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button> -->
								<?php echo $btn_delete; ?>
								<?php echo $btn_save; ?>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

        <!-- search -->
        <div class="col-12 shadow-sm bg-white rounded-2 py-2">
            <div class="py-2 border-bottom">
                <h4>Categorias</h4>
            </div>
            <!-- <form id="form-data" class="form-horizontal mt-2" action="" method="post"> -->
			<div class="row pt-2 justify-content-center">
				<div class="col-4">
					<div class="form-group">
						<label class="form-label fw-bold" for="">Categoria</label>
						<input class="form-control form-control-sm" name="" id="">
					</div> 
				</div>
				<div class="col-4">
					<label class="form-label fw-bold" for="">Estatus</label>
					<div class="input-group mb-3">
						<select class="form-select form-select-sm" name="" id=""></select>    
						<button class="btn btn-outline-info bg-info btn-sm" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
					</div>
				</div>
			</div><!-- row -->
            <!-- </form> -->
        </div>
		
		<!-- table -->
		<div class="col-12 conten bg-white mt-3 shadow-sm rounded-2 py-2">			
			<section class="content-header">
				<!-- <h1 class="fs-4"><?php echo $app_title; ?></h1> -->
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
					<!-- <button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button> -->
					<?php echo $btn_new ?>
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
	<script src="../js/seg_categorias.js?=1.001"></script>

<?php include('../layouts/main_end.php'); ?>


