<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");
// leerExcel($absolutePath);

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
    <form action="../../reportes_excel/read_excel.php" method="post">
        <label for="excel">Cargar archivo</label>
        <input type="file" name="excel">
        <button type="submit">Enviar</button>
    </form>
</div>


<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/perfiles.js?v=1.001"></script>


<?php include('../layouts/main_end.php'); ?>