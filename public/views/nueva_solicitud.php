<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Inicio';
$index_active = 'active';
segVerifyAuth($app);

$id_user = SessGetUserId();

// head table
// $a_head_data = array('#', 'Categoria', 'Descripcion', 'Cantidad', 'Costo', 'Sel');

$fecha_hoy = DtDbToday();
$fecha_hoy_show =  DtDbToShow($fecha_hoy);

$qry  = "SELECT t3.EstacionServicio, t3.NoEstacion, t1.Nombre, t1.ApellidoPaterno, t1.APellidoMaterno, t1.Email, t1.telefono, t2.IdEstacion_fk 
        FROM seg_usuarios t1
        LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUsuario_fk
        LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion
        WHERE t1.IdUsuario = $id_user";
$a_servicio = DbQryToArray($qry, true);
foreach ($a_servicio as $row){
    $estacion_servicio =  utf8_encode($row['EstacionServicio']);
    $num_estacion = $row['NoEstacion'];
    $nombre_gerente = $row['Nombre'] ." ". $row['ApellidoPaterno'] ." ". $row['APellidoMaterno'];
    $nombre_gerente = utf8_encode($nombre_gerente);
    $email = utf8_encode($row['Email']);
    $telefono = $row['telefono'];
    $id_estacion = $row['IdEstacion_fk'];
}

$qry = "SELECT IdArea, Nombre FROM areas WHERE EsActivo = 1";
$a_areas = DbQryToArray($qry, true);
$html_areas;
foreach ($a_areas as $row) {
    $id = $row['IdArea'];
    $nombre = $row['Nombre'];
    $html_areas .= "<option value='$id'>$nombre</option>";
}

$qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = $id_user";
$perfil  =  DbGetFirstFieldValue($qry);
if ($perfil == 13) {
    $btn_save = '<button type="button" class="btn btn-primary btn-sm me-2" id="btn-save">Guardar</button>';
} else if ($perfil == 16) {
	$btn_save = '';
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

    <div class="row px-5 mb-100">
        <div class="col-12 shadow-sm bg-white rounded-2 py-2">
            <form action="" method="post" id="form-add">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <div>
                            <h4>1. Nueva Solicitud de Refacciones</h4>
                        </div>
                        <div>
                            <!-- <button type="button" class="btn btn-primary btn-sm me-2" id="btn-save">Guardar</button> -->
                        </div>
                    </div>
                    <div class="col-12 border-bottom border-2 border-primary">
                        <h5>Detalle de la Estación de Servicio</h5>
                    </div>
                    <div class="col-12 mt-1">
                        <h5>Información de Estación</h5>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Nombre de la estación de Servicio:</span><p class="fw-bold mb-0"><?php echo $estacion_servicio; ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">No. Estación:</span><p class="fw-bold mb-0"><?php echo $num_estacion; ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Gerente punto de venta:</span><p class="fw-bold mb-0"><?php echo $nombre_gerente; ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Correo electronico:</span><p class="fw-bold mb-0"><?php echo $email; ?></p>
                    </div>
                    <div class="col-12 d-flex border-bottom border-2 border-primary">
                        <span class="text-secondary fw-bold me-2 mb-2">Telefono/Nextel:</span><p class="fw-bold mb-0"><?php  echo $telefono; ?></p>
                    </div>
                </div><!-- row -->
            
                <div class="row">
                    <div class="col-12 mt-1">
                        <h5>Información de la solicitud</h5>
                    </div>
                    <div class="col-12 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Folio de Solicitud:</span><p class="fw-bold mb-0">CMG - Nueva</p>
                        <input type="hidden" value="CMG - " name="Folio" id="Folio">
                        <input type="hidden" value="<?php echo $id_estacion; ?>" name="IdEstacion_fk" id="IdEstacion_fk">
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Estatus:</span><p class="fw-bold mb-0">Abierto</p>
                        <input type="hidden" value="1" name="Estatus" id="Estatus">
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Fecha Creacion:</span><p class="fw-bold mb-0"><?php echo $fecha_hoy_show; ?></p>
                        <input type="hidden" value="<?php echo $fecha_hoy ?>" name="Fecha" id="Fecha">
                    </div>
                    <div class="col-4">
                        <div class="mb-2">
                            <label class="form-label" for="MatEntregado">Materiales entregados</label>
                            <select class="form-select form-select-sm" name="MatEntregado" id="MatEntregado">
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-2">
                            <label class="form-label" for="IdAreaSolicita_fk">Area que Solicita</label>
                            <select class="form-select form-select-sm" name="IdAreaSolicita_fk" id="IdAreaSolicita_fk">
                                <option value="92">Estacion</option>
                                <?php echo $html_areas; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-2">
                            <label class="form-label" for="FolioRemision">Numero de remisión</label>
                            <input type="text" class="form-control form-control-sm" name="FolioRemision" id="FolioRemision" readonly>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="mb-2">
                            <label class="form-label" for="Observaciones">Observaciones</label>
                            <textarea class="form-control form-control-sm" name="Observaciones" id="Observaciones"></textarea>
                        </div>
                    </div>
                    <div class="col-5 d-flex align-items-center">
                        <?php echo $btn_save; ?> 
                        <!-- <button type="button" class="btn btn-primary btn-sm me-2" id="btn-save">Guardar</button> -->
                    </div>
                </div>
            </form>
        </div>
    </div><!-- row -->

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/nueva_solicitud.js?v=1.003"></script>

<?php include('../layouts/main_end.php'); ?>