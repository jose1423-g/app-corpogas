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
$email_supervisor = (isset($_REQUEST['EmailSupervisor'])) ? $_REQUEST['EmailSupervisor'] : "";
$nombre_corto = (isset($_REQUEST['NombreCorto'])) ? $_REQUEST['NombreCorto'] : "";
$tel_supervisor = (isset($_REQUEST['TelSupervisor'])) ? $_REQUEST['TelSupervisor'] : "";

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
		$qry = "SELECT EstacionServicio, NoEstacion, EsActivo, EmailSupervisor, NombreCorto, TelSupervisor
                FROM estaciones WHERE IdEstacion = $id_estacion";
		$a_estacion = DbQryToRow($qry);

		$estacion = utf8_encode($a_estacion['EstacionServicio']);
		$a_estacion['EstacionServicio'] = $estacion;

		$nombre_corto = utf8_encode($a_estacion['NombreCorto']);
		$a_estacion['NombreCorto'] = $nombre_corto;

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
	} else if (!strlen($email_supervisor)) {
        $msg = 'El campo email supervisor es requerido';
		$result = -1;
	} else if (!strlen($nombre_corto)) {
		$msg = 'El campo nombre corto de la estacion es requerido';
		$result = -1;
	} else if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		$estacion = utf8_decode($estacion);
		$nombre_corto = utf8_decode($nombre_corto);

		if (strlen($id_estacion)) {
	
			$qry = "UPDATE estaciones 
                    SET EstacionServicio = '$estacion', 
                    NoEstacion = '$num_estacion', 
					EmailSupervisor = '$email_supervisor',
                    EsActivo = $estatus,
					NombreCorto = '$nombre_corto',
					TelSupervisor = '$tel_supervisor'
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

			$qry = "INSERT INTO estaciones (EstacionServicio, NoEstacion, EsActivo, EmailSupervisor, NombreCorto, TelSupervisor) 
					VALUES 
					('$estacion','$num_estacion',$estatus, '$email_supervisor', '$nombre_corto', '$tel_supervisor')";
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