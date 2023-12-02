<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");

session_start();

// recupera datos
// header
$id_user = SessGetUserId();

$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

$folio = (isset($_REQUEST['Folio'])) ? $_REQUEST['Folio'] : '';
// $fecha = (isset($_REQUEST['Fecha'])) ? $_REQUEST['Fecha'] : '';
$id_estacion = (isset($_REQUEST['IdEstacion_fk'])) ? $_REQUEST['IdEstacion_fk'] : '';
$mat_entregado = (isset($_REQUEST['MatEntregado'])) ? $_REQUEST['MatEntregado'] : '';
$id_area_solicita = (isset($_REQUEST['IdAreaSolicita_fk'])) ? $_REQUEST['IdAreaSolicita_fk'] : '';
$folio_remision = (isset($_REQUEST['FolioRemision'])) ? $_REQUEST['FolioRemision'] : '';
$estatus = (isset($_REQUEST['Estatus'])) ? $_REQUEST['Estatus'] : '';
$area_instalo_entrego = (isset($_REQUEST['AreaInstaloEntrego'])) ? $_REQUEST['AreaInstaloEntrego'] : '';
$nomenclatura = (isset($_REQUEST['Nomenclatura'])) ? $_REQUEST['Nomenclatura'] : '';
$observaciones = (isset($_REQUEST['Observaciones'])) ? $_REQUEST['Observaciones'] : '';
// $estatus = (isset($_REQUEST['Estatus'])) ? 1 : 0;

$fecha_hoy = "";
$fecha_hoy =  DtDbToday($fecha_hoy);



$msg = '';
$result = 0;

if ($op == 'save') {
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		if (!strlen($id_area_solicita)) {
			$id_area_solicita = 'NULL';
		}

		if (!strlen($area_instalo_entrego)) {
			$area_instalo_entrego = 'NULL';
		}

		/* quitar Folio, Estatus */
		$qry = "INSERT INTO solicitudes (Folio, Fecha, IdEstacion_fk, IdUsuario_fk, MatEntregado, IdAreaSolicita_fk, FolioRemision, Estatus, AreaInstaloEntrego, Nomenclatura, Observaciones) 
		VALUES 
		('$folio', $fecha_hoy, $id_estacion, $id_user, '$mat_entregado', $id_area_solicita, '$folio_remision', $estatus, $area_instalo_entrego, '$nomenclatura', '$observaciones')";
		$res_ins = DbExecute($qry, true);
		DbCommit();	
		if (is_string($res_ins)) {
			$msg = 'Error al crear la solicitud' . $res_ins;
			$result = -1;
		} else {
			if (!$res_ins) {
				$msg = 'Error al crear la solicitud';
				$result = -1;
			} else {    
				// header('location: ../public/views/agregar_refacciones.php');
				$msg = 'Solicitud creada con exito';
				$result = 1;
				
			}
		}	
		
		$a_categoria['result'] = 1;
		$a_categoria['msg'] = $msg;
		$a_ret = $a_categoria;
	
		echo json_encode($a_ret);
		exit();
	}
//SAVE
} elseif ($op == 'save') {
	//$id_usuario = $id_usuario_save; // compatibility
	if (!strlen($articulo)) {
		$msg = 'El campo producto es requerido';
		$result = -1;
	} else if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		$status = 1;

		if (!strlen($total)) {
			$total = 'NULL';
		}

		// $qry = "SELECT MAX(IdCotizacion) AS IdCotizacion FROM invcotizaciones";
		// $id_cotizacion = DbGetFirstFieldValue($qry);
		// echo "valor". $id_cotizacion;
		// exit();
		
		$qry = "SELECT MAX(IdCotizacion) AS IdCotizacion FROM invcotizaciones";
		$id_cotizacion = DbGetFirstFieldValue($qry);

	 	$qry = "SELECT t2.IdCotizacion
		 		FROM invcotizacionesDt t1  
				LEFT JOIN invcotizaciones t2 ON t1.IdCotizacion_fk = t2.Idcotizacion";
	 	$a_cotizacion = DbQryToRow($qry);
		$id_cotizacion =  $a_cotizacion['IdCotizacion'];
		

		if (!strlen($$id_cotizacion)) {
			echo "false";
			exit();
		}

		echo $$id_cotizacion;
		exit();


		// if (!strlen($id_cotizacion)) {
		$qry="INSERT INTO invcotizaciones (IdCotizacion, FechaCaptura, total, IdProveedor_fk, STATUS, CapturadoPor) VALUES ($fecha_hoy, $total, $proveedor, $status, $id_user)"; 
		$res_ins = DbExecute($qry, true);
		DbCommit();
		// }
		// $id_proveedor = LastIdAutoTable('Proveedores');
		$qry = "SELECT MAX(IdCotizacion) AS IdCotizacion FROM invcotizaciones";
		$id_cotizacion = DbGetFirstFieldValue($qry);

	 	$qry = "SELECT t2.IdCotizacion
		 		FROM invcotizacionesDt t1  
				LEFT JOIN invcotizaciones t2 ON t1.IdCotizacion_fk = $id_cotizacion";
	 	$a_cotizacion = DbQryToRow($qry);
		$id_cotizacion =  $a_cotizacion['IdCotizacion'];

		if (!strlen($$id_cotizacion)) {
			echo "false";
			exit();
		}

		echo $$id_cotizacion;
		exit();

		
		$qry="SELECT Costo FROM productos WHERE IdArticulo = $articulo";
		$a_costo = DbQryToRow($qry);
		$costo =  $a_costo['Costo']; 

		$costo = $costo * $cantidad;

		$qry = "INSERT INTO invcotizacionesDt (IdCotizacion_fk, IdArticulo_fk, Notas, Cantidad, Costo) VALUES ($id_cotizacion, $articulo, '$notas', $cantidad, $costo)";
		$res_ins = DbExecute($qry, true);
		DbCommit();	
		if (is_string($res_ins)) {
			$msg = 'Error al agregar el producto' . $res_ins;
			$result = -1;
		} else {
			if (!$res_ins) {
				$msg = 'Error al agregar el producto';
				$result = -1;
			} else {    
				$msg = 'Producto agregado correctamente';
				$result = 1;
			}
		}	
	}

} else if($op == 'delete') {
	if (!strlen($IdCotizacionDt)) {
		$msg = 'Error le producto no fue seleccionado';
		$result = -1;
	}

	$qry = "DELETE invcotizacionesDt, invcotizaciones
			FROM invcotizacionesDt
			INNER JOIN invcotizaciones ON invcotizacionesDt.IdCotizacion_fk = invcotizaciones.IdCotizacion
			WHERE IdCotizacionDt = $IdCotizacionDt";
	$res_upd = DbExecute($qry);
	DbCommit();
	if (is_string($res_upd)) {
		$msg = 'No se pudo eliminar el producto:' . $res_upd;
		$result = -1;
	} else {
		if (!$res_upd) {
			$msg = 'Error al eliminar producto';
			$result = -1;
		} else {
			$msg = 'producto eliminado con exito';
			$result = 1;
		}
	}

}

// $a_ret = array('result' => $result, 'msg' => $msg, 'id_usuario' => $id_usuario);
// echo json_encode($a_ret);
?>