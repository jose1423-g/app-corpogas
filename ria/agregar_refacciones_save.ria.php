<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");

session_start();

// recupera datos
// header
$id_user = SessGetUserId();

$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_producto =  (isset($_REQUEST['Id_producto'])) ? $_REQUEST['Id_producto'] : '';
$id_partida =  (isset($_REQUEST['id_partida'])) ? $_REQUEST['id_partida'] : '';
$id_categoria = (isset($_REQUEST['IdCategoria_fk'])) ? $_REQUEST['IdCategoria_fk'] : '';
$data_json = (isset($_REQUEST['data_json'])) ? $_REQUEST['data_json'] : '';

// $fecha_hoy = "";
// $fecha_hoy =  DtDbToday($fecha_hoy);

$msg = '';
$result = 0;

if ($op == 'ShowImg') {
	if (!strlen($id_producto)) {
		$msg = 'Hubo un error, No se pudo cargar la imagen';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$qry = "SELECT img FROM productos WHERE Idproducto = $id_producto";
		$a_producto = DbQryToRow($qry);	

		$a_producto['result'] = 1;
		$a_producto['msg'] = $msg;
		$a_ret = $a_producto;
	
		echo json_encode($a_ret);
		exit();
	}

} else if ($op == 'SaveData') {
    
    if (!strlen($id_categoria)) {
        $msg = 'Erro no a seleccionado ninguna categoria';
        $result = -1;
    } 

    $qry = "SELECT * FROM solicitudes ORDER BY IdSolicitud DESC LIMIT 1";
    $id_solicitud  =  DbGetFirstFieldValue($qry);


    $qry = "SELECT * FROM solicitudes WHERE IdSolicitud = $id_solicitud AND IdCategoria_fk = $id_categoria";
    $resp  =  DbGetFirstFieldValue($qry);

    if (strlen($resp)) {
        $datos = json_decode($data_json, true);   
        $qry = "SELECT IdPartida FROM productos_solicitud ORDER BY IdPartida DESC LIMIT 1";
        $i  =  DbGetFirstFieldValue($qry);
        $i = $i + 1;
        foreach ($datos as $id => $cantidad) {
            $qry = "INSERT INTO productos_solicitud (IdPartida, IdProducto_fk, Cantidad, IdSolicitud) VALUES ($i, $id, $cantidad, $id_solicitud)";
            $res_upd = DbExecute($qry);
            DbCommit();
            if (is_string($res_upd)) {
                $msg = 'Error al  agregar los productos:' . $res_upd;
                $result = -1;
            } else {
                if (!$res_upd) {
                    $msg = 'Error al  agregar los productos';
                    $result = -1;
                } else {
                    $msg = 'Productos agregados';
                    $result = 1;
                }
            }        
        }
    } else {
        $qry = "SELECT * FROM solicitudes WHERE IdSolicitud = $id_solicitud AND IdCategoria_fk IS NOT NULL";
        $value  =  DbGetFirstFieldValue($qry);
        if (strlen($value)) {
            $msg = 'Error una solicitud no puede contener mas de una categoria';
            $result = -1;
        } else {
            $qry = "UPDATE solicitudes SET IdCategoria_fk = $id_categoria WHERE IdSolicitud = $id_solicitud";
            $res_upd = DbExecute($qry);
            DbCommit();
            if (is_string($res_upd)) {
                $msg = 'Error al enviar la solicitud:' . $res_upd;
                $result = -1;
            } else {
                if (!$res_upd) {
                    $msg = 'Error al enviar la solicitud';
                    $result = -1;
                } else {
                    $datos = json_decode($data_json, true);   
                    $i = 0;
                    foreach ($datos as $id => $cantidad) {
                        $i = $i + 1;
                        $qry = "INSERT INTO productos_solicitud (IdPartida, IdProducto_fk, Cantidad, IdSolicitud) VALUES ($i, $id, $cantidad, $id_solicitud)";
                        $res_upd = DbExecute($qry);
                        DbCommit();
                        if (is_string($res_upd)) {
                            $msg = 'Error al enviar los datos:' . $res_upd;
                            $result = -1;
                        } else {
                            if (!$res_upd) {
                                $msg = 'Error al enviar los datos';
                                $result = -1;
                            } else {
                                $msg = 'Solicitud enviada con exito';
                                $result = 1;
                            }
                        }        
                    }
                }
            } 
        }
    }
    $a_ret = array('result' => $result, 'msg' => $msg);
    echo json_encode($a_ret);
    exit();

} else if ($op == 'ShowProducts') {
    $qry = "SELECT * FROM solicitudes ORDER BY IdSolicitud DESC LIMIT 1";
    $id_solicitud  =  DbGetFirstFieldValue($qry);

    $qry = "SELECT t2.IdPartida, t3.Referencia, t3.NombreRefaccion, t2.Cantidad, t2.IdPartida AS icons
            FROM solicitudes t1
            LEFT JOIN productos_solicitud t2 ON t1.IdSolicitud = t2.IdSolicitud
            LEFT JOIN productos t3 ON t2.IdProducto_fk = t3.IdProducto
            WHERE t1.IdSolicitud = $id_solicitud ORDER BY t2.IdPartida ASC";
    $a_producto = DbQryToArray($qry, true);	
    
    $a_data = array();

    foreach ($a_producto as $row){
        $a_data_line = array();
    
        $id_partida = $id = $a_data_line['IdPartida'] = (string) $row['IdPartida'];
        $referencia =  $a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
        $refaccion =  $a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
        $cantidad =  $a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);
        $icons =  $a_data_line['icons'] = (string) $row['icons'];
        if (!strlen($id_partida)) {
            $a_data_line[] = '';
        } else if (!strlen($referencia)) {
            $a_data[] =  $a_data;
        } else if (!strlen($refaccion)) {
            $a_data[] =  $a_data;
        } else if (!strlen($cantidad)) {
            $a_data[] =  $a_data;
        } else if (!strlen($icons)) {
            $a_data[] =  $a_data;
        } else {
        $a_data_line['IdPartida'] = (string) $row['IdPartida'];
        $a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
        $a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
        $a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);
        // $a_data_line['icons'] = (string) $row['icons'];

        $icons =  "<button type='button' class='btn btn-danger btn-sm btn-delete-product' data-id='$id'><i class='fas fa-trash-alt'></i></button>";
        $a_data_line['icons'] = $icons;

        $a_data[] = $a_data_line; 
        }        
    }

    $json =  json_encode(array('data' => $a_data), true);
    echo $json;	
    

} else if ($op == 'Cancelar') {
    $qry = "SELECT * FROM solicitudes ORDER BY IdSolicitud DESC LIMIT 1";
    $id_solicitud  =  DbGetFirstFieldValue($qry);

    $qry = "DELETE FROM productos_solicitud WHERE IdSolicitud = $id_solicitud";
    $res_upd = DbExecute($qry);
    if (is_string($res_upd)) {
        $msg = 'Error al cancelar la solicitud:' . $res_upd;
        $result = -1;
    } else {
        if (!$res_upd) {
            $msg = 'Error al cancelar la solicitud';
            $result = -1;
        } else {
            $qry = "DELETE FROM solicitudes WHERE IdSolicitud = $id_solicitud";
            $res_upd = DbExecute($qry);
            if (is_string($res_upd)) {
                $msg = 'Error al cancelar la solicitud:' . $res_upd;
                $result = -1;
            } else {
                if (!$res_upd) {
                    $msg = 'Error al cancelar la solicitud';
                    $result = -1;
                } else {
                    $msg = 'Solicitud cancelada con exito';
                    $result = 1;
                }
            }
        }
    }   
    $a_ret = array('result' => $result, 'msg' => $msg);
    echo json_encode($a_ret);
    exit();
} else if ($op == 'Delete_product') {

    if (!strlen($id_partida)) {
        $msg = 'Error al seleccionar el producto';
        $result = -1;
    }

    $qry = "DELETE FROM productos_solicitud WHERE IdPartida = $id_partida";
    $res_upd = DbExecute($qry);

    if (is_string($res_upd)) {
        $msg = 'Error al eliminar el producto:' . $res_upd;
        $result = -1;
    } else {
        if (!$res_upd) {
            $msg = 'Error al eliminar el producto';
            $result = -1;
        } else {
            $msg = 'Productos eliminado con exito';
            $result = 1;
        }
    }   
    $a_ret = array('result' => $result, 'msg' => $msg);
    echo json_encode($a_ret);
    exit();
    
} else if ($op == 'Revision') {
    $qry = "SELECT * FROM solicitudes ORDER BY IdSolicitud DESC LIMIT 1";
    $id_solicitud  =  DbGetFirstFieldValue($qry);

    $qry  = "UPDATE solicitudes SET Estatus = 2 WHERE IdSolicitud = $id_solicitud";
    $res_upd = DbExecute($qry);
    if (is_string($res_upd)) {
        $msg = 'Error al enviar la solicitud:' . $res_upd;
        $result = -1;
    } else {
        if (!$res_upd) {
            $msg = 'Error al enviar la solicitud';
            $result = -1;
        } else {
            $msg = 'Solicitud enviada';
            $result = 1;
        }
    }
    $a_ret = array('result' => $result, 'msg' => $msg);
    echo json_encode($a_ret);
}

// $a_ret = array('result' => $result, 'msg' => $msg);
// echo json_encode($a_ret);
?>