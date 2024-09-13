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

$qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = $id_user";
$perfil  =  DbGetFirstFieldValue($qry);
if ($perfil == 13) {
	$btn_save = '';
	$btn_new = '';
} else if ($perfil == 16) {
	$btn_save = '<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button>';
	$btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
} else if ($perfil == 12) {
	$btn_save = '<button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button>';
	$btn_new = '<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>';
}

?>

<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

<div class="row px-5 mb-100">

    <form action="" method="post" class="shadow rounded-lg p-5 d-block bg-white">
        <div class="mb-3">
            <label for="excel" class="form-label">Cargar archivo</label>
            <input type="file" class="form-control" name="excel" id="excel" accept=".xls,.xlsx">
        </div>
        <div class="alert alert-primary d-none" role="alert" id="showMessages">
            Espera... <br>
            Estamos cargando y procesando el archivo Excel. Por favor, espere mientras leemos los datos y hacemos los cambios necesarios.
        </div>        
        <div class="alert alert-success d-none" role="alert" id="ShowSuccess">
            El archivo Excel ha sido procesado exitosamente y los datos han sido actualizados en la base de datos.
        </div>
        <button type="button" id="btn_submit" class="btn btn-primary btn-sm">Subir archivo</button>
        <div id="showChanges"></div>
    </form>

</div>


<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/upload_excel.js?v=1.001"></script>


<?php include('../layouts/main_end.php'); ?>