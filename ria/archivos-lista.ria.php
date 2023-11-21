<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");

session_start();
$id_user = SessGetUserId();
if (!strlen($id_user)) {
	echo "No user defined";
	exit();
}
	// Configuracion especifica por estacion
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 // $a_head_data = array('#', 'Estacion', 'Archivo', 'Fecha', 'Hora', 'IP', 'Estatus Envio', 'Fecha Envio', 'Hora Envio', 'Acciones');
	$aColumns = array( 't1.IdFile', 't2.PL', 't1.FileName', 't1.Fecha', 't1.Hora', 't1.IPOrigen', 't1.EstatusEnvio AS EstatusEnvioDesc', 't1.FechaEnvio', 't1.HoraEnvio', 't1.Mensaje', 't1.MensajeEnvio', 't1.EstatusEnvio');
	$aColumnsClean = array( 'IdFile', 'PL', 'FileName', 'Fecha', 'Hora', 'IPOrigen', 'EstatusEnvioDesc', 'FechaEnvio', 'HoraEnvio', 'Mensaje', 'MensajeEnvio', 'EstatusEnvio');

	// especifico
	// ..
	
	// catalogos
	$a_estatus = array(0 => '<span class="badge badge-primary">Pendiente Enviar</span>', 1 => '<span class="badge badge-warning">Envio fallido</span>', 2 => '<span class="badge badge-success">Enviado</span>');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "IdFile";
	
	/* DB table to use */
	$sTable = "archivos";
	
	// custom from
	$custom_from = "FROM archivos t1
					LEFT JOIN estaciones t2 ON
						t1.IdEstacion = t2.IdEstacion";
						
	// php filter
	$searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : ''; // duplicado, para no moverlo de su lugar original
	$custom_where = "";
	$s_filename = (isset($_GET['s_filename'])) ? $_GET['s_filename'] : '';
	$s_fecha_desde =  (isset($_GET['s_fecha_desde'])) ? $_GET['s_fecha_desde'] : '';
	$s_fecha_hasta =  (isset($_GET['s_fecha_hasta'])) ? $_GET['s_fecha_hasta'] : '';
	$s_fecha_envio =  (isset($_GET['s_fecha_envio'])) ? $_GET['s_fecha_envio'] : '';
	$s_estatus_envio =  (isset($_GET['s_estatus_envio'])) ? $_GET['s_estatus_envio'] : '';
	
    if (strlen($s_filename)) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " t1.FileName LIKE '%$s_filename%'";
    }
    if (strlen($s_fecha_desde)) {
		$s_fecha_desde_db = DtShowToDb($s_fecha_desde);
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " t1.Fecha >= $s_fecha_desde_db";
    }
    if (strlen($s_fecha_hasta)) {
		$s_fecha_hasta_db = DtShowToDb($s_fecha_hasta);
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " t1.Fecha <= $s_fecha_hasta_db";
    }
    if (strlen($s_fecha_envio)) {
		$s_fecha_envio_db = DtShowToDb($s_fecha_envio);
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " t1.FechaEnvio = $s_fecha_envio_db";
    }
	// var_dump($s_estatus_envio); exit();
    if ($s_estatus_envio === '0') {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " COALESCE(t1.EstatusEnvio, 0) = 0";
    } elseif ($s_estatus_envio === '1' or $s_estatus_envio === '2') {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " COALESCE(t1.EstatusEnvio, 0) = $s_estatus_envio";
	}
	
	
	// new function for paging
	$start = (isset($_GET['start'])) ? $_GET['start'] : '';
	$length = (isset($_GET['length'])) ? $_GET['length'] : '-1';
	$pOrder = (isset($_GET['order'])) ? $pOrder = $_GET['order'] : '';
	$searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : '';
	$aQueryRet = DbGetPagingData($sTable, $start, $length, $pOrder, $sIndexColumn, $aColumns, $searchValue, $_GET, $custom_where, $custom_from, $aColumnsClean);
	$sLimit = $aQueryRet['sLimit'];
	$sOrder = $aQueryRet['sOrder'];
	$sWhere = $aQueryRet['sWhere'];
	$rResult = $aQueryRet['rResult']; // recorset?
	$iFilteredTotal = $aQueryRet['iFilteredTotal'];
	$iTotal = $aQueryRet['iTotal'];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval((isset($_GET['sEcho'])) ? $_GET['sEcho'] : 0),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	$aColumns = $aColumnsClean;
	while ( $aRow = mysqli_fetch_array( $rResult, MYSQLI_ASSOC ) ) {
		$row = array();
		// echo "<pre>" . print_r($aColumns, true) . "</pre>";
		// echo "<pre>" . print_r($aRow, true) . "</pre>"; exit();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $aColumns[$i] == "EstatusEnvioDesc" ) {
				$estatus_envio = $aRow[ $aColumns[$i] ];
				$estatus_envio = (strlen($estatus_envio)) ? $estatus_envio : 0;
				$row[] = (isset($a_estatus[$estatus_envio])) ? $a_estatus[$estatus_envio] : $estatus_envio;
			} elseif ( $aColumns[$i] == "Fecha" ) {
				$row[] = DtDbToShow($aRow[ $aColumns[$i] ]);
			} elseif ( $aColumns[$i] == "FechaEnvio" ) {
				$row[] = DtDbToShow($aRow[ $aColumns[$i] ]);
			}

			else if ( $aColumns[$i] != ' ' ) {
				/* General output */
				$row[] = utf8_encode($aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>