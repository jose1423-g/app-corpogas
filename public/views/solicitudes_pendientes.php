<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Busqueda de Solicitudes';
$usuarios_active = 'active';
segVerifyAuth($app);

$id_user = SessGetUserId();

// read data (grid)
$a_head_data = array('#', 'Acciones', 'Folio', 'Estacion', 'Estatus', 'Fecha Creacion');
$a_head_data_refacciones = array('Partida', 'Referencia', 'Descripcion', 'Cantidad');

$qry = "SELECT IdEstacion, EstacionServicio FROM estaciones";
$a_estaciones = DbQryToArray($qry, true);
$estacion;
foreach($a_estaciones as $row){
    $id = $row['IdEstacion'];
    $nombre = utf8_encode($row['EstacionServicio']);
    $estacion .= "<option value='$id'>$nombre</option>";
}

$fecha = DtDbToday();

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

    <input type="hidden" name="fecha_val" id="fecha_val" value="<?php echo $fecha ?>">

	<div class="row px-5 mb-100">
		<div class="col-12">
			<!-- Modal -->
			<div class="modal fade" id="DataModal" tabindex='-1'>
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Infromacion de la solcitud</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body p-0">
							<form id="form-data" class="form-horizontal" action="" method="post">
							<input type="hidden" id="id_solicitud" name="id_solicitud" value="1">
							<div class="modal-body">
								<div class="row">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Folio de la Solicitud:</span><p class="fw-bold mb-0" id="folio"></p>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Estatus:</span><p class="fw-bold mb-0" id="Estatus"></p>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Fecha de Creacion:</span><p class="fw-bold mb-0" id="fecha"></p>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Materiales Entregados:</span><p class="fw-bold mb-0" id="matentregados"></p>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Area que Solicita:</span><p class="fw-bold mb-0" id="areasolicita"></p>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Se entrego Material Completo:</span><p class="fw-bold mb-0" id="matcompleto"></p>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Folio Remision:</span><p class="fw-bold mb-0" id="folioremision"></p>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Observaciones:</span><p class="fw-bold mb-0" id="observaciones"></p>
                                        </div>
                                        <div class="col-12 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Motivo de Rechazo:</span><p class="fw-bold mb-0" id="motivorechazo"></p>
                                        </div>
                                        <div class="col-12 d-flex">
                                            <span class="text-secondary fw-bold me-2 mb-1">Observaciones Generales:</span><p class="fw-bold mb-0" id="obsgenerales"></p>
                                        </div>
                                    </div>
                                        <div class="border-bottom border-1 border-primary mb-2"></div>
                                    <div class="col-12 d-flex">
                                        <span class="text-secondary fw-bold me-2 mb-1">Estacion de Servicio:</span><p class="fw-bold mb-0" id="noestacion"></p>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="text-secondary fw-bold me-2 mb-1">Gerente Punto de Venta:</span><p class="fw-bold mb-0" id="gerente"></p>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="text-secondary fw-bold me-2 mb-1">Correo Electronico:</span><p class="fw-bold mb-0" id="email"></p>
                                    </div>
                                    <div class="col-6 d-flex">
                                        <span class="text-secondary fw-bold me-2 mb-1">Telefono/nextel:</span><p class="fw-bold mb-0" id="telefono"></p>
                                    </div>
                                        <div class="border-bottom border-1 border-primary mb-2"></div>
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
								</div>
							</div> 
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-danger btn-sm" id="btn-rechazar">Rechazar</button>
                                <button type="button" class="btn btn-primary btn-sm" id="btn-aprobar">Aprobar</button>
								<!-- <button type="button" name="button-save" id="button-save" class="btn btn-sm btn-primary">Guardar</button> -->

							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- table -->
		<div class="col-12 conten bg-white mt-3 shadow-sm rounded-2 py-2">			
			<section class="content-header">
				<div class="row justify-content-end">
                    <h1 class="fs-4"><?php echo $app_title; ?></h1>
                    <div class="col-4">
                        <label class="form-label fw-bold" for="s_estacion">Estaciones</label>
                        <div class="input-group mb-3">
                            <select class="form-select form-select-sm" name="s_estacion" id="s_estacion">
                                <option value=""></option>
                                <?php echo $estacion ?>
                            </select>    
                        </div>
				    </div>
                    <div class="col-3">
                        <label class="form-label fw-bold" for="s_mostrar">Estatus</label>
                        <div class="input-group mb-3">
                            <select class="form-select" name="s_mostrar" id="s_mostrar">
                                <option value=""></option>    
                                <option value="">Pendiente Revision</option>
                                <option value="3">Rechazadas</option>
                                <option value="4">Abiertas</option>
                            </select>    
                            <button class="btn btn-outline-info bg-info btn-sm" type="button" id="btn-search"><i class="fas fa-search"></i></button>
                        </div>
				    </div>
                </div>
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
	<script src="../js/solicitudes_pendientes.js?=1.001"></script>

<?php include('../layouts/main_end.php'); ?>


