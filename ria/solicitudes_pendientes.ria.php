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

$qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = $id_user";
$a_perfil = DbQryToRow($qry);
$id_perfil = $a_perfil['UsuarioPerfilId_fk'];

$qry = "SELECT IdEstacion_fk FROM seg_estacionesusuario WHERE IdUSuario_fk = $id_user";
$a_estacion = DbQryToRow($qry);
$id_estacion = $a_estacion['IdEstacion_fk'];
	
// $qry = "SELECT UsuarioPerfilId_fk FROM seg_usuarios WHERE IdUsuario = 436";
// $a_data = DbQryToRow($qry);
// $es_perfil = $a_data['UsuarioPerfilId_fk'];
// $perfil = 0;
// 	if ($es_perfil == 12) {
// 		$perfil = 1;
// 	}



	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 
	$aColumns = array('t1.IdSolicitud', 't1.IdSolicitud AS icons', 't1.Folio', 't3.NoEstacion', 't1.Estatus', 't1.Fecha');
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
						LEFT JOIN estaciones t3 ON t3.IdEstacion = t1.IdEstacion_fk";
	// $custom_from = "FROM solicitudes t1 
	// 				LEFT JOIN seg_usuarios t2 ON t1.IdUsuario_fk = t2.IdUsuario
	// 				LEFT JOIN estaciones t3 ON t1.IdEstacion_fk  = t3.IdEstacion 
	// 				LEFT JOIN seg_estacionesusuario t4 ON t3.IdEstacion = t4.IdEstacion_fk";
						
	$custom_where = "";
    $folio = (isset($_GET['Folio'])) ? $_GET['Folio'] : '';
	$s_mostrar =  (isset($_GET['s_mostrar'])) ? $_GET['s_mostrar'] : '';
	$s_estacion =  (isset($_GET['s_estacion'])) ? $_GET['s_estacion'] : '';
    
    // if (strlen($s_razon_social)) {
    //     $custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
    //     $custom_where .= " Folio LIKE '%$folio%'";
    // }
	
	if (strlen($s_mostrar)) {
		if ($s_mostrar == 2) {
			$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
			$custom_where .= " Estatus = 2";
		}
    }
	
	if (strlen($s_mostrar)) {
		if ($s_mostrar == 3) {
        	$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
        	$custom_where .= " Estatus = 3";
		}
    }
	
	if (strlen($s_mostrar)) {
		if ($s_mostrar == 4) {
			$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
			$custom_where .= " Estatus = 4";	
		}
    } else {
		$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
		$custom_where .= " Estatus = 2";
	}

	/* esta condicion es solo para genrentes los admin y los supervisores pueden ver todas */
	if ($id_perfil == '13') {
		$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
		$custom_where .= "t1.IdEstacion_fk = $id_estacion AND t1.IdUsuario_fk = $id_user";			
	}
	
	if (strlen($s_estacion)) {
		/* hacer una consulta de todas las estaciones del supervisor */		
		$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
		$custom_where .= "t1.IdEstacion_fk = $s_estacion";
	} else {
		$qry = "SELECT IdEstacion_fk FROM seg_estacionesusuario WHERE IdUsuario_fk = $id_user";
		$a_estaciones = DbQryToArray($qry);
		$a_datos = array();
		foreach($a_estaciones as $row){
			$valor = $row['IdEstacion_fk'];
			array_push($a_datos, $valor);
		}
		$datos = implode(',', $a_datos);

		$custom_where .= (strlen($searchValue) or strlen($custom_where)) ? " AND " : "WHERE ";
		$custom_where .= "t1.IdEstacion_fk IN($datos)";			
		
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
                    $html = '<span style="cursor:pointer" class="text-warning btn-show-modal">Pendiente Revision</span>';
                } else if ($estatus == 3) {
                    $html = '<span style="cursor:pointer" class="text-danger btn-show-modal">Rechazada</span>';
                } else if ($estatus = 4){
					$html = '<span style="cursor:pointer" class="text-success btn-show-modal">Abierta</span>';
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