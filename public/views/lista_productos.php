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
// $g_nombre_usuario = GetUserName($id_user, 'NA');

// // read data (grid)
$a_head_data = array('IdProducto', 'Editar', 'Descripción', 'Referencia', 'No Serie', 'Categoría', 'Imagen', 'Acción');
// $a_grid_data = array();

/* Categoria */
$qry = "SELECT IdCategoria, Categoria FROM productos_categorias WHERE EsActivo = 1";
$a_categoria = DbQryToArray($qry);
$html = '';
foreach ($a_categoria as $row){
    $id = $row['IdCategoria'];
    $des = $row['Categoria'];
    $html .= "<option value='$id'>$des</option>";
}

$qry = "SELECT IdCategoria, Categoria FROM productos_categorias WHERE EsActivo = 1";
$a_categoria = DbQryToArray($qry, true);
$html_categoria = '';
foreach ($a_categoria as $row){
    $id = $row['IdCategoria'];
    $categoria = $row['Categoria'];
    $html_categoria .= "<option value='$id'>$categoria</option>";
}

$qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = $id_user";
$perfil  =  DbGetFirstFieldValue($qry);
if ($perfil == 13) {
	$btn_save = '';
	$btn_new = '';
} else if ($perfil == 16) {
	$btn_save = '<button type="button" name="btn-save" id="btn-save" class="btn btn-sm btn-primary">Guardar</button>';
	// $btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
} else if ($perfil == 12) {
	$btn_save = '<button type="button" name="btn-save" id="btn-save" class="btn btn-sm btn-primary">Guardar</button>';
	// $btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
}


?>

<?php include('../layouts/main.php'); ?>
<?php include('../layouts/main_content.php') ?>

	<div class="position-absolute top-50 start-50 translate-middle w-25 d-none" id="spinner" style="z-index: 2000">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
	
	<!-- modal  img-->
	<div class="modal fade" id="DataModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <h5 class="modal-title" id="titulo">Modal title</h5> -->
					<button type="button" class="btn-close" id="close-modal-img" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<img id="show_img" class="img-fluid" alt="Este producto no cuenta con imagen">
				</div>
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div> -->
			</div>
		</div>
	</div>

	<!-- Modal para editar -->
	<div class="modal fade" id="DataModalEdit" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Actulizar</h5>
					<button type="button" class="btn-close" id="close-modal-img" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>				
				<form id="form-data" class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
					<input type="hidden" id="Id_producto" name="Id_producto">
					<input type="hidden" name="img" id="img">
					<div class="modal-body p-3">

						<div class="form-group">
							<label class="form-label fw-bold" for="NombreRefaccion">Nombre Refaccion</label>
							<input type="text" class="form-control form-control-sm" name="NombreRefaccion" id="NombreRefaccion">
						</div>
						
						<div class="form-group">
							<label class="form-label fw-bold" for="Referencia">Referencia</label>
							<input type="text" class="form-control form-control-sm" name="Referencia" id="Referencia">
						</div> 							
						
						<div class="form-group">
							<label class="form-label fw-bold" for="NoSerie">Numero de serie</label>
							<input type="text" class="form-control form-control-sm" name="NoSerie" id="NoSerie">
						</div> 
													
						<div class="form-group">
							<label class="form-label fw-bold" for="IdCategoria_fkP">Categoria</label>
							<select class="form-control form-control-sm" name="IdCategoria_fkP" id="IdCategoria_fkP">
								<option value=""></option>
								<?php echo $html_categoria; ?>
							</select>
						</div>

						<div class="mb-3">
							<label class="form-label" for="uploadedfile">Imagen</label>
							<input class="form-control form-control-sm" type="file" name="uploadedfile"  id="uploadedfile">							
							<p class="my-p" id=img_p></p>
						</div>
							
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" name="EsActivoP" id="EsActivoP">
							<label class="custom-control-label" for="EsActivoP">Activo</label>
						</div>
														
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
						<?php echo $btn_save; ?>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row px-5 mb-100">
        <!-- search -->
        <div class="col-12 shadow-sm bg-white rounded-2 py-2">
            <div class="py-2 border-bottom">
                <h4>Productos</h4>
            </div>
			<div class="row pt-2">
				<div class="col-3">
                    <div class="form-group">
                        <label class="form-label fw-bold" for="IdCategoria_fk">Categoría</label>
                        <select class="form-control" name="IdCategoria_fk" id="IdCategoria_fk">
                            <option value=""></option>
                            <?php echo $html; ?>
                        </select>
                    </div>
                </div>
				<div class="col-3">
					<div class="form-group">
						<label class="form-label fw-bold" for="Descripcion">Descripción</label>
						<input class="form-control keydown13" name="Descripcion" id="Descripcion">
					</div> 
				</div>
                <div class="col-2">
					<div class="form-group">
						<label class="form-label fw-bold" for="Referenecia">Referencia</label>
						<input class="form-control keydown13" name="Referenecia" id="Referenecia">
					</div> 
				</div>
				<div class="col-2">
                    <div class="form-group">
                        <label class="form-label fw-bold" for="EsActivo">Mostrar</label>
                        <select class="form-select" name="EsActivo" id="EsActivo">
                            <option value="1" selected>Activo</option>
							<option value="0">Inactivos</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <label class="form-label fw-bold" for="Ordenar">Ordenar</label>
                    <div class="input-group mb-3">
                        <select class="form-select" name="Ordenar" id="Ordenar">
                            <option value=""></option>    
                            <option value="1">Categoria</option>
                            <option value="2">Descripcion</option>
                        </select>
                        <button class="btn btn-outline-info bg-info btn-sm" type="button" id="button-search"><i class="fas fa-search"></i></button>
                    </div>
                </div>
			</div><!-- row -->
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
			<!-- <div class="row">
				<div class="col-sm-6">
					<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>
				</div>
				<div class="col-sm-6">
				</div>
			</div> -->
		</div>
	</div>

<?php include('../layouts/main_content_end.php')  ?>  
<!-- footer -->
<?php include('../layouts/footer.php'); ?>

<!-- script -->
	<script src="../js/lista_productos.js?v=1.003"></script>

<?php include('../layouts/main_end.php'); ?>


