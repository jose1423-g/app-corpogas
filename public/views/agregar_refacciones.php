<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Refacciones en solicitud';
$index_active = 'active';
segVerifyAuth($app);


$app = basename(__FILE__);
// Partida
$a_head_data = array('#','Referencia', 'Descripción', 'Cantidad', 'Acciones');
$a_head_data_refacciones = array('#','Referencia', 'Descripción', 'Categoria', 'Cantidad', 'Imagen');

/* obtiene el ultimo id de la tabla solicitudes */
$qry = "SELECT IdSolicitud FROM solicitudes ORDER BY IdSolicitud DESC LIMIT 1";
$id_solicitud = DbGetFirstFieldValue($qry);
$qry = "UPDATE solicitudes SET Folio = 'CMG - $id_solicitud' WHERE IdSolicitud = $id_solicitud";
DbExecute($qry);

$qry = "SELECT IdCategoria, Categoria FROM productos_categorias WHERE EsActivo = 1";
$a_categoria = DbQryToArray($qry);
$html = '';
foreach ($a_categoria as $row){
    $id = $row['IdCategoria'];
    $des = $row['Categoria'];
    $html .= "<option value='$id'>$des</option>";
}


$qry = "SELECT t1.IdSolicitud, t1.Folio, t1.fecha, t2.EstacionServicio, t2.NoEstacion, t3.Nombre, t3.ApellidoPaterno, t3.ApellidoMaterno, t3.Email, t3.telefono, 
t1.MatEntregado, t4.Nombre AS nombrearea, t1.FolioRemision, t1.Estatus, t1.AreaInstaloEntrego, t1.Nomenclatura, t1.Observaciones
FROM solicitudes t1
LEFT JOIN estaciones t2 ON t1.IdEstacion_fk = t2.IdEstacion
LEFT JOIN seg_usuarios t3 ON t1.IdUsuario_fk = t3.IdUsuario
LEFT JOIN areas t4 ON t1.IdAreaSolicita_fk = t4.IdArea
ORDER BY IdSolicitud DESC LIMIT 1";

$a_solicitud  =  DbQryToArray($qry);
foreach ($a_solicitud as $row) {
    $folio_solicitud = $row['Folio'];
    $fecha = DtDbToShow($row['fecha']);
    $estacion_servicio = utf8_encode($row['EstacionServicio']);
    $num_estacion = $row['NoEstacion'];
    $nombre = $row['Nombre'] ." ". $row['ApellidoPaterno'] ." ". $row['ApellidoMaterno'];
    $nombre = utf8_encode($nombre);
    $email = utf8_encode($row['Email']);    
    $telefono = $row['telefono'];
    $mat_entregado = $row['MatEntregado'];
    $area = utf8_encode($row['nombrearea']);
    $folio_remision = $row['FolioRemision'];
    $estatus = $row['Estatus'];
    $area_instalo = $row['AreaInstaloEntrego'];
    $nomenclatura = $row['Nomenclatura'];
    $observaciones = utf8_encode($row['Observaciones']);

}


?>
<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

    <input type="hidden" name="id_solicitud" id="id_solicitud" value="<?php echo $id_solicitud; ?>">

    <div class="position-absolute top-50 start-50 translate-middle w-25 d-none" id="spinner" style="z-index: 2000">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- modal img -->
    <div class="modal fade" id="modal-img" tabindex="-1" style="z-index: 1900">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <h5 class="modal-title" id="titulo">Modal title</h5> -->
					<button type="button" class="btn-close" id="close-modal-img" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<img id="show_img" class="img-fluid" alt="Este producto no cuenta con imagen">
				</div>
			</div>
		</div>
	</div>

    <!-- add refacciones -->
    <div class="modal fade" id="add-modal" tabindex="-1" style="z-index: 1600">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catalogo de Refacciones</h5>
                <button type="button" class="btn-close" id="modal-close-products"></button>
            </div>
            <div class="modal-body">
                
                <div class="shadow-sm bg-white rounded-2 py-2">
                    <div class="row justify-content-center">
                        <div class="col-4 mb-3">
                            <label class="form-label" for="IdCategoria_fk">Categoria</label>
                            <select class="form-control form-control-sm" name="IdCategoria_fk" id="IdCategoria_fk">
                                <option value=""></option>    
                                <?php echo $html; ?>
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label" for="Descripcion">Nombre</label>
                            <input type="text" class="form-control form-control-sm" name="Descripcion" id="Descripcion">
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-bold" for="Ordenar">Ordenar</label>
                            <div class="input-group mb-3">
                                <select class="form-select form-select-sm" name="Ordenar" id="Ordenar">
                                    <option value=""></option>    
                                    <option value="1">Categoria</option>
                                    <option value="2">Descripción</option>
                                </select>
                                <button class="btn btn-outline-info bg-info btn-sm" type="button" id="button-search"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table  modal -->
                <div class="conten bg-white mt-1 shadow-sm rounded-2 py-1">			
                    <section class="content-header">
                        <!-- <h1 class="fs-4"><?php echo $app_title; ?></h1> -->
                    </section>
                    <div class="table-responsive" style="overflow-x: hidden;">
                        <table id="table-refacciones" class="text-small display table table-bordered table-striped table-bordered table-hover table-condensed table-sm" style="width: 100%">
                            <thead class="text-center">
                                <tr>
                                <?php
                                    foreach($a_head_data_refacciones as $head_data) {
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
            </div><!-- end -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="btn-agregar">Agregar</button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-exit">Salir</button>
            </div>
            </div>
        </div>
    </div>

    <div class="row px-5 text-small mb-100">
        <div class="col-12 shadow-sm bg-white rounded-2 py-2">
            <div class="row">
                <div class="col-12 mb-3 border-bottom border-primary border-2">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="me-5">2. Agregar Refacciones del folio <?php echo $folio_solicitud; ?></h2>
                        <button type="button" class="btn btn-info btn-sm  text-white" id="mas-informacion">Mas información</button>
                    </div>
                    <!-- <h4>Armado de Solicitud</h4> -->
                </div>
                <div class="row" id="informacion" style="display: none;">
                    <div class="col-12 mb-2">
                        <h4>Informacion de la solicitud</h4>
                    </div>
                    <div class="col-12 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Folio de Solicitud:</span><p class="fw-bold mb-0"><?php echo $folio_solicitud ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Estatus:</span>
                        <p class="fw-bold mb-0">
                            <?php 
                            if ($estatus == 1) {
                                echo 'Abierto';
                            } ?>
                        </p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Fecha de creacion:</span><p class="fw-bold mb-0"><?php echo $fecha ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Materiales Entregados:</span><p class="fw-bold mb-0"><?php echo $mat_entregado ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Area que solicita:</span><p class="fw-bold mb-0"><?php echo $area ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Se entrego material completo:</span><p class="fw-bold mb-0"><?php  ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Folio de remision:</span><p class="fw-bold mb-0"><?php echo $folio_remision ?></p>
                    </div>
                    <div class="col-12 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Observaciones:</span><p class="fw-bold mb-0"><?php echo $observaciones ?></p>
                    </div>
                    <div class="col-12 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Motivo de Rechazo:</span><p class="fw-bold mb-0"><?php echo "" ?></p>
                    </div>
                    <div class="col-12 d-flex border-bottom border-primary border-2">
                        <span class="text-secondary fw-bold me-2 mb-2">Observaciones Generales:</span><p class="fw-bold mb-0"><?php echo "" ?></p>
                    </div>
                    <div class="col-12 mb-2 mt-3">
                        <h4>Informacion de Estacion</h4>
                    </div>
                    <div class="col-7 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Estacion de servicio:</span><p class="fw-bold mb-0"><?php echo $estacion_servicio ?></p>
                    </div>
                    <div class="col-4 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">No. Estacion::</span><p class="fw-bold mb-0"><?php echo $num_estacion  ?></p>
                    </div>
                    <!-- No. Estacion: -->
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Gerente punto de venta:</span><p class="fw-bold mb-0"><?php echo $nombre ?></p>
                    </div>
                    <div class="col-6 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Correo electronico:</span><p class="fw-bold mb-0"><?php echo $email ?></p>
                    </div>
                    <div class="col-12 d-flex">
                        <span class="text-secondary fw-bold me-2 mb-1">Telefono/Nextel:</span><p class="fw-bold mb-0"><?php echo $telefono ?></p>
                    </div>
                </div>
            </div>
        </div> <!-- shadow -->

        <div class="col-12 conten bg-white mt-3 shadow-sm rounded-2 py-2">			
			<section class="content-header">
                <div class="d-flex justify-content-between ">
                    <h1 class="fs-4"><?php echo $app_title; ?></h1>
                    <div>
                        <button type="button" class="btn btn-primary btn-sm ms-5" id="btn-add">Agregar Refacciones</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-revision">Enviar a Revision</button>
                        <button type="button" class="btn btn-danger btn-sm" id="cancelar" data-bs-dismiss="modal">Cancelar Pedido</button>
                    </div>
                </div>
			</section>
			<div class="table-responsive" style="overflow-x: hidden;">
				<table id="table-solicitud" class="text-small display table table-bordered table-striped table-bordered table-hover table-condensed table-sm" style="width: 100%">
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
<script src="../js/agregar_refacciones.js?v=1.004"></script>

<?php include('../layouts/main_end.php'); ?>