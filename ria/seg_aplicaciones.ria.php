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
	 
	$aColumns = array( 't1.IdUsuario', 't1.UserName', 't1.ApellidoPaterno', 't1.ApellidoMaterno', 't1.Nombre', "TRIM(CONCAT(COALESCE(t1.Nombre, ''), ' ', COALESCE(t1.ApellidoPaterno, ''), ' ', COALESCE(t1.ApellidoMaterno, ''))) AS NombreCompleto", 't1.UsuarioPerfilId', 't2.NombrePerfil AS Perfil', 't1.EsActivo', 't1.EsActivo AS Activo', 't1.passwd');
	$aColumnsClean = array( 'IdUsuario', 'UserName', 'ApellidoPaterno', 'ApellidoMaterno', 'Nombre', 'NombreCompleto', 'UsuarioPerfilId', 'Perfil', 'EsActivo', 'Activo', 'passwd');

	// especifico
	// ..
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "IdUsuario";
	
	/* DB table to use */
	$sTable = "seg_usuarios";
	
	// custom from
	$custom_from = "FROM seg_usuarios t1
					LEFT JOIN seg_usuarioperfil t2 ON
						t1.UsuarioPerfilId = t2.UsuarioPerfilId";
						
	$custom_where = "";
	// si es usuario actual no es admin, oculta los usuarios con perfil admin
	$qry = "SELECT t2.EsAdmin
			FROM seg_usuarios t1
			LEFT JOIN seg_usuarioperfil t2 ON t1.UsuarioPerfilId = t2.UsuarioPerfilId
			WHERE t1.IdUsuario = $id_user";
	$es_admin = DbGetFirstFieldValue($qry);
	
	if ($es_admin != 1) {
		$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
		$custom_where .= "t2.EsAdmin = 0";
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
		// echo "<pre>" . print_r($aColumns, true) . "</pre>";
		// echo "<pre>" . print_r($aRow, true) . "</pre>"; exit();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $aColumns[$i] == "Activo" ) {
				// $row[] = ($aRow[ $aColumns[$i] ] == 1) ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-warning">Inactivo</span>';
				$row[] = ($aRow[ $aColumns[$i] ] == 1) ? 'Activo' : 'Inactivo';
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