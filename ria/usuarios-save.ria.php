<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
	
session_start();

// agrega o guarda datos del usuario
	
// recupera datos
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_usuario = (isset($_REQUEST['IdUsuario'])) ? $_REQUEST['IdUsuario'] : ""; // si no trae id, es que es nuevo..
$user_name = (isset($_REQUEST['UserName'])) ? $_REQUEST['UserName'] : "";
$apellido_paterno = (isset($_REQUEST['ApellidoPaterno'])) ? $_REQUEST['ApellidoPaterno'] : "";
$apellido_materno = (isset($_REQUEST['ApellidoMaterno'])) ? $_REQUEST['ApellidoMaterno'] : "";
$nombre = (isset($_REQUEST['Nombre'])) ? $_REQUEST['Nombre'] : "";
$usuario_perfil_id = (isset($_REQUEST['UsuarioPerfilId_fk'])) ? $_REQUEST['UsuarioPerfilId_fk'] : "";
$passwd = (isset($_REQUEST['passwd'])) ? $_REQUEST['passwd'] : "";
$is_passwd_mod = (isset($_REQUEST['IsPasswdMod'])) ? $_REQUEST['IsPasswdMod'] : "0";
$es_activo = (isset($_REQUEST['EsActivo'])) ? 1 : 0;

$msg = "";
$result = 0;

// en proceso op=del
if ($op == 'showusuario') {
	if (!strlen($id_usuario)) {
		$msg = 'Hubo un error, la categoria no fue seleccionado correctamente';
		$result = -1;
	} else if (true) {

	}  else if (true) {

	}  else if (true) {

	}
	
	elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$qry = "SELECT Usuername, Nombre, ApellidoPaterno, ApellidoMaterno, passwd, UsuarioPerfilId_fk, EsActivo, Email, telefono, IdEstacion_fk
		 		FROM seg_usuarios WHERE IdUsuario = $id_usuario";
		$a_usuarios = DbQryToRow($qry);

		$user_name = utf8_decode($user_name);
		$apellido_paterno = utf8_decode($apellido_paterno);
		$apellido_materno = utf8_decode($apellido_materno);
		$nombre = utf8_decode($nombre);
		$passwd = utf8_decode($passwd);
		

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


} else {
	// valida datos
	$es_error = 0;
	if (!strlen($user_name)) {
		$es_error = 1;
		$output = "El campo Usuario es necesario";
	} elseif (!strlen($nombre)) {
		$es_error = 1;
		$output = "El campo Nombre es necesario";
	} elseif (!strlen($apellido_paterno)) {
		$es_error = 1;
		$output = "El campo Apellido Paterno es necesario";
	}
	
	// transforma para guardar o insertar
	
	// valida que UserName no exista en otro usuario
	

	$user_name_existe = DbGetFirstFieldValue($qry);
	if (strlen($user_name_existe)) {
		$es_error = 1;
		$output = "El nombre de Usuario '$user_name' ya existe";
	}

	if ($es_error == 0) {
		// echo "dentro del if";
		// exit();
		if (strlen($id_usuario)) {
			// edicion
            if ($is_passwd_mod == 1) {
                $passwd = crypt($passwd, "doxasystems"); // mover a libreria
            }
			$qry = "UPDATE seg_usuarios
					SET UserName = '$user_name',
						ApellidoPaterno = '$apellido_paterno',
						ApellidoMaterno = '$apellido_materno',
						Nombre = '$nombre',
						UsuarioPerfilId_fk = $usuario_perfil_id,
						passwd = '$passwd',
						EsActivo = $es_activo
					WHERE IdUsuario = $id_usuario";
			$result = DbExecute($qry, true);
			if ($result) {
				if (is_string($result)) {
					$output = "Hubo un error al guardar los cambios";
				} else {
					$output = "Se guardaron los cambios";
					$result = 1;
				}
			} else {
				$output = "Hubo un error al guardar los cambios";
				$result = -1;
			}
		} else {
			// nuevo
			$passwd = crypt($passwd, "doxasystems"); // mover a libreria
			if (!strlen($usuario_perfil_id)) {
				$usuario_perfil_id = 'NULL';
			}
			$qry = "INSERT INTO seg_usuarios (UserName, ApellidoPaterno, ApellidoMaterno, Nombre, UsuarioPerfilId_fk, passwd, EsActivo)
					VALUES('$user_name', '$apellido_paterno', '$apellido_materno', '$nombre', $usuario_perfil_id, '$passwd', $es_activo)";
			echo $qry;
			exit();
			$result = DbExecute($qry, true);
			if ($result) {
				if (is_string($result)) {
					$output = "Hubo un error al agregar el usuario";
				} else {
					$output = "El usuario se agregó correctamente";
					$result = 1;
				}
			} else {
				$output = "Hubo un error al agregar el usuario";
				$result = -1;
			}
		}
	}
}
$a_ret = array('result' => $result, 'msg' => $output);
echo json_encode($a_ret);
?>