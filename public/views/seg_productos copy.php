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
$a_head_data = array('#', 'sel', 'Descripcion', 'Clave', 'Categoria', 'Activo', 'FechaCaptura', 'CapturadoPor');
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
			<!-- Modal -->
        <div class="modal fade" id="DataModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar/Editar Productos </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form id="form-data" class="form-horizontal" action="" method="post">
                            <input type="hidden" id="IdUsuario" name="IdUsuario">
                            <input type="hidden" id="IsPasswdMod" name="IsPasswdMod" value="0">
                            <div class="modal-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="">Descripcion</label>
                                            <input type="text" name="" id="" class="form-control">
                                        </div> 
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="">Clave</label>
                                            <input type="text" name="" id="" class="form-control">
                                        </div> 
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="EsActivo" id="EsActivo">
                                                <label class="custom-control-label" for="EsActivo">Activo</label>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="">Proveedor</label>
                                            <select class="form-control" name="" id="">
                                                <option value="">-- proveedor --</option>
                                            <?php
                                                foreach($a_perfiles as $a_pf) {
                                                    $id_perfil_show = $a_pf['UsuarioPerfilId'];
                                                    $nombre_perfil_show = $a_pf['NombrePerfil'];
                                                    echo "<option value=\"$id_perfil_show\">$nombre_perfil_show</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="">Codigo de Barras</label>
                                            <input type="text" name="" id="" class="form-control">
                                        </div> 
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="">Categoria</label>
                                            <select class="form-control" name="" id="">
                                                <option value="">-- Categoria --</option>
                                            <?php
                                                foreach($a_perfiles as $a_pf) {
                                                    $id_perfil_show = $a_pf['UsuarioPerfilId'];
                                                    $nombre_perfil_show = $a_pf['NombrePerfil'];
                                                    echo "<option value=\"$id_perfil_show\">$nombre_perfil_show</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="">Precio</label>
                                            <input type="text" name="" id="" class="form-control">
                                        </div> 
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
        <!-- search -->
        <div class="col-12 shadow-sm bg-white rounded-2 py-2">
            <div class="py-2 border-bottom">
                <h4>Productos</h4>
            </div>
            <form id="form-data" class="form-horizontal mt-2" action="" method="post">
                <div class="row pt-2">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Descripcion</label>
                            <input class="form-control form-control-sm" name="" id="">
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Clave</label>
                            <input class="form-control form-control-sm" name="" id="">
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Proveedor</label>
                            <select class="form-control" name="" id="">
                                <option value="">-- proveedor --</option>
                            <?php
                                foreach($a_perfiles as $a_pf) {
                                    $id_perfil_show = $a_pf['UsuarioPerfilId'];
                                    $nombre_perfil_show = $a_pf['NombrePerfil'];
                                    echo "<option value=\"$id_perfil_show\">$nombre_perfil_show</option>";
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Categoria</label>
                            <select class="form-control form-control-sm" name="" id=""></select>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="form-label fw-bold" for="">Codigo de barras</label>
                            <input type="text" class="form-control form-control-sm" name="" id="">
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
            </form>
        </div>
		
		<!-- table -->
		<div class="col-12 conten bg-white mt-3 shadow-sm rounded-2 py-2">
				<section class="content-header">                        
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
			<!-- </div> -->
		</div>
	</div> 

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/proveedores.js"></script>
<?php include('../layouts/main_end.php'); ?>