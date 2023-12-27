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

$estaciones = implode(",", $id_estacion);


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

		$pass = utf8_encode($a_usuarios['passwd']);
		$a_usuarios['passwd'] = $pass;

		$email = utf8_encode($a_usuarios['Email']);
		$a_usuarios['Email'] = $email;


		$id_estacion = $a_usuarios['IdEstacion_fk'];
		$estaciones = explode(',', $id_estacion);
		
		$a_usuarios['IdEstacion_fk'] = $estaciones;



		$a_usuarios['result'] = 1;
		$a_usuarios['msg'] = $msg;
		$a_ret = $a_usuarios;

		echo json_encode($a_ret);
		exit();
	}

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
	} else if (!strlen($telefefono)){
		$msg = 'El campo telefono es requerido';
		$result = -1;
	// } else if ($id_estacion_long == 1){
	// 	$msg = 'El campo estacion de servicio es requerido';
	// 	$result = -1;
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
					$qry = "UPDATE seg_estacionesusuario 
							SET IdEstacion_fk = '$estaciones' 
							WHERE IdUsuario_fk = $id_usuario"; 
					$res_ins = DbExecute($qry, true);
					DbCommit();
					if (is_string($res_ins)) {
						$msg = 'Error al actualizar el usuario.: ' . $res_ins;
						$result = -1;
					} else {
						if (!$res_ins) {
							$msg = 'Error al actualizar el usuario.';
							$result = -1;
						} else {
							$msg = 'Usuario actulizado correctamente';
							$result = 1;
						}
					}
				}
			}

		} else {
			$qry = "SELECT UserName FROM seg_usuarios WHERE UserName = '$user_name'";
			$user = DbGetFirstFieldValue($qry);
			// echo $data;
			if (isset($user)) {
				$msg = 'El usuario ya existe intente con otro';
				$result = -1;
				
			} else {

				$passwd = crypt($passwd, "doxasystems");
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
						$qry = "SELECT MAX(IdUsuario) AS id_usuario FROM seg_usuarios";
						$id_usuario = DbGetFirstFieldValue($qry);					
						$qry = "INSERT INTO seg_estacionesusuario (IdUsuario_fk, IdEstacion_fk) VALUES ($id_usuario, '$estaciones')";
						$res_ins = DbExecute($qry, true);
						DbCommit();
						if (is_string($res_ins)) {
							$msg = 'Error al crear el usuario.: ' . $res_ins;
							$result = -1;
						} else {
							if (!$res_ins) {
								$msg = 'Error al crear el usuario.';
								$result = -1;
							} else {
								$msg = 'Usuario creado correctamente';
								$result = 1;
							}
						}
					}
				}
			}
		}

	}
}
// } else {
// 	// valida datos
// 	$es_error = 0;
// 	if (!strlen($user_name)) {
// 		$es_error = 1;
// 		$output = "El campo Usuario es necesario";
// 	} elseif (!strlen($nombre)) {
// 		$es_error = 1;
// 		$output = "El campo Nombre es necesario";
// 	} elseif (!strlen($apellido_paterno)) {
// 		$es_error = 1;
// 		$output = "El campo Apellido Paterno es necesario";
// 	}
	
// 	// transforma para guardar o insertar
	
// 	// valida que UserName no exista en otro usuario
	

// 	$user_name_existe = DbGetFirstFieldValue($qry);
// 	if (strlen($user_name_existe)) {
// 		$es_error = 1;
// 		$output = "El nombre de Usuario '$user_name' ya existe";
// 	}

// 	if ($es_error == 0) {
// 		// echo "dentro del if";
// 		// exit();
// 		if (strlen($id_usuario)) {
// 			// edicion
//             if ($is_passwd_mod == 1) {
//                 $passwd = crypt($passwd, "doxasystems"); // mover a libreria
//             }
// 			$qry = "UPDATE seg_usuarios
// 					SET UserName = '$user_name',
// 						ApellidoPaterno = '$apellido_paterno',
// 						ApellidoMaterno = '$apellido_materno',
// 						Nombre = '$nombre',
// 						UsuarioPerfilId_fk = $usuario_perfil_id,
// 						passwd = '$passwd',
// 						EsActivo = $es_activo
// 					WHERE IdUsuario = $id_usuario";
// 			$result = DbExecute($qry, true);
// 			if ($result) {
// 				if (is_string($result)) {
// 					$output = "Hubo un error al guardar los cambios";
// 				} else {
// 					$output = "Se guardaron los cambios";
// 					$result = 1;
// 				}
// 			} else {
// 				$output = "Hubo un error al guardar los cambios";
// 				$result = -1;
// 			}
// 		} else {
// 			// nuevo
// 			$passwd = crypt($passwd, "doxasystems"); // mover a libreria
// 			if (!strlen($usuario_perfil_id)) {
// 				$usuario_perfil_id = 'NULL';
// 			}
// 			$qry = "INSERT INTO seg_usuarios (UserName, ApellidoPaterno, ApellidoMaterno, Nombre, UsuarioPerfilId_fk, passwd, EsActivo)
// 					VALUES('$user_name', '$apellido_paterno', '$apellido_materno', '$nombre', $usuario_perfil_id, '$passwd', $es_activo)";
// 			echo $qry;
// 			exit();
// 			$result = DbExecute($qry, true);
// 			if ($result) {
// 				if (is_string($result)) {
// 					$output = "Hubo un error al agregar el usuario";
// 				} else {
// 					$output = "El usuario se agregó correctamente";
// 					$result = 1;
// 				}
// 			} else {
// 				$output = "Hubo un error al agregar el usuario";
// 				$result = -1;
// 			}
// 		}
// 	}
// }
$a_ret = array('result' => $result, 'msg' => $msg);
echo json_encode($a_ret);
?>