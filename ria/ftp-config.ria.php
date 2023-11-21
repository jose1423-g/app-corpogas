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
	$aColumns = array( 'IdEstacion', 'PL', 'UsarFTPEstacion', 'FTPServiceType', 'FTPIp', 'FTPUser', 'FTPPass', 'FTPPort', 'FTPConnType', 'FTPFolder', 'EsActivo');
	$aColumnsClean = $aColumns;

	// especifico
	// ..
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "IdEstacion";
	
	/* DB table to use */
	$sTable = "estaciones";
	
	// catalogos
	$a_conn_types = array(
		1 => 'G500',
		2 => 'General'
	);
	
	
	// new function for paging
	$start = (isset($_GET['start'])) ? $_GET['start'] : '';
	$length = (isset($_GET['length'])) ? $_GET['length'] : '-1';
	$pOrder = (isset($_GET['order'])) ? $pOrder = $_GET['order'] : '';
	$searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : '';
	$aQueryRet = DbGetPagingData($sTable, $start, $length, $pOrder, $sIndexColumn, $aColumns, $searchValue, $_GET);
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
			if ( $aColumns[$i] == "EsActivo" ) {
				$row[] = ($aRow[ $aColumns[$i] ] == 1) ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-warning">Inactivo</span>';
			} elseif ( $aColumns[$i] == "UsarFTPEstacion" ) {
				$row[] = ($aRow[ $aColumns[$i] ] == 1) ? 'Estacion' : 'Global';
			} elseif ( $aColumns[$i] == "FTPConnType" ) {
				$ftp_conn_type = $aRow[ $aColumns[$i] ];
				$row[] = (isset($a_conn_types[$ftp_conn_type])) ? $a_conn_types[$ftp_conn_type] : $ftp_conn_type;
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