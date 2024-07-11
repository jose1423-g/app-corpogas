<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Reporte de Solicitudes';
$usuarios_active = 'active';
segVerifyAuth($app);

$id_user = SessGetUserId();

// read data (grid)
$a_head_data = array('#', 'Folio', 'No. Estacion', 'Estacion', 'Estatus', 'Fecha');

$qry = "SELECT t1.IdEstacion, t1.EstacionServicio
        FROM estaciones t1
        LEFT JOIN seg_estacionesusuario t2 ON t2.IdEstacion_fk = t1.IdEstacion
        WHERE t2.IdUsuario_fk = $id_user";
$a_estaciones = DbQryToArray($qry, true);
$estacion;
foreach($a_estaciones as $row){
    $id = $row['IdEstacion'];
    $nombre = utf8_encode($row['EstacionServicio']);
    $estacion .= "<option value='$id'>$nombre</option>";
}

$fecha = DtDbToday();
$fecha = DtDbToShow($fecha);


$qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = $id_user";
$perfil  =  DbGetFirstFieldValue($qry);
if ($perfil == 13) {
	$btn_save = '';
	$btn_rechazar = '';
} else if ($perfil == 16) {
	$btn_save = '<button type="button" class="btn btn-primary btn-sm" id="btn-aprobar">Aprobar</button>';
    $btn_rechazar = '<button type="button" class="btn btn-danger btn-sm" id="btn-rechazar">Rechazar</button>';	
} else if ($perfil == 12) {
	$btn_save = '<button type="button" class="btn btn-primary btn-sm" id="btn-aprobar">Aprobar</button>';
    $btn_rechazar = '<button type="button" class="btn btn-danger btn-sm" id="btn-rechazar">Rechazar</button>';
}

?>

<?php include('../layouts/main.php'); ?>
<?php include('../layouts/main_content.php') ?>
    
	<div class="row px-5 mb-100">		

		<!-- table -->
		<div class="col-12 conten bg-white shadow-sm rounded-2 py-1">			
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-form-label">Fecha Desde</label>
                            <div class="input-group input-group-sm date" id="datetimepicker1" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input input_text" data-target="#datetimepicker1" data-toggle="datetimepicker" value="<?php echo $fecha ?>" placeholder="<?php echo $fecha ?>" name="s_FechaDesde" id="s_FechaDesde"/>
                                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-form-label">Fecha Hasta</label>
                            <div class="input-group input-group-sm date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input input_text" data-target="#datetimepicker2" data-toggle="datetimepicker" value="<?php echo $fecha ?>" placeholder="<?php echo $fecha ?>" name="s_FechaHasta" id="s_FechaHasta"/>
                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-bold" for="s_mostrar">Estatus</label>
                        <div class="input-group mb-3">
                            <select class="form-select" name="s_mostrar" id="s_mostrar">
                                <option value="">Todas</option>
                                <option value="2">Pendiente Revision</option>
                                <option value="3">Rechazadas</option>
                                <option value="4">Abiertas</option>
                            </select>    
                            <button class="btn btn-outline-info bg-info btn-sm" type="button" id="btn-search"><i class="fas fa-search"></i></button>
                            <button class="btn btn-outline-info bg-success btn-sm" type="button" id="btn-excel"><i class="fas fa-file-excel"></i></button>
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
		</div>
	</div>

<?php include('../layouts/main_content_end.php')  ?>  
<!-- footer -->
<?php include('../layouts/footer.php'); ?>

<!-- script -->
<script src="../js/reporte_solicitudes.js?v=1.002"></script>

<?php include('../layouts/main_end.php'); ?>


