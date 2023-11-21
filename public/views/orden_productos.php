<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Inicio';
$index_active = 'active';
$valor =  segVerifyAuth($app);
// echo $valor;
// exit();


?>

<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

// session_start();

// // cia (page title)
// $qry = "SELECT PagesTitle FROM empresa WHERE EmpresaID = 1";
// $title = DbGetFirstFieldValue($qry);
// $title = (strlen($title)) ? $title : "Doxa";

$app = basename(__FILE__);
$app_title = 'Usuarios';
$usuarios_active = 'active';
// segVerifyAuth($app);

// $id_user = SessGetUserId();
// $g_nombre_usuario = GetUserName($id_user, 'NA');

// // read data (grid)
$a_head_data = array('#', 'Categoria', 'Descripcion', 'Cantidad', 'Costo', 'Sel');
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
$qry = "SELECT IdProveedor, Nombre FROM proveedores";
$a_civil = DbQryToArray($qry, true);
$html_civil = "";
foreach ($a_civil as $row){
        $id = $row['IdProveedor'];
        $estado_civil =  $row['Nombre'];
        $html_civil .= "<option value='$id'>$estado_civil</option>";
}

// $qry = "SELECT IdCategoria, Categoria FROM productos_categorias";
// $a_civil = DbQryToArray($qry, true);
// $html_categoria = "";
// foreach ($a_civil as $row){
//         $id = $row['IdCategoria'];
//         $estado_civil =  $row['Categoria'];
//         $html_categoria .= "<option value='$id'>$estado_civil</option>";
// }

$qry = "SELECT IdArticulo, descripcion FROM productos";
$a_civil = DbQryToArray($qry, true);
$html_productos = "";
foreach ($a_civil as $row){
        $id = $row['IdArticulo'];
        $estado_civil =  $row['descripcion'];
        $html_productos .= "<option value='$id'>$estado_civil</option>";
}

?>
<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

    <div class="row px-5">
        <div class="col-12 shadow-sm bg-white rounded-2 py-2">
            <form id="form-data" class="form-horizontal" action="" method="post">
                <div class="row pt-2">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="IdProveedor_fk">Categoria</label>
                            <select class="form-control form-control-sm" name="IdProveedor_fk" id="IdProveedor_fk">
                                <option value="">proveedor</option>
                                <?php echo $html_civil; ?>
                            </select>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="IdArticulo_fk">Producto</label>
                            <select class="form-control form-control-sm" name="IdArticulo_fk" id="IdArticulo_fk">
                                <option value="">producto</option>
                                <?php echo $html_productos; ?>
                            </select>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="Cantidad">Cantidad</label>
                            <input class="form-control form-control-sm" name="Cantidad" id="Cantidad">
                        </div> 
                    </div>
                    <!-- <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Categoria</label>
                            <select class="form-control form-control-sm" name="" id="">
                                <option value="">categoria</option>
                                <?php //echo $html_categoria; ?>
                            </select>
                        </div> 
                    </div> -->
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="Notas">Notas</label>
                            <textarea class="form-control form-control-sm" name="Notas" id="Notas"></textarea>
                        </div> 
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <div>
                            <button type="button" title="Limpiar el formulario" class="btn btn-danger btn-sm" id="btn-clear">Borrar</button>
                            <button type="button" title="Agregar producto a la lista" class="btn btn-primary btn-sm" id="btn-save">Agregar</button>
                            <button type="button" title="Cerrar pedido" class="btn btn-warning btn-sm" id="btn-close">Cerrar</button>
                        </div>
                    </div>
                </div><!-- row -->
            </form>
        </div>

        <div class="col-12 content bg-white mt-3 shadow-sm rounded-2 py-2">
            <section class="content-header">
                <h1 class="fs-4">Producto seleccionado</h1>
            </section>
            <div class="table-responsive" style="overflow-x: hidden;">
                <table id="grid-table" class="display table table-bordered table-striped table-bordered table-hover table-condensed table-sm text-small" style="width: 100%">
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
		</div>

    </div><!-- row -->

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/orden_productos.js"></script>

<?php include('../layouts/main_end.php'); ?>