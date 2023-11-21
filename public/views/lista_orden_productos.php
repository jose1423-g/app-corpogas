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
$valor = segVerifyAuth($app);
// echo $valor;
// exit();

// $id_user = SessGetUserId();
// $g_nombre_usuario = GetUserName($id_user, 'NA');

// // read data (grid)
$a_head_data = array('#', 'Categoria', 'Cantidad', 'Precio unitario', 'inpuestos', 'total');
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

<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

    <div class="row px-5">
        <div class="col-12 shadow-sm bg-white rounded-2 p-2">
            <div class="p-2">
                <h4>Busqueda Ordenes</h4>
            </div>
            <form id="form-data" class="form-horizontal" action="" method="post">
                <div class="row pt-2">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Proveedor</label>
                            <select class="form-control form-control-sm" name="" id=""></select>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Fecha</label>
                            <input type="date" class="form-control form-control-sm" name="" id="">
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">No. Orden</label>
                            <select class="form-control form-control-sm" name="" id=""></select>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Referencia</label>
                            <input class="form-control form-control-sm" name="" id="">
                        </div> 
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-bold" for="">Estatus</label>
                        <div class="input-group mb-3">
                            <input class="form-control form-control-sm" name="" id="">
                            <button class="btn btn-outline-info bg-info btn-sm" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div><!-- row -->
            </form>
        </div>
    
        <div class="content bg-white mt-3 shadow-sm rounded-2 py-2">
			<!-- <div class="container-fluid"> -->
				<section class="content-header">
					<!-- <h1 class="fs-4">Producto seleccionado</h1> -->
				</section>
				<div class="row">
					<div class="col-12">
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
				</div>
				<div class="row">
					<div class="col-sm-6">
						<button type="button" id="button-add" title="Agregar" class="btn btn-primary btn-sm"><span class="fa fas fa-plus fs-6 me-2"></span>Nuevo</button>
					</div>
					<div class="col-sm-6">
					</div>
				</div>
			<!-- </div> -->
		</div>

    </div><!-- row -->

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/lista_orden_productos.js"></script>
<?php include('../layouts/main_end.php'); ?>