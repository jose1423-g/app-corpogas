<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
	
session_start();

$id_user = SessGetUserId();	

	
// recupera datos
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_usuario = (isset($_REQUEST['IdUsuario'])) ? $_REQUEST['IdUsuario'] : ""; // si no trae id, es que es nuevo..
$user_name = (isset($_REQUEST['UserName'])) ? $_REQUEST['UserName'] : "";
$nombre = (isset($_REQUEST['Nombre'])) ? $_REQUEST['Nombre'] : "";
$apellido_paterno = (isset($_REQUEST['ApellidoPaterno'])) ? $_REQUEST['ApellidoPaterno'] : "";
$apellido_materno = (isset($_REQUEST['ApellidoMaterno'])) ? $_REQUEST['ApellidoMaterno'] : "";
$passwd = (isset($_REQUEST['passwd'])) ? $_REQUEST['passwd'] : "";
$usuario_perfil_id = (isset($_REQUEST['UsuarioPerfilId_fk'])) ? $_REQUEST['UsuarioPerfilId_fk'] : "";
$email = (isset($_REQUEST['Email'])) ? $_REQUEST['Email'] : "";
$telefefono = (isset($_REQUEST['telefono'])) ? $_REQUEST['telefono'] : "";
$id_estacion = (isset($_REQUEST['IdEstacion_fk'])) ? $_REQUEST['IdEstacion_fk'] : "";
$es_activo = (isset($_REQUEST['EsActivo'])) ? 1 : 0;

$check_app = (isset($_REQUEST['check_app'])) ? $_REQUEST['check_app'] : '';

$s_is_show_all = (isset($_GET['s_is_show_all'])) ? $_GET['s_is_show_all'] : '';

// $estaciones = implode(",", $id_estacion);

$msg = "";
$result = 0;

// en proceso op=del
if ($op == 'loadUsuario') {
	if (!strlen($id_usuario)) {
		$msg = 'Hubo un error, el usuario no fue seleccionado correctamente';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		// t1.EmailSupervisor,
		$qry = "SELECT t1.Username, t1.Nombre, t1.ApellidoPaterno, t1.ApellidoMaterno, t1.passwd, t1.UsuarioPerfilId_fk, t1.EsActivo, t1.Email, 
		t1.telefono, t2.IdEstacion_fk
		FROM seg_usuarios t1
		LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUsuario_fk
		WHERE IdUsuario =  $id_usuario";
		$a_usuarios = DbQryToRow($qry);
		
		$user_name = utf8_encode($a_usuarios['Username']);
		$a_usuarios['Username'] = $user_name;

		$nombre = utf8_encode($a_usuarios['Nombre']);
		$a_usuarios['Nombre'] = $nombre;
		
		$paterno = utf8_encode($a_usuarios['ApellidoPaterno']);
		$a_usuarios['ApellidoPaterno'] = $paterno;
		
		$materno = utf8_encode($a_usuarios['ApellidoMaterno']);
		$a_usuarios['ApellidoMaterno'] = $materno;

		// $pass = utf8_encode($a_usuarios['passwd']);
		// $a_usuarios['passwd'] = $pass;

		$email = utf8_encode($a_usuarios['Email']);
		$a_usuarios['Email'] = $email;


		// $id_estacion = $a_usuarios['IdEstacion_fk'];
		// $estaciones = explode(',', $id_estacion);
		// $a_usuarios['IdEstacion_fk'] = $estaciones;

		$a_usuarios['result'] = 1;
		$a_usuarios['msg'] = $msg;
		$a_ret = $a_usuarios;

		echo json_encode($a_ret);
		exit();
	}

	$a_ret = array('result' => $result, 'msg' => $msg);
	echo json_encode($a_ret);

} else if ($op == 'save') {
	$id_estacion_long = count($id_estacion); 
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else if (!strlen($user_name)){
		$msg = 'El campo usuario es requerido';
		$result = -1;
	} else if (!strlen($nombre)){
		$msg = 'El campo nombre es requerido';
		$result = -1;
	} else if (!strlen($usuario_perfil_id)){
		$msg = 'El campo perfil es requerido';
		$result = -1;
	} else if (!strlen($email)){
		$msg = 'El campo email es requerido';
		$result = -1;	
	} else if (!strlen($passwd)){
		$msg = 'El campo password es requerido';
		$result = -1;
	} else {		
		$nombre = utf8_decode($nombre);
		$apellido_paterno =  utf8_decode($apellido_paterno);
		$apellido_materno =  utf8_decode($apellido_materno);

		if (!strlen($apellido_paterno)) {
			$apellido_paterno = '';
		}

		if (!strlen($apellido_materno)) {
			$apellido_materno = '';
		}
	

		if (strlen($id_usuario)) {
			$qry = "UPDATE seg_usuarios 
					SET UserName = '$user_name', 
					Nombre = '$nombre', 
					ApellidoPaterno = '$apellido_paterno', 
					ApellidoMaterno = '$apellido_materno', 
					passwd = '$passwd', 
					UsuarioPerfilId_fk = $usuario_perfil_id, 
					EsActivo = $es_activo, 
					Email = '$email', 
					telefono = '$telefefono'
					WHERE IdUsuario = $id_usuario";
			$res_ins = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_ins)) {
				$msg = 'Error al actualizar el usuario: ' . $res_ins;
				$result = -1;
			} else {
				if (!$res_ins) {
					$msg = 'Error al actualizar el usuario';
					$result = -1;
				} else {    
					$msg = 'Usuario actulizado con exito';
					$result = 1;					
				}
			}

		} else {
			$passwd = crypt($passwd, "doxasystems");

			$qry = "SELECT UserName FROM seg_usuarios WHERE UserName = '$user_name'";
			$user = DbGetFirstFieldValue($qry);
			if (isset($user)) {
				$msg = 'El usuario ya existe intente con otro';
				$result = -1;				
			} else {
				$qry = "INSERT INTO seg_usuarios (UserName, Nombre, ApellidoPaterno, ApellidoMaterno, passwd, UsuarioPerfilId_fk, EsActivo, Email, telefono)
						VALUES ('$user_name', '$nombre', '$apellido_paterno', '$apellido_materno', '$passwd', $usuario_perfil_id, $es_activo, '$email', '$telefefono')";				
				$res_ins = DbExecute($qry, true);
				DbCommit();
				if (is_string($res_ins)) {
					$msg = 'Error al crear el usuario: ' . $res_ins;
					$result = -1;
				} else {
					if (!$res_ins) {
						$msg = 'Error al crear el usuario';
						$result = -1;
					} else {
						$qry = "SELECT MAX(IdUsuario) FROM seg_usuarios";
						$id_usuario = DbGetFirstFieldValue($qry);
						$msg = 'Usuario creado con exito';
						$result = 1;
					}
				}
			}
		}
	}
	$a_ret = array('result' => $result, 'msg' => $msg,  'idusuario' => $id_usuario);
	echo json_encode($a_ret);

} else if ($op == 'showEstation') {

	// if (!strlen($id_usuario)) {
	// 	$msg = 'Hubo un error, el perfil no fue seleccionado';
	// 	$result = -1;
	// } else
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		// $a_values = array();
		if (strlen($id_usuario)) {
			$qry = "SELECT IdEstacion_fk FROM seg_estacionesusuario WHERE IdUsuario_fk = $id_usuario";
			$a_perfiles = DbQryToArray($qry);
		}

		$qry = "SELECT IdEstacion, IdEstacion AS Sel, EstacionServicio, NoEstacion 
				FROM estaciones WHERE EsActivo = 1";

		$a_data_b = DbQryToArray($qry, true);
		$a_data = array();
		$val = array();

		foreach($a_data_b as $a_dt) {
			$a_data_line = array();
			$a_data_line['IdEstacion'] = (string) $a_dt['IdEstacion'];
		
			$id_app = $a_dt['Sel'];

			$val['IdEstacion_fk'] = $id_app;

			$checked = 0;
		
			if (in_array($val, $a_perfiles)) {
				$checked = 1;
			}

			if ($s_is_show_all == 1 or $checked == 1) {
				$a_data_line['Sel'] = '';
				if ($checked == 1) {
					$a_data_line['Sel'] = (string) "<input type='checkbox' data-id='".$id_app."' class='idestacion' name='group_checkbox' value='". $id_app ."' checked>";
				} else {
					$a_data_line['Sel'] = (string) "<input type='checkbox' data-id='".$id_app."' class='idestacion' name='group_checkbox' value='". $id_app ."'>";
				}
				$a_data_line['EstacionServicio'] = (string) utf8_encode($a_dt['EstacionServicio']);
				$a_data_line['NoEstacion'] = (string) utf8_encode($a_dt['NoEstacion']);
				$a_data[] = $a_data_line;
			}
		}
			$json =  json_encode(array('data' => $a_data), true);
			echo $json;		
	} 

} else if ($op == 'savePerfil') {
	if (!strlen($id_usuario)) {
		$msg = 'Aun no a guardado el usuario';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		
		if ($check_app == 1) {
			$qry = "INSERT INTO seg_estacionesusuario ( IdUsuario_fk, IdEstacion_fk )VALUES($id_usuario, $id_estacion)";
			$res_db = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_db)) {
				$msg = 'Error SQL:' . $res_db;
			} else {
				if (!$res_db) {
					$msg = 'error al guardar';
					$result = -1;
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					// exit();
				} else {
					$result = 1;
					$msg = 'Dato guardado';
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
				}
			}
		} else {
			$qry = "DELETE FROM seg_estacionesusuario WHERE IdUsuario_fk = $id_usuario AND IdEstacion_fk = $id_estacion";
			$res_db = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_db)) {
				$msg = 'Error SQL:' . $res_db;
			} else {
				if (!$res_db) {
					$msg = 'No borrado';
					$result = -1;
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					// exit();
				} else {
					$result = 1;
					$msg = 'Dato borrado';
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					// exit();
				}
			}
		} 		
	}
}

// $a_ret = array('result' => $result, 'msg' => $msg);
// echo json_encode($a_ret);
?>