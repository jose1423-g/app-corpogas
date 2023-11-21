<?php

require_once("db_def.inc.php");
require_once("logs.inc.php");

function DBConnect () {
    global $db_connection;

    global $db_error_count;
    global $a_db_errors;
    global $link_ID;
    global $no_connections;
    global $no_connections_atemps;

    $no_connections_atemps++;
    if (!$link_ID) {
        $provider = $db_connection["barbet_db"];
        $persistent = $provider["Persistent"];
		
        if ($persistent) {
            $link_ID = @mysqli_connect("p:" . $provider["Host"], $provider["User"], $provider["Password"], $provider["Database"], $provider["Port"]);
        } else {
            $link_ID = @mysqli_connect($provider["Host"], $provider["User"], $provider["Password"], $provider["Database"], $provider["Port"]);
        }
        $no_connections++;
        if (!$link_ID) {
			$str_error = mysqli_connect_error();
			$error_id = rand();
			file_put_contents(__DIR__ . "/msg_sql.log", "Error id: \"$error_id\"; Message: \"$str_error\";\n", FILE_APPEND);
			exit('mysqli: holis Disculpe las Molestias, tuvimos un error inesperado, intente mas tarde por favor. CODE:' . $error_id);
			return 0;
        }
    }
    return $link_ID;
}

function close_connection($link_ID) {
	mysqli_close($link_ID);
}  


function DbExecute($qry, $is_return_err_msg = false) {
    global $a_db_errors;
    global $db_error_count;
    global $link_ID;
	$is_close_at_exit = 0;
	if (!$link_ID) {
		$link_ID = DBConnect();
		$is_close_at_exit = 0;
	}
	
	// ejecucion
	$result = '';

    //n $qry = mysqli_real_escape_string($link_ID, $qry);
    //echo $qry; exit();
    $result = mysqli_query($link_ID, $qry);
    if (!$result) {
		$str_error = mysqli_error($link_ID);
        $a_db_errors[] = mysqli_error($link_ID);
		if ($is_return_err_msg) {
			return $str_error;
		}
		$error_id = rand();
		file_put_contents(__DIR__ . "/msg_sql.log", "Error id: \"$error_id\"; Message: \"$str_error\"; Query: \"$qry\";\n", FILE_APPEND);
		exit($str_error  . ' mysqli: Disculpe las Molestias, tuvimos un error inesperado, intente mas tarde por favor. CODE:' . $error_id);
		return 0;
	}    
	
	return $result;
}  


function DbCommit() {
    global $link_ID;
    global $a_db_errors;
    $is_errors = DbIsErrors();
    if ($link_ID) {
        if (!$is_errors) {
            mysqli_commit($link_ID);
        } else {
            mysqli_rollback($link_ID);
        }
    }
}

function DbRollBack() {
    global $link_ID;
    if ($link_ID) {
        mysqli_rollback($link_ID);
    }
}

function DbIsErrors() {
    global $db_error_count;
    $a_php_errors = error_get_last();
    $is_php_error = false;
    if (is_array($a_php_errors)) {
        foreach ($a_php_errors AS $idx => $val) {
            if ($idx == 'type') {
                if ($val == E_DEPRECATED) {
                    $is_php_error = false;
                } else {
                    $is_php_error = true;
                }
            }
        }
    }
    if ($db_error_count > 0 or $is_php_error) {
        return true;
    } else {
        return false;
    }
}

function DbErrors() {
    global $a_db_errors;
    return $a_db_errors;
}

function DbQryToArray($qry, $is_result_array = true, $is_first_field_name = false, $is_pair_list = false) {
	$result = DbExecute($qry);
	$num_rows = mysqli_num_rows($result);
	$num_fields = mysqli_num_fields($result);
	$a_rows = Array();
	if ($num_rows > 0) {
		for ($i = 1; $i <= $num_rows; $i++) {
			$a_rows_b = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$obj =  mysqli_fetch_field_direct($result, 0);
			$key = ($obj) ? $obj->name : "";
			if ($num_fields >= 2) {
				$obj =  mysqli_fetch_field_direct($result, 1);
				$val = ($obj) ? $obj->name : "";
			} else {
				$val = "";
			}
            if ($is_result_array) {
                if ($is_first_field_name) {
                    $a_rows[$a_rows_b[$key]] = $a_rows_b;
                } else {
                    $a_rows[] = $a_rows_b;
                }
            } else {
				if ($is_first_field_name) {
					if ($is_pair_list and strlen($val)) {
						$a_rows[$a_rows_b[$key]] = $a_rows_b[$val]; // 1er campo como key y segundo campo como valor
					} else {
						$a_rows[$a_rows_b[$key]] = $a_rows_b[$key];
					}
				} else {
					$a_rows[] = $a_rows_b[$key];
				}
            }
		}
	}
	return $a_rows;
}

// transforma el primer campo de una consulta de multiples registros, a valores separados por coma
function DbQryToArrayValues($qry){
	$a_data = DbQryToArray($qry, false, true);
	$data = implode(",", $a_data);
	
	return $data;
}


function DbQryToRow($qry, $link_ID = null) {
	$result = DbExecute($qry);
	$a_row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	return $a_row;
}


function DbGetFirstFieldValue($qry, $link_ID = null) {
	$result = DbExecute($qry);
    $first_field_value = '';
    if ($result instanceof mysqli_result) {
        $a_row = mysqli_fetch_row($result);
    }
    $first_field_value = $a_row[0];
	return $first_field_value;
}


function LastIdAuto($link_ID = null) {
    if (!$link_ID) {
        $link_ID = DbGetLinkId();
    }
	$valor = mysqli_insert_id($link_ID);
	return $valor;
}


function GetNextIdAuto($table, $link_ID = null) {
    if (!$link_ID) {
        $link_ID = DbGetLinkId();
    }
	if (strlen($table)) {
		$qry = "SHOW TABLE STATUS LIKE '$table'";
		$row = DbQryToRow($qry);
		$next_id = (isset($row['Auto_increment'])) ? $row['Auto_increment'] : 0; // 0 cuando la tabla no tiene auto increment o no tiene ningun registro (como saber la diferencia?)
	} else {
		$next_id = -2; // -2 valor si no indican tabla (no deberia entrar nunca aqui)
	}
	return $next_id;
}


function DbGetLinkId() {
    global $link_ID;
    return $link_ID;
}

function GetUserData($id_user = '') {
	if (!strlen($id_user)) {
		$id_user = SessGetUserId();
	}
	if (strlen($id_user)) {
		$qry = "SELECT * FROM seg_usuarios WHERE IdUsuario = $id_user";
		$a_user = DbQryToRow($qry);
	}
	return $a_user;
}


function GetUserName($id_user = '', $type_result = 'FULL') {
	// tipos de resultado
	// FULL = Nombre, Apellido Paterno y Apellido Materno
	// NA = Nombre y Apellido Paterno
	// N = Solo nombre
	// A = Solo apellido Paterno
	// U = Usuario (por ejemlo "ofuente")
	$user = "";
	$a_user = GetUserData($id_user);
	if ($type_result == 'FULL') {
		$user = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno'] . ' ' . $a_user['ApellidoMaterno']);
	}
	if ($type_result == 'NA') {
		$user = trim($a_user['Nombre'] . ' ' . $a_user['ApellidoPaterno']);
	}
	if ($type_result == 'N') {
		$user = trim($a_user['Nombre']);
	}
	if ($type_result == 'A') {
		$user = trim($a_user['ApellidoPaterno']);
	}
	if ($type_result == 'A') {
		$user = trim($a_user['UserName']);
	}
	if (!strlen($user)) {
		$user = trim($a_user['UserName']);
	}
	if (!strlen($user)) {
		$user = $id_user;
	}
	return $user;
}


function IsUserLogged() {
	$id_user = SessGetUserId();
	$ret = (strlen($id_user)) ? true : false;
	
	return $ret;
}

function setLogin($id_user, $dt_db_today, $hora) {
    $ret = -1;
    if (strlen($id_user) and strlen($dt_db_today) and strlen($hora)) {
        SessSetUserId($id_user);
        // $dir_ip = getClientIp();
        // $qry = "UPDATE seg_usuarios SET FechaLastLogin = $dt_db_today, HoraLastLogin = '$hora', DirIpLastLogin = '$dir_ip' WHERE IdUsuario = $id_user";
        // DbExecute($qry);
        $ret = 1;
    }
    return $ret;
}

// Obtiene informacion de la base de datos de la tienda.
// SI ya se le envia esa informacion ya no se conecta a la BD local para leer esa informacion, solo revisa el archivo de configuracion
function getDbData($tienda = "", $db_name = "", $ip_address = "") {

	if (!strlen($db_name) and !strlen($ip_address)) {
		$qry = "SELECT CdTienda, DbName, DbNameReplica, IPAddress, IPAddressReplica FROM tiendas WHERE CdTienda = '$tienda'";
		$a_row = DbQryToRow($qry); // OJO AQUI SE USA conexion con MySQL
		$db_name = (isset($a_row['DbNameReplica'])) ? $a_row['DbNameReplica'] : ""; // la deja vacia para que tome la BD de la configuracion
		$ip_address = (isset($a_row['IPAddressReplica'])) ? $a_row['IPAddressReplica'] : ""; // si no la consigue la deja vacia para que tome la IP de la conf
	}
    if (!strlen($db_name)) {
        global $db_connection_ms;
        $provider = $db_connection_ms["bisweb_db"];
        $db_name = $provider["Database"]; // puede devolver vacio para forzar la conexion con el DSN
        $ip_address = $provider["Host"]; // puede devolver vacio para forzar la conexion con el DSN
    }
    $a_db_data = array('cd_tienda' => $tienda, 'db_name' => $db_name, 'ip_address' => $ip_address);
    
    return $a_db_data;
}


function sqlEscape($sql) {
 
    $fix_str = stripslashes($sql);
    $fix_str = str_replace("'", "''", $fix_str);
    $fix_str = str_replace("\0", "[NULL]", $fix_str);

    return $fix_str;

}

function sqlsrv_escape($data) {
    if(is_numeric($data))
        return $data;
    $unpacked = unpack('H*hex', $data);
    return '0x' . $unpacked['hex'];
}

// funcion de pagincion en la base de datos
function DbGetPagingData($sTable, $start, $length, $pOrder, $sIndexColumn, $aColumns, $searchValue, &$get, $custom_where = "", $custom_from = "", $aColumnsClean = array()) {
    $aQueryRet = array();
    /* 
     * Paging
     */
    $sLimit = "";
    if ( isset( $start ) && $length != '-1' ) {
        $sLimit = "LIMIT ". ( $start ).", ".
            ( $length );
    }
    $aQueryRet['sLimit'] = $sLimit;

    /*
     * Ordering
     */
    $sOrder_b = "ORDER BY " . $sIndexColumn;
    // $aColumnsClean = (!empty($aColumnsClean)) ? $aColumnsClean : $aColumns;
    if ( !empty( $pOrder ) ) {
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i < count($pOrder) ; $i++ ) {
            $columnNo = $pOrder[$i]['column'];
            $columnName = $aColumns[$columnNo];
			if (strpos($columnName, ' AS ') !== FALSE) {
				$columnName = substr($columnName, 0, strpos($columnName, ' AS '));
			}
            if ( $get['columns'][$columnNo][ 'orderable'] == "true" ) {
                $sOrder .= $columnName ."
                    ". ( $pOrder[$i]['dir'] ) .", ";
            }
        }
        
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" ) {
            $sOrder = "";
        }
    }
    if (!strlen($sOrder)) {
        $sOrder = $sOrder_b;
    }
    $aQueryRet['sOrder'] = $sOrder;

    /* 
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "";
    if ( isset($searchValue) && $searchValue != "" ) {
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            $columnNo = $i;
			$columnName = $aColumns[$i];
			if (strpos($columnName, ' AS ') !== FALSE) {
				$columnName = substr($columnName, 0, strpos($columnName, ' AS '));
			}
            if ( $get['columns'][$columnNo][ 'searchable'] == "true" ) {
				$sWhere .= $columnName." LIKE '%". ( $searchValue )."%' OR ";
            }
			
			
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }
	$aColumnsClean = (!empty($aColumnsClean)) ? $aColumnsClean : $aColumns;

    if (strlen($custom_where)) {
       $sWhere .= $custom_where;
    }
    
    /* Individual column filtering */ // not implemented
    // for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
    //     if ( isset($get['bSearchable_'.$i]) && $get['bSearchable_'.$i] == "true" && $get['sSearch_'.$i] != '' ) {
    //         if ( $sWhere == "" ) {
    //             $sWhere = "WHERE ";
    //         }
    //         else {
    //             $sWhere .= " AND ";
    //         }
    //         $sWhere .= $aColumns[$i]." LIKE '%" . ($get['sSearch_'.$i])."%' ";
    //     }
    // }

    $aQueryRet['sWhere'] = $sWhere;

    /*
     * SQL queries
     * Get data to display
     */
    if (!strlen($custom_from)) {
        $sQuery = "
            SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
        ";
    } else {
        // si es custom, cuidar que los campos sean los mismos de la tabla para que no afecten al where
        // cuidar tambien que los nombres de las columnas sean los mismos que estan definidos en $aColumns;
        $sQuery = "
            SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
            $custom_from
            $sWhere
            $sOrder
            $sLimit
        ";
    }
    // echo "<pre>" . $sQuery . "</pre>"; exit();
    $rResult = DbExecute( $sQuery );
    $aQueryRet['rResult'] = $rResult;
    
    /* Data set length after filtering */
    if (!strlen($custom_from)) {
        $sQuery = "
            SELECT COUNT(*)
            FROM   $sTable
            $sWhere";
            // -- $sOrder
            // -- $sLimit
        // ";
    } else {
        $sQuery = "
            SELECT COUNT(*)
            $custom_from
            $sWhere";
            // -- $sOrder
            // -- $sLimit
        // ";
    }
	// echo "<pre>" . $sQuery . "</pre>"; exit();
    $iFilteredTotal = DbGetFirstFieldValue($sQuery);
    $aQueryRet['iFilteredTotal'] = $iFilteredTotal;

    /* Total data set length */
    if (!strlen($custom_from)) {
        $sQuery = "
            SELECT COUNT(".$sIndexColumn.")
            FROM   $sTable
        ";
    } else {
        $sQuery = "
            SELECT COUNT(".$sIndexColumn.")
            $custom_from
        ";
    }
    // $rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
    // $aResultTotal = mysql_fetch_array($rResultTotal);
    $iTotal = 0; //DbGetFirstFieldValue($sQuery); // Esta desactivado mostrar el total de registros de la tabla
    $aQueryRet['iTotal'] = $iTotal;

    return $aQueryRet;

}

function LastIdAutoTable($table_name, $link_ID = null) {
	$qry = "SELECT IDENT_CURRENT('$table_name')AS INDENT_CURRENT";
	$valor = DbGetFirstFieldValue($qry, $link_ID);
	return $valor;
}

?>