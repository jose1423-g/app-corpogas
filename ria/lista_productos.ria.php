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
	 
	$aColumns = array('t1.IdProducto', 't1.NombreRefaccion', 't1.Referencia', 't1.NoSerie', 't2.Categoria', 't1.IdProducto AS btnshow', 't1.IdProducto AS icons');
	$aColumnsClean = array('IdProducto','NombreRefaccion','Referencia','NoSerie','Categoria', 'btnshow', 'icons');

	// especifico
	// ..
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "IdProducto";
	
	/* DB table to use */
	$sTable = "productos";
	
	// custom from
	$custom_from = "FROM productos t1
                    LEFT JOIN productos_categorias t2 ON t1.IdCategoria_fk = t2.IdCategoria";
						
    $searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : ''; // duplicado, para no moverlo de su lugar original
	$custom_where = "";
	$Descripcion =  (isset($_GET['Descripcion'])) ? $_GET['Descripcion'] : '';
	$Referenecia =  (isset($_GET['Referenecia'])) ? $_GET['Referenecia'] : '';
	$IdCategoria_fk =  (isset($_GET['IdCategoria_fk'])) ? $_GET['IdCategoria_fk'] : '';

    if (strlen($Descripcion)) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= "t1.NombreRefaccion LIKE '%$Descripcion%'";
    }

    if (strlen($Referenecia)) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= "t1.Referencia LIKE '%$Referenecia%'";
    }

	if (strlen($IdCategoria_fk)) {
        $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= "t1.IdCategoria_fk = $IdCategoria_fk";
    } else {
		$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        $custom_where .= "t1.IdCategoria_fk = 0";
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
			if ( $aColumns[$i] == "btnshow" ) {
				$icons = '<button type="button" class="btn btn-primary btn-sm btn-img"><i class="fas fa-eye"></i></button>';
				$row[] = $icons;
			} else if ($aColumns[$i] == "icons" ){
                $icons = '<button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash-alt"></i></button>';
				$row[] = $icons;
            } else if ( $aColumns[$i] != ' ' ) {
				/* General output */
				$row[] = utf8_encode($aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );

?>