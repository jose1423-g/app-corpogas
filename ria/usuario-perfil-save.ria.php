<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
	
session_start();

// guarda los datos del usuario

// recupera datos
// header
$id_user = SessGetUserId();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_usuario = (isset($_REQUEST['id_usuario'])) ? $_REQUEST['id_usuario'] : '';
$passwd_actual = (isset($_REQUEST['passwd_actual'])) ? utf8_decode($_REQUEST['passwd_actual']) : '';
$passwd_nuevo = (isset($_REQUEST['passwd_nuevo'])) ? utf8_decode($_REQUEST['passwd_nuevo']) : '';
$passwd_confirma = (isset($_REQUEST['passwd_confirma'])) ? utf8_decode($_REQUEST['passwd_confirma']) : '';


$msg = '';
$result = 0;

if ($op == 'changePwd') {
	if (!strlen($id_usuario)) {
		$msg = 'Hubo un error, el usuario no fue seleccionado correctamente';
	} elseif (!strlen($passwd_nuevo)) {
		$msg = 'La contraseña nueva no puede dejarse vacia';
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		
		// revisa ue la contraseña actual sea correcta
		$qry = "SELECT passwd FROM seg_usuarios WHERE IdUsuario = $id_usuario";
		$hashed_password = DbGetFirstFieldValue($qry);
		if (hash_equals($hashed_password, crypt($passwd_actual, $hashed_password))) {
			// valida que la nueva contraseña sea igual a la confirmacion
			if ($passwd_nuevo == $passwd_confirma) {
			// guarda
				$passwd_nuevo = crypt($passwd_nuevo, "doxasystems"); // mover a libreria
				$qry = "UPDATE seg_usuarios SET passwd = '$passwd_nuevo' WHERE IdUsuario = $id_usuario";
				$res = DbExecute($qry, true);
				if ($res) {
					if (is_string($res)) {
						$msg = "Hubo un error al guardar los cambios";
					} else {
						$msg = "Se guardaron los cambios";
						$result = 1;
					}
				} else {
					$msg = "Hubo un error al guardar los cambios";
					$result = -1;
				}
			} else {
				$msg = 'La nueva contraseña no es igual a su confirmación';
			}
		} else {
			$msg = 'La contraseña actual no es correcta';
		}
	}
}

$a_ret = array('result' => $result, 'msg' => $msg);
echo json_encode($a_ret);
?>