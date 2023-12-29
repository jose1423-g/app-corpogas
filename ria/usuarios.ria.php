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
	 
	$aColumns = array('t1.IdUsuario', 't1.IdUsuario AS icons', "CONCAT(t1.Nombre,' ',t1.ApellidoPaterno,' ',t1.ApellidoMaterno) AS Gerente",
	't2.NombrePerfil', 't1.Email AS correogpv', 't1.EsActivo');
	$aColumnsClean = array( 'IdUsuario', 'icons', 'Gerente', 'NombrePerfil', 'correogpv', 'EsActivo');

	// especifico
	// ..
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "IdUsuario";
	
	/* DB table to use */
	$sTable = "seg_usuarios";
	
	// custom from
	$custom_from = "FROM seg_usuarios t1
					LEFT JOIN seg_usuarioperfil t2 ON t1.UsuarioPerfilId_fk = t2.UsuarioPerfilId";

	// $custom_from = "FROM seg_usuarios t1
	// 				LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUsuario_fk
	// 				LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion";

						
	$custom_where = "";
	
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
			if ( $aColumns[$i] == "EsActivo" ) {
				$estatus = $aRow[ $aColumns[$i] ];
                if ($estatus == 1) {
                    $html = '<center><i class="far fa-check-square"></i></center>';
                } else {
                    $html = '<center><i class="far fa-square"></i></center>';
                }
                $row[] = $html;
			} else if ( $aColumns[$i] == "Gerente" ){
				$gerente= utf8_encode($aRow[ $aColumns[$i] ]);
				$row[] = $gerente;
			} else if ($aColumns[$i] == "icons") {
				$icons = '<div style="cursor:pointer;" title="Editar"><span class="fas fa-pen-square text-primary fs-5 btn-edit" aria-hidden="true"></span></div>';
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