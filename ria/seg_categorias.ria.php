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
	 
	$aColumns = array( 't1.IdCategoria', 't1.IdCategoria AS icons', 't1.Categoria', 't1.EsActivo', 't2.Nombre');
	$aColumnsClean = array( 'IdCategoria', 'icons', 'Categoria', 'EsActivo', 'Nombre');

	// especifico
	// ..
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "IdCategoria";
	
	/* DB table to use */
	$sTable = "productos_categorias";
	
	// custom from
	$custom_from = "FROM productos_categorias t1 
					LEFT JOIN seg_usuarios t2 ON t1.IdUsuario_fk = t2.IdUsuario";
						
	$custom_where = "";
    $categoria = (isset($_GET['s_Categoria'])) ? $_GET['s_Categoria'] : '';
	$es_activo =  (isset($_GET['Activo'])) ? $_GET['Activo'] : '';

    if (strlen($categoria)) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " t1.Categoria LIKE '%$categoria%'";
    }
	
	if ($es_activo == 1) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " t1.Esactivo = 1";
    }
	
	if ($es_activo == 0) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= " t1.EsActivo = 0";
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
				$icons = '<div style="cursor:pointer;" title="Editar"><span class="fas fa-pen-square text-primary fs-5 button-edit" aria-hidden="true"></span></div>';
                // $icons = '<button type="button" class="btn btn-primary button-edit btn-sm"><span class="fas fa-pen-square button-edit" aria-hidden="true"></button>';
				$row[] = $icons;
            } else if ($aColumns[$i] == "EsActivo"){
                $estatus = $aRow[ $aColumns[$i] ];
                if ($estatus == 1) {
                    $html = '<center><i class="far fa-check-square"></i></center>';
                } else {
                    $html = '<center><i class="far fa-square"></i></center>';
                }
                $row[] = $html;
            } else if ($aColumns[$i] == "Categoria") {
                $categoria = $aRow[ $aColumns[$i] ];
                $row[] = utf8_encode($categoria);
			} else if ( $aColumns[$i] != ' ' ) {
				/* General output */
				$row[] = utf8_encode($aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>