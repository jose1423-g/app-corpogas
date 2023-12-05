<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Perfiles';
$index_active = 'active';
segVerifyAuth($app);

$id_user = SessGetUserId();

$a_head_data = array('#', 'Sel', 'Perfil', 'Estatus');


?>

<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

<div class="row px-5">
		<div class="col-12">
			<!-- Modal -->
			<div class="modal fade" id="DataModal" tabindex='-1'>
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Agregar/Editar Perfiles</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body p-0">
							<form id="form-data" class="form-horizontal" action="" method="post">
							<input type="hidden" id="UsuarioPerfilId" name="UsuarioPerfilId">
							<div class="modal-body">
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label class="form-label fw-bold" for="NombrePerfil">Nombre del perfil</label>
											<input type="text" class="form-control form-control-sm" id="NombrePerfil" name="NombrePerfil">
										</div>
									</div>
                                    <!-- <div class="col-12">
										<div class="form-group">
											<label class="form-label fw-bold" for="NoEstacion">Numero de la estacion</label>
											<input type="text" class="form-control form-control-sm" id="NoEstacion" name="NoEstacion">
										</div>
									</div> -->
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
                <div class="row justify-content-end">
                    <h1 class="m-0"><?php echo $app_title; ?></h1>

                    <div class="col-4">
                        <label class="form-label fw-bold" for="s_mostrar">Estatus</label>
                        <div class="input-group mb-3">
                            <select class="form-select form-select-sm" name="s_mostrar" id="s_mostrar">
                                <option value="1">Activos</option>
                                <option value="0">Inactivos</option>
                            </select>    
                            <button class="btn btn-outline-info bg-info btn-sm" type="button" id="btn-search"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
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

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/perfiles.js"></script>


<?php include('../layouts/main_end.php'); ?>