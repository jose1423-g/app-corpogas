<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/file_uploader.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
	
session_start();

// agrega o guarda datos del usuario
$id_user = SessGetUserId();	

// recupera datos
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

$nombre_refaccion = (isset($_REQUEST['NombreRefaccion'])) ? $_REQUEST['NombreRefaccion'] : "";
$referencia = (isset($_REQUEST['Referencia'])) ? $_REQUEST['Referencia'] : "";
$no_serie = (isset($_REQUEST['NoSerie'])) ? $_REQUEST['NoSerie'] : "";
$id_categoria = (isset($_REQUEST['IdCategoria_fk'])) ? $_REQUEST['IdCategoria_fk'] : "";
$img = (isset($_FILES['uploadedfile'])) ? $_FILES['uploadedfile'] : "";
$es_activo = (isset($_REQUEST['EsActivo'])) ? $_REQUEST['EsActivo'] : "";


$msg = "";
$result = 0;

// $fecha_hoy = "";
// $fecha_hoy =  DtDbToday($fecha_hoy);


if ($op == 'save') {
	if (!strlen($nombre_refaccion)) {
		$msg = "El campo nombre refaccion es necesario";
		$result = -1;
	} elseif (!strlen($referencia)) {
		$msg = "El campo referencia es necesario";
		$result = -1;
	} elseif (!strlen($no_serie)) {
		$msg = "El campo No serie es necesario";
		$result = -1;
	} elseif (!strlen($id_categoria)) {
		$msg = "El campo categoria es necesario";
		$result = -1;
	} elseif (empty($img)) {
		$msg = "El campo imagen es necesario";
		$result = -1;
	} elseif (!strlen($id_user)){
		$msg = "Su session ha expirado";
		$result = -1;
	} else {
		
		$nombre_refaccion = utf8_encode($nombre_refaccion);
		$referencia = utf8_encode($referencia);

			//tiene algo				
		if(!empty($_FILES)){
			$new_name = "prducto".$id_user;
			$a_result = UploadFileDoc($_FILES, $new_name, "../images/products/");
			$msg = $a_result['msg'];
			$result = $a_result['result'];
			$new_file_name = $a_result['new_file_name'];

			if ($result != 1) {
				$new_file_name = '';
			}
		} 

		$qry = "INSERT INTO productos (NombreRefaccion, Referencia, NoSerie, IdCategoria_fk, img) 
				VALUES ('$nombre_refaccion', '$referencia', '$no_serie', $id_categoria, '$new_file_name')";
			$result = DbExecute($qry, true);
			if ($result) {
				if (is_string($result)) {
					$msg = "Hubo un error al agregar el producto";
					$result = -1;
				} else {
					$msg = "El productos se agregó correctamente";
					$result = 1;
				}
			} else {
				$msg = "Hubo un error al agregar el producto";
				$result = -1;
			}
	}
}

// en proceso op=del
// if ($op == 'del') {
// 	// No se eliminan usuarios
// 	$output = "No se puede eliminar el Usuario. Debe desactivarlo para evitar su uso";
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
// 	$user_name = utf8_decode($user_name);
// 	$apellido_paterno = utf8_decode($apellido_paterno);
// 	$apellido_materno = utf8_decode($apellido_materno);
// 	$nombre = utf8_decode($nombre);
// 	$passwd = utf8_decode($passwd);
	
// 	// valida que UserName no exista en otro usuario
// 	$slc_id_usuario = "";
// 	if (strlen($id_usuario)) {
// 		$slc_id_usuario = " AND IdUsuario <> $id_usuario";
// 	}
// 	$qry = "SELECT IdUsuario FROM seg_usuarios WHERE UserName = '$user_name' $slc_id_usuario";
// 	// echo $qry;

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