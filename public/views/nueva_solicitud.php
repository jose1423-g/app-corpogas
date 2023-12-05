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
    $estacion_servicio = utf8_encode($row['EstacionServicio']);
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

    <div class="row px-5">
        <div class="col-12 shadow-sm bg-white rounded-2 py-2">
            <form action="" method="post" id="form-add">
                <div class="row">
                    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h2>1. Nueva Solicitud de Refacciones</h2>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm me-2" id="btn-save">Guardar y Agreagar Refacciones</button>
                            <button type="button" class="btn btn-danger btn-sm " id="cancelar">Cancelar</button>
                        </div>
                    </div>
                    <div class="col-12 border-bottom border-2 border-primary">
                        <h4>Detalle de Estacion de Servicio</h4>
                    </div>
                    <div class="col-12 mb-3 mt-3">
                        <h4>Informacion de Estacion</h4>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Nombre de la estacion de Servicio:</span><p class="fw-bold mb-0"><?php echo $estacion_servicio; ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">No. Estacion:</span><p class="fw-bold mb-0"><?php echo $num_estacion; ?></p>
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
                    <div class="col-12 mt-3 mb-3">
                        <h4>Informacion de la solicitud</h4>
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
                        <div class="mb-3">
                            <label class="form-label" for="MatEntregado">Materiales entregados</label>
                            <select class="form-select form-select-sm" name="MatEntregado" id="MatEntregado">
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label" for="IdAreaSolicita_fk">Area que Solicita</label>
                            <select class="form-select form-select-sm" name="IdAreaSolicita_fk" id="IdAreaSolicita_fk">
                                <option value="92">Estacion</option>
                                <?php echo $html_areas; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="form-label" for="FolioRemision">Numero de remision</label>
                            <input type="text" class="form-control form-control-sm" name="FolioRemision" id="FolioRemision" readonly>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="mb-3">
                            <label class="form-label" for="Observaciones">Observaciones</label>
                            <textarea class="form-control form-control-sm" name="Observaciones" id="Observaciones"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- row -->

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/nueva_solicitud.js"></script>

<?php include('../layouts/main_end.php'); ?>