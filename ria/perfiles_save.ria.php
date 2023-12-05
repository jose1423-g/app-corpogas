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
$id_perfil = (isset($_REQUEST['UsuarioPerfilId'])) ? $_REQUEST['UsuarioPerfilId'] : '';
$perfil = (isset($_REQUEST['NombrePerfil'])) ? $_REQUEST['NombrePerfil'] : '';
$estatus = (isset($_REQUEST['EsActivo'])) ? 1 : 0;
// $num_estacion = (isset($_REQUEST['NoEstacion'])) ? $_REQUEST['NoEstacion'] : '';

$fecha_hoy = "";
$fecha_hoy =  DtDbToday($fecha_hoy);


$msg = '';
$result = 0;

if ($op == 'loadPerfiles') {
	if (!strlen($id_perfil)) {
		$msg = 'Hubo un error, el perfil no fue seleccionado correctamente';
		$result = -1; 
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$qry = "SELECT NombrePerfil, EsActivo FROM seg_usuarioperfil WHERE UsuarioPerfilId = $id_perfil";
		$a_perfil = DbQryToRow($qry);

		$nombre = utf8_encode($a_perfil['NombrePerfil']);
		$a_perfil['NombrePerfil'] = $nombre;

		// $nombre = utf8_encode($a_estacion['IdUsuario_fk']);
		// $a_estacion['IdUsuario_fk'] = $nombre;
		
		$a_perfil['result'] = 1;
		$a_perfil['msg'] = $msg;
		$a_ret = $a_perfil;
	
		echo json_encode($a_ret);
		exit();
	}
//SAVE
} elseif ($op == 'save') {
	//$id_usuario = $id_usuario_save; // compatibility
	if (!strlen($perfil)) {
		$msg = 'El campo nombre del  perfil es requerido';
		$result = -1;
	} else if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		if (strlen($id_perfil)) {
	
			$qry = "UPDATE seg_usuarioperfil 
                SET NombrePerfil = '$perfil', 
                EsActivo =  $estatus,
                CapturadoPor = $id_user, 
                FechaCaptura =  $fecha_hoy
                WHERE UsuarioPerfilId = $id_perfil";
					
			$res_upd = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_upd)) {
				$msg = 'Error no se pudo actualizar el perfil' . $res_upd;
				$result = -1;
			} else {
				if (!$res_upd) {
					$msg = 'Error no se pudo actualizar el perfil';
					$result = -1;
				} else {
					$msg = 'Perfil actualizado con exito';
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

			$qry = "INSERT INTO seg_usuarioperfil (NombrePerfil, EsActivo, CapturadoPor, FechaCaptura) VALUES ('$perfil', $estatus, $id_user, $fecha_hoy)";
			$res_ins = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_ins)) {
				$msg = 'Error al agregar el perfil: ' . $res_ins;
				$result = -1;
			} else {
				if (!$res_ins) {
					$msg = 'Error al agregar el perfil';
					$result = -1;
				} else {    
                    // $id_usuario = LastIdAutoTable('Medicos');
					// $qry = "SELECT MAX(IdMedico) AS id_medico_last FROM Medicos";
					// $id_usuario = DbGetFirstFieldValue($qry);
                    $msg = 'Perfil agregado con exito';
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