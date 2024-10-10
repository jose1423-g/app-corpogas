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

$a_head_data = array('#', 'sel', 'Descripcion', 'Clave', 'Categoria', 'Activo', 'FechaCaptura', 'CapturadoPor');

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
	// $btn_new = '';
} else if ($perfil == 16) {
	$btn_save = '<button type="button" name="button-save" id="btn-save" class="btn btn-sm btn-primary">Guardar</button>';
	// $btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
} else if ($perfil == 12) {
	$btn_save = '<button type="button" name="button-save" id="btn-save" class="btn btn-sm btn-primary">Guardar</button>';
	// $btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
}


?>

<?php include('../layouts/main.php');  ?>

<?php include('../layouts/main_content.php')  ?>

    <div class="position-absolute top-50 start-50 translate-middle w-25 d-none" id="spinner" style="z-index: 2000">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-100">   
        <div class="col-5 bg-white shadow-lg rounded-2">
            <form id="form-data" class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                <div class="py-2">
                    <h3 class="fw-bold text-center text-primary">Nueva refaccion</h3>
                </div>
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-bold" for="NombreRefaccion">Nombre Refaccion</label>
                                <input type="text" class="form-control form-control-sm" name="NombreRefaccion" id="NombreRefaccion">
                            </div> 
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-bold" for="Referencia">Referencia</label>
                                <input type="text" class="form-control form-control-sm" name="Referencia" id="Referencia">
                            </div> 
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-bold" for="NoSerie">Numero de serie</label>
                                <input type="text" class="form-control form-control-sm" name="NoSerie" id="NoSerie">
                            </div> 
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-bold" for="IdCategoria_fk">Categoria</label>
                                <select class="form-control form-control-sm" name="IdCategoria_fk" id="IdCategoria_fk">
                                    <option value=""></option>
                                <?php echo $html_categoria; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="uploadedfile">Imagen</label>
                                <input class="form-control form-control-sm" type="file" name="uploadedfile"  id="uploadedfile">
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
                <div class="py-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-warning btn-sm me-2" id="btn-clear">Limpiar formulario</button>
                    <!-- <button type="button" name="button-save" id="btn-save" class="btn btn-sm btn-primary">Guardar</button> -->
                    <?php echo $btn_save; ?>
                </div>
            </form>
        </div>
	</div> 

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/seg_productos.js?v=1.001"></script>

<?php include('../layouts/main_end.php'); ?>