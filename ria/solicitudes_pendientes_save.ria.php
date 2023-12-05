<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/file_uploader.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
	
session_start();

// agrega o guarda datos del usuario
$id_user = SessGetUserId();	

// recupera datos
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

$id_solicitud = (isset($_REQUEST['id_solicitud'])) ? $_REQUEST['id_solicitud'] : "";
// $referencia = (isset($_REQUEST['Referencia'])) ? $_REQUEST['Referencia'] : "";
// $no_serie = (isset($_REQUEST['NoSerie'])) ? $_REQUEST['NoSerie'] : "";
// $id_categoria = (isset($_REQUEST['IdCategoria_fk'])) ? $_REQUEST['IdCategoria_fk'] : "";
// $img = (isset($_FILES['uploadedfile'])) ? $_FILES['uploadedfile'] : "";
// $es_activo = (isset($_REQUEST['EsActivo'])) ? $_REQUEST['EsActivo'] : "";


$msg = "";
$result = 0;

// $fecha_hoy = "";
// $fecha_hoy =  DtDbToday($fecha_hoy);

$minutos = date('i');




if ($op == 'loadSolicitud') {
	if (!strlen($id_solicitud)) {
		$msg = "El campo nombre refaccion es necesario";
		$result = -1;
	} elseif (!strlen($id_user)){
		$msg = "Su session ha expirado";
		$result = -1;
	} else {

		$qry = "SELECT t1.Folio, t1.Estatus, t1.Fecha, t1.MatEntregado, t2.Nombre AS AreaSolicita, t1.EntregoMatCompleto, t1.FolioRemision, t1.Observaciones,
		t1.MotRechazo, t1.ObGenerales, t5.NoEstacion, CONCAT(t3.Nombre,' ',t3.ApellidoPaterno,' ',t3.ApellidoMaterno) AS Gerente, t3.Email, t3.Telefono
		FROM solicitudes t1 
		LEFT JOIN areas t2 ON t1.IdAreaSolicita_fk = t2.IdArea
		LEFT JOIN seg_usuarios t3 ON t1.IdUsuario_fk = t3.IdUsuario
		LEFT JOIN seg_estacionesusuario t4 ON t3.IdUsuario = t4.IdUsuario_fk 
		LEFT JOIN estaciones t5 ON t4.IdEstacion_fk = t5.IdEstacion
		WHERE IdSolicitud  = $id_solicitud";
        $a_datos = DbQryToRow($qry);	


		$estatus = $a_datos['Estatus'];
		if ($estatus == 2) {
			$estatus = "<span class='text-warning'>Pendiente Revision</span>";
			$a_datos['Estatus'] = $estatus;
		} else if ($estatus == 3) {
			$estatus = "<span class='text-danger'>Pendiente Revision</span>";
			$a_datos['Estatus'] = $estatus;
		}

		$fecha =  DtDbToShow($a_datos['Fecha']);
		$a_datos['Fecha'] = $fecha;
		$observaciones = utf8_encode($a_datos['Observaciones']);
		$a_datos['Observaciones'] = $observaciones;
		$mot_rechazo = utf8_encode($a_datos['MotRechazo']);
		$a_datos['MotRechazo'] = $mot_rechazo;
		$obs_generales = utf8_encode($a_datos['ObGenerales']);
		$a_datos['ObGenerales'] = $obs_generales;
		$Gerente = utf8_encode($a_datos['Gerente']);
		$a_datos['Gerente'] = $Gerente;
		
		
		$a_datos['result'] = 1;
		$a_ret = $a_datos; 
		echo json_encode($a_ret);
	}
} else if ($op == 'ShowProducts') {

	$qry = "SELECT t2.IdPartida, t3.Referencia, t3.NombreRefaccion, t2.Cantidad 
	FROM solicitudes t1
	LEFT JOIN productos_solicitud t2 ON t1.IdSolicitud = t2.IdSolicitud
	LEFT JOIN productos t3 ON t2.IdProducto_fk = t3.IdProducto
	WHERE t1.IdSolicitud = $id_solicitud ORDER BY t2.IdPartida ASC";
	$a_producto = DbQryToArray($qry, true);	

	$a_data = array();

	foreach ($a_producto as $row){
		$a_data_line = array();

		$id_partida = $id = $a_data_line['IdPartida'] = (string) $row['IdPartida'];
		$referencia =  $a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
		$refaccion =  $a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
		$cantidad =  $a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);
		// $icons =  $a_data_line['icons'] = (string) $row['icons'];
		if (!strlen($id_partida)) {
			$a_data_line[] = '';
		} else if (!strlen($referencia)) {
			$a_data[] =  $a_data;
		} else if (!strlen($refaccion)) {
			$a_data[] =  $a_data;
		} else if (!strlen($cantidad)) {
			$a_data[] =  $a_data;
		// } else if (!strlen($icons)) {
		// 	$a_data[] =  $a_data;
		} else {
		$a_data_line['IdPartida'] = (string) $row['IdPartida'];
		$a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
		$a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
		$a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);
		// $a_data_line['icons'] = (string) $row['icons'];

		// $icons =  "<button type='button' class='btn btn-danger btn-sm btn-delete-product' data-id='$id'><i class='fas fa-trash-alt'></i></button>";
		// $a_data_line['icons'] = $icons;

		$a_data[] = $a_data_line; 
		}        
	}
	$json =  json_encode(array('data' => $a_data), true);
	echo $json;	
} 

?>