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
$id_estacion = (isset($_REQUEST['IdEstacion'])) ? $_REQUEST['IdEstacion'] : '';
$estacion = (isset($_REQUEST['EstacionServicio'])) ? $_REQUEST['EstacionServicio'] : '';
$num_estacion = (isset($_REQUEST['NoEstacion'])) ? $_REQUEST['NoEstacion'] : '';
$estatus = (isset($_REQUEST['EsActivo'])) ? 1 : 0;

$fecha_hoy = "";
$fecha_hoy =  DtDbToday($fecha_hoy);


$msg = '';
$result = 0;

if ($op == 'loadEstacion') {
	if (!strlen($id_estacion)) {
		$msg = 'Hubo un error, la estacion no fue seleccionado correctamente';
		$result = -1; 
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$qry = "SELECT EstacionServicio, NoEstacion, EsActivo 
                FROM estaciones WHERE IdEstacion = $id_estacion";
		$a_estacion = DbQryToRow($qry);

		$estacion = utf8_encode($a_estacion['EstacionServicio']);
		$a_estacion['EstacionServicio'] = $estacion;
		
		$a_estacion['result'] = 1;
		$a_estacion['msg'] = $msg;
		$a_ret = $a_estacion;
	
		echo json_encode($a_ret);
		exit();
	}
//SAVE
} elseif ($op == 'save') {
	//$id_usuario = $id_usuario_save; // compatibility
	if (!strlen($estacion)) {
		$msg = 'El campo nombre de la estacion es requerido';
		$result = -1;
    } else if (!strlen($num_estacion)) {
        $msg = 'El campo numero de la estacion es requerido';
		$result = -1;
	} else if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		if (strlen($id_estacion)) {
		
			// $categoria = utf8_decode($categoria);
			$estacion = utf8_decode($estacion);
			// if (!strlen($id_usuarios_fk)) {
			// 	$id_usuarios_fk = 'NULL';
			// }
			$qry = "UPDATE estaciones 
                    SET EstacionServicio = '$estacion', 
                    NoEstacion = '$num_estacion', 
                    EsActivo = $estatus
                    WHERE IdEstacion = $id_estacion";
					
			$res_upd = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_upd)) {
				$msg = 'No se pudo actualizar la estacion' . $res_upd;
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

			// $categoria = utf8_decode($categoria);

			// if (!strlen($id_usuarios_fk)) {
			// 	$id_usuarios_fk = 'NULL';
			// }

			$estacion = utf8_decode($estacion);
			// $qry = "SELECT MAX(IdMedico) AS id_medico_last FROM Medicos";
			// $id_medico_last = DbGetFirstFieldValue($qry);
			// $id_medico_next = $id_medico_last + 1;

			$qry = "INSERT INTO estaciones (EstacionServicio, NoEstacion, EsActivo) VALUES ('$estacion','$num_estacion',$estatus)";
			$res_ins = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_ins)) {
				$msg = 'Error al agregar la estacion: ' . $res_ins;
				$result = -1;
			} else {
				if (!$res_ins) {
					$msg = 'Error al agregar la estacion';
					$result = -1;
				} else {    
                    // $id_usuario = LastIdAutoTable('Medicos');
					// $qry = "SELECT MAX(IdMedico) AS id_medico_last FROM Medicos";
					// $id_usuario = DbGetFirstFieldValue($qry);
                    $msg = 'Estacion agregada con exito';
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

$a_ret = array('result' => $result, 'msg' => $msg);
echo json_encode($a_ret);
?>