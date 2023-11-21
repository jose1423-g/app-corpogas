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

// $fecha_hoy = "";
// $fecha_hoy =  DtDbToday($fecha_hoy);

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

        $qry = "SELECT img FROM productos WHERE IdProducto = $id_producto";
        $sign_filename = DbGetFirstFieldValue($qry);
        @unlink("../images/products/" . $sign_filename);

        $qry = "DELETE FROM productos WHERE IdProducto = $id_producto";
        $res_upd = DbExecute($qry);
        DbCommit();
        if (is_string($res_upd)) {
            $msg = 'No se pudo eliminar el producto:' . $res_upd;
            $result = -1;
        } else {
            if (!$res_upd) {
                $msg = 'Error al eliminar producto';
                $result = -1;
            } else {
                $msg = 'producto eliminado con exito';
                $result = 1;
            }
        }
    }

}

$a_ret = array('result' => $result, 'msg' => $msg, 'id_usuario' => $id_usuario);
echo json_encode($a_ret);
?>