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
$id_categoria = (isset($_REQUEST['id_categoria'])) ? $_REQUEST['id_categoria'] : '';
$categoria = (isset($_REQUEST['Categoria'])) ? $_REQUEST['Categoria'] : '';
$id_usuarios_fk = (isset($_REQUEST['IdUsuario_fk'])) ? $_REQUEST['IdUsuario_fk'] : '';
$estatus = (isset($_REQUEST['EsActivo'])) ? 1 : 0;

$fecha_hoy = "";
$fecha_hoy =  DtDbToday($fecha_hoy);


$msg = '';
$result = 0;

if ($op == 'loadCategoria') {
	if (!strlen($id_categoria)) {
		$msg = 'Hubo un error, la categoria no fue seleccionado correctamente';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$qry = "SELECT t1.Categoria, t1.EsActivo,  t1.IdUsuario_fk  
				FROM productos_categorias t1
				WHERE IdCategoria = $id_categoria";
		$a_categoria = DbQryToRow($qry);

		$categoria = utf8_encode($a_categoria['Categoria']);
		$a_categoria['Categoria'] = $categoria;

		$nombre = utf8_encode($a_categoria['IdUsuario_fk']);
		$a_categoria['IdUsuario_fk'] = $nombre;
		

		$a_categoria['result'] = 1;
		$a_categoria['msg'] = $msg;
		$a_ret = $a_categoria;
	
		echo json_encode($a_ret);
		exit();
	}
//SAVE
} elseif ($op == 'save') {
	//$id_usuario = $id_usuario_save; // compatibility
	if (!strlen($categoria)) {
		$msg = 'El campo categoria es requerido';
		$result = -1;
	} else if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		if (strlen($id_categoria)) {
		
			$categoria = utf8_decode($categoria);

			if (!strlen($id_usuarios_fk)) {
				$id_usuarios_fk = 'NULL';
			}

			$qry = "UPDATE productos_categorias 
					SET Categoria = '$categoria', 
						EsActivo = $estatus, 
						IdUsuario_fk = $id_usuarios_fk 
					WHERE IdCategoria = $id_categoria";
					
			$res_upd = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_upd)) {
				$msg = 'No se pudo actualizar la categoria' . $res_upd;
				$result = -1;
			} else {
				if (!$res_upd) {
					$msg = 'Error al actualizar la categoria';
					$result = -1;
				} else {
					$msg = 'Categoria actualizada con exito';
					$result = 1;
				}
			}
		} else {

			$categoria = utf8_decode($categoria);

			if (!strlen($id_usuarios_fk)) {
				$id_usuarios_fk = 'NULL';
			}

			// $qry = "SELECT MAX(IdMedico) AS id_medico_last FROM Medicos";
			// $id_medico_last = DbGetFirstFieldValue($qry);
			// $id_medico_next = $id_medico_last + 1;

			$qry = "INSERT INTO productos_categorias (Categoria, EsActivo, IdUsuario_fk) VALUES ('$categoria', $estatus, $id_usuarios_fk)";
			$res_ins = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_ins)) {
				$msg = 'Error al agregar la categoria: ' . $res_ins;
				$result = -1;
			} else {
				if (!$res_ins) {
					$msg = 'Error al agregar la categoria';
					$result = -1;
				} else {    
                    // $id_usuario = LastIdAutoTable('Medicos');
					// $qry = "SELECT MAX(IdMedico) AS id_medico_last FROM Medicos";
					// $id_usuario = DbGetFirstFieldValue($qry);
                    $msg = 'categoria agregada con exito';
                    $result = 1;
				}
			}
		}
	}
} elseif($op == 'delete') {

	if (!strlen($id_usuario)) {
		$msg = 'Hubo un error, el usuario no fue seleccionado correctamente';
		$result = -1;
	}
	$qry = "DELETE FROM productos_categorias WHERE IdCategoria = $id_categoria";
	$res_upd = DbExecute($qry);
	DbCommit();
	if (is_string($res_upd)) {
		$msg = 'No se pudo eliminar la categoria:' . $res_upd;
		$result = -1;
	} else {
		if (!$res_upd) {
			$msg = 'Error al eliminar la categoria';
			$result = -1;
		} else {
			$msg = 'Categoria eliminada con exito';
			$result = 1;
		}
	}

}

$a_ret = array('result' => $result, 'msg' => $msg, 'id_usuario' => $id_usuario);
echo json_encode($a_ret);
?>