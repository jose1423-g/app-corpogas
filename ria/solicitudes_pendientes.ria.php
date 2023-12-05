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
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 
	$aColumns = array('t1.IdSolicitud', 't1.IdSolicitud AS icons', 't1.Folio', 't4.NoEstacion', 't1.Estatus', 't1.Fecha');
	$aColumnsClean = array( 'IdSolicitud', 'icons', 'Folio', 'NoEstacion', 'Estatus', 'Fecha');

	// especifico
	// ..
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "IdSolicitud";
	
	/* DB table to use */
	$sTable = "solicitudes";
	
	// custom from
	$custom_from = "FROM solicitudes t1 
					LEFT JOIN seg_usuarios t2 ON t1.IdUsuario_fk = t2.IdUsuario
					LEFT JOIN seg_estacionesusuario t3 ON t2.IdUsuario = t3.IdUsuario_fk
					LEFT JOIN estaciones t4 ON t3.IdEstacion_fk  = t4.IdEstacion ";
						
	$custom_where = "";
    $folio = (isset($_GET['Folio'])) ? $_GET['Folio'] : '';
	$s_mostrar =  (isset($_GET['s_mostrar'])) ? $_GET['s_mostrar'] : '';
    
    if (strlen($s_razon_social)) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " Folio LIKE '%$folio%'";
    }
	
	if ($s_mostrar == 2) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " Estatus = 2";
    }
	
	if ($s_mostrar == 3) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " Estatus = 3";
    } else {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " Estatus = 2";
    }

	if ($s_mostrar == 4) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " Estatus = 4";
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
	while ( $aRow = mysqli_fetch_array( $rResult ) ) {
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $aColumns[$i] == "icons" ) {
				$icons = '<div style="cursor:pointer;" title="Editar"><span class="fas fa-pen-square text-primary fs-5 btn-show" aria-hidden="true"></span></div>';
				$row[] = $icons;
            } else if ($aColumns[$i] == "Estatus"){
                $estatus = $aRow[ $aColumns[$i] ];
                if ($estatus == 2) {
                    $html = '<center><span class="text-warning">Pendiente Revision</span></center>';
                } else if ($estatus == 3) {
                    $html = '<center><span class="text-danger">Rechazada</span></center>';
                }
                $row[] = $html;
            } else if ($aColumns[$i] == "Fecha") {
                $fecha = DtDbToShow($aRow[ $aColumns[$i]]);
                $row[] = $fecha;
			} else if ( $aColumns[$i] != ' ' ) {
				/* General output */
				$row[] = utf8_encode($aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>