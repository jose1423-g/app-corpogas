<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
// require_once("$SYS_ROOT/php/chrg/facturas.inc.php");

session_start();
$id_user = SessGetUserId();
if (!strlen($id_user)) {
	echo "No user defined";
	exit();
}
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 // $a_head_data = array('#', 'Sel', 'Concepto', 'Anticipo', 'Activo', 'Cuenta Contable', 'Cuenta Contable 2');
	$aColumns = array('UsuarioPerfilId', 'UsuarioPerfilId AS icons', 'NombrePerfil');
	$aColumnsClean = array('UsuarioPerfilId', 'icons', 'NombrePerfil');

	// especifico
	// ...
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "UsuarioPerfilId";
	
	/* DB table to use */
	$sTable = "Seg_UsuarioPerfil";
	
	$custom_from = "";
	
	// php filter

	$searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : ''; // duplicado, para no moverlo de su lugar original
	$custom_where = "";
	$s_mostrar =  (isset($_GET['s_mostrar'])) ? $_GET['s_mostrar'] : '';
	
    // if (strlen($s_mostrar)) {
    //     $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
    //     $custom_where .= " FileName LIKE '%$s_mostrar%'";
    // }

	/*	
	if ($s_mostrar == 1) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " FileName LIKE '%$s_filename%'";
    }
	
	if ($s_mostrar == 2) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " EsActivo = 0";
    }x
	 */
	// new function for paging
	$start = (isset($_GET['start'])) ? $_GET['start'] : '';
	$length = (isset($_GET['length'])) ? $_GET['length'] : '-1';
	$pOrder = (isset($_GET['order'])) ? $pOrder = $_GET['order'] : '';
	$is_debug = 0;
	$useColumnsInWhere = 1;
	$pOrderColumns = array(); 
	// nombre del campo a usar en el Order, esto para poner o quitar t1.
	// for ( $i=0 ; $i < count($pOrder) ; $i++ ) {
		// $columnNo = $pOrder[$i]['column'];
		// if ($columnNo == 1) {
			// $pOrderColumns[$columnNo] = $columnNo;
		// }
	// }
	$searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : '';
	$aQueryRet = DbGetPagingData($sTable, $start, $length, $pOrder, $sIndexColumn, $aColumns, $searchValue, $_GET, $custom_where, $custom_from, $aColumnsClean, $useColumnsInWhere, $is_debug, $pOrderColumns);
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
	while ( $aRow = mysqli_fetch_array( $rResult ) ) {
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $aColumns[$i] == "icons" ) {
				$icons = '<button type="button" title="Editar" class="btn btn-primary btn-sm button-edit"><span class="fa fas fa-edit" aria-hidden="true"></span></button>';
				$row[] = $icons;
			}else if ( $aColumns[$i] != ' ' ) {
				/* General output */
				$row[] = utf8_encode($aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
	exit();
?>