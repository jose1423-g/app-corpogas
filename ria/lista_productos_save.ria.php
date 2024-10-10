<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/file_uploader.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");

session_start();

// recupera datos
// header
$id_user = SessGetUserId();

$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_producto =  (isset($_REQUEST['Id_producto'])) ? $_REQUEST['Id_producto'] : '';

$nombre_refaccion = (isset($_REQUEST['NombreRefaccion'])) ? $_REQUEST['NombreRefaccion'] : "";
$referencia = (isset($_REQUEST['Referencia'])) ? $_REQUEST['Referencia'] : "";
$no_serie = (isset($_REQUEST['NoSerie'])) ? $_REQUEST['NoSerie'] : "";
$id_categoria = (isset($_REQUEST['IdCategoria_fkP'])) ? $_REQUEST['IdCategoria_fkP'] : "";
$img = (isset($_FILES['uploadedfile'])) ? $_FILES['uploadedfile'] : "";
$img_exist = (isset($_REQUEST['img'])) ? $_REQUEST['img'] : "";
$es_activo = (isset($_REQUEST['EsActivoP'])) ? 1 : 0;

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

} else if ($op == 'GetProdcut') {     
    if (!strlen($id_producto)) {
		$msg = 'Hubo un error, No se selecciono el producto correctamente';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$qry = "SELECT NombreRefaccion, Referencia, NoSerie, IdCategoria_fk, img, EsActivo FROM productos WHERE IdProducto = $id_producto";
		$a_producto = DbQryToRow($qry);

        $a_producto['NombreRefaccion']  = utf8_encode($a_producto['NombreRefaccion']);

		$a_producto['result'] = 1;
		$a_producto['msg'] = $msg;
		$a_ret = $a_producto;
	
		echo json_encode($a_ret);
		exit();
	}

}else if ($op == 'updateproduct') {

    if (!strlen($nombre_refaccion)) {
		$msg = "El campo nombre refaccion es necesario";
		$result = -1;
	} elseif (!strlen($referencia)) {
		$msg = "El campo referencia es necesario";
		$result = -1;
	} elseif (!strlen($id_categoria)) {
		$msg = "El campo categoria es necesario";
		$result = -1;
	// } elseif (empty($img)) {
	// 	$msg = "El campo imagen es necesario";
	// 	$result = -1;
	} elseif (!strlen($id_user)){
		$msg = "Su session ha expirado";
		$result = -1;
	} else {
		
		$nombre_refaccion = utf8_decode($nombre_refaccion);
		$referencia = utf8_decode($referencia);

        //tiene algo
        $new_file_name = '';
		if(!empty($_FILES)){            
			$new_name = $_FILES['name'];            
			$a_result = UploadFileDoc($_FILES, $new_name, "../images/products/");            
			$msg = $a_result['msg'];
			$result = $a_result['result'];
			$new_file_name = $a_result['new_file_name'];

			if ($result != 1) {
				$new_file_name = '';
			}
		} else {            
            $new_file_name = $img_exist;
        }
        
		if(!strlen($no_serie)){
			$no_serie = 'NULL';
		}        

        $qry = "UPDATE productos SET
                NombreRefaccion = '$nombre_refaccion',
                Referencia = '$referencia',
                NoSerie = $no_serie,
                IdCategoria_fk = $id_categoria, 
                img = '$new_file_name', 
                EsActivo = $es_activo 
                WHERE IdProducto = $id_producto";

            $result = DbExecute($qry, true);
            if (is_string($result)) {
                $msg = "Hubo un error al actulizar el producto". $result;
                $result = -1;
            } else {
                if (!$result) {
                    $msg = "Hubo un error al actulizar el producto intente nueva mente";
                    $result = -1;
                } else {
                    $msg = "El productos se actualizo correctamente";
                    $result = 1;
                }
            }
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