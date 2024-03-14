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

$msg = '';
$result = 0;

if ($op == 'ShowImg') {
	if (!strlen($id_producto)) {
		$msg = 'Hubo un error, No se selecciono el producto correctamente';
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

} else if($op == 'delete') {
	if (!strlen($id_producto)) {
		$msg = 'Error el producto no fue seleccionado';
		$result = -1;
	} else if (!strlen($id_user)) {
        $msg = 'Su session ha expirado';
		$result = -1;
    } else {

        $qry = "SELECT EsActivo FROM productos WHERE IdProducto = $id_producto";
        $es_activo = DbGetFirstFieldValue($qry);

        if ($es_activo == 1) {
            $qry = "UPDATE productos SET EsActivo = 0 WHERE IdProducto = $id_producto";
            $res_upd = DbExecute($qry);
            DbCommit();
            if (is_string($res_upd)) {
                $msg = 'No se pudo desactivar el producto:' . $res_upd;
                $result = -1;
            } else {
                if (!$res_upd) {
                    $msg = 'Error al desactivar producto';
                    $result = -1;
                } else {
                    $msg = 'producto desactivado con éxito';
                    $result = 1;
                }
            }
        } else {
            $qry = "UPDATE productos SET EsActivo = 1 WHERE IdProducto = $id_producto";
            $res_upd = DbExecute($qry);
            DbCommit();
            if (is_string($res_upd)) {
                $msg = 'No se pudo activar el producto:' . $res_upd;
                $result = -1;
            } else {
                if (!$res_upd) {
                    $msg = 'Error al activar producto';
                    $result = -1;
                } else {
                    $msg = 'producto activar con exito';
                    $result = 1;
                }
            }
        }
        
    }

}

$a_ret = array('result' => $result, 'msg' => $msg, 'id_usuario' => $id_usuario);
echo json_encode($a_ret);
?>