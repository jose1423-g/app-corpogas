<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/chrg/facturas.inc.php");
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 // $a_head_data = array('#', 'Sel', 'Concepto', 'Anticipo', 'Activo', 'Cuenta Contable', 'Cuenta Contable 2');
	$aColumns = array( 'idproveedor', 'idproveedor AS icons', 'RazonSocial', 'NombreCorto', 'rfc', 'CdRegimenFiscal', 'tipo', 'Estatus');
	$aColumnsClean = array( 'idproveedor', 'icons', 'RazonSocial', 'NombreCorto', 'rfc', 'CdRegimenFiscal', 'tipo', 'Estatus');

	// especifico
	// ...
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "idproveedor";
	
	/* DB table to use */
	$sTable = "Proveedores";
	
	$custom_from = "";
	
	// php filter
	$searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : ''; // duplicado, para no moverlo de su lugar original
	$custom_where = "";
	$s_razon_social = (isset($_GET['s_razon_social'])) ? $_GET['s_razon_social'] : '';
	$s_mostrar =  (isset($_GET['s_mostrar'])) ? $_GET['s_mostrar'] : '';
	
    if (strlen($s_razon_social)) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " RazonSocial LIKE '%$s_razon_social%'";
    }
	
	if ($s_mostrar == 1) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " Estatus = 1";
    }
	
	if ($s_mostrar == 2) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " Estatus = 0";
    }
	
	// new function for paging
	$start = (isset($_GET['start'])) ? $_GET['start'] : '';
	$length = (isset($_GET['length'])) ? $_GET['length'] : '-1';
	$pOrder = (isset($_GET['order'])) ? $pOrder = $_GET['order'] : '';
	$is_debug = 0;
	$useColumnsInWhere = 1;
	$pOrderColumns = array(); // nombre del campo a usar en el Order, esto para poner o quitar t1.
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
	while ( $aRow = odbc_fetch_array( $rResult ) ) {
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $aColumns[$i] == "Estatus" ) {
				$estatus = $aRow[ $aColumns[$i] ];
				if ($estatus == 1) {
					$html = '<i class="far fa-check-square"></i>';
				} else {
					$html = '<i class="far fa-square"></i>';
				}
				$row[] = $html;
			} else if ( $aColumns[$i] == "icons" ) {
				$icons = '<div style="cursor:pointer;" title="Editar"><span class="fas fa-pen-square button-edit" aria-hidden="true"></span></div>';
				$row[] = $icons;
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
