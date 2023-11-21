<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");

// config
$upload_dir = "files/";
$log_filename = "upload.log";
$log_filename_files = "files.log";

// trata de crear el directorio principal si no existe
if (!is_dir($upload_dir)) {
	if (!mkdir($upload_dir)) {
		$error = "No se pudo crear el directorio $upload_dir";
		http_response_code(200);
		exit($error);
	}
}

// obtiene direccion IP para el log
$ip = "";
if ( isset($_SERVER["REMOTE_ADDR"])) {
	$ip = $_SERVER["REMOTE_ADDR"];
} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
	$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else if ( isset($_SERVER["HTTP_CLIENT_IP"])) {
	$ip = $_SERVER["HTTP_CLIENT_IP"];
}

// Contar cuantos archivos vienen
$total = 0;
if (isset($_FILES)) {
	$total = count($_FILES);
}

$a_result = array();
$a_result['total'] = $total;
$a_archivos = array();
$is_error = 0;
$error_msg = '';

// recorre archivos que vienen para guardarlos
for( $i = 0 ; $i < $total ; $i++ ) {
	$a_result[$i]['id'] = $i;
	$tmp_name = (isset($_FILES['file'.$i]['tmp_name'])) ? $_FILES['file'.$i]['tmp_name'] : '';
	$a_result[$i]['tmp_name'] = $tmp_name;
	// Directorio temporal
	$tmpFilePath = (isset($_FILES['file'.$i]['tmp_name'])) ? $_FILES['file'.$i]['tmp_name'] : '';

	// Verifica el directorio
	if ($tmpFilePath != ""){
		$a_result[$i]['tmp_name'] = $tmpFilePath;
		$filename = $_FILES['file'.$i]['name'];
		
		// directorio especifico para la estacion
		$a_file_name = explode("_", $filename);
		$estacion = $a_file_name[0] . "_" . $a_file_name[1];

		// crea el directorio si no existe
		if (!is_dir($upload_dir . $estacion)) {
			if (!mkdir($upload_dir . $estacion)) {
				$error_msg = "No se pudo crear el directorio $estacion";
				$a_result[$i]['msgs'][] = $error_msg;
				$is_error = 1;
			}
		}
		
		if ($is_error == 0) {
			$newFilePath = $upload_dir . $estacion . "/" . $filename;
			$a_result[$i]['name'] = $filename;
		
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$full_filename = $upload_dir_rel . $filename;
				$a_archivos[$i] = $full_filename;
				$a_result[$i]['msgs'][] = utf8_decode("Archivo subido $filename");
				
				// obtiene estacion, la crea si no existe
				$qry = "SELECT IdEstacion FROM estaciones WHERE PL = '$estacion'";
				$id_estacion_existe = DbGetFirstFieldValue($qry);
				if (!strlen($id_estacion_existe)) {
					$qry = "INSERT INTO estaciones(PL, EsActivo)
							VALUES('$estacion', 1)";
					$res_est = DbExecute($qry, true);
					$id_estacion = LastIdAuto();
					if (is_string($res_est)) {
						$a_result[$i]['msgs'][] = utf8_decode("No se pudo crear la estacion $estacion: $res_est");
					} elseif (!$res_est) {
						$a_result[$i]['msgs'][] = utf8_decode("No se pudo crear la estacion $estacion");
					}
				} else {
					$id_estacion = $id_estacion_existe;
				}
				// guarda log de archivo subido en base de datos
				$qry = "SELECT IdFile FROM archivos WHERE IdEstacion = $id_estacion AND FileName = '$filename'";
				$id_file_existe = DbGetFirstFieldValue($qry);
				// si no existe lo agrega, si existe, no hace nada, pero se sobreescribe el archivo en la carpeta
				if (!strlen($id_file_existe)) {
					$mensaje = 'Archivo recibido correctamente';
					$fecha = DtDbToday();
					$hora = TmDbStamp();
					$qry = "INSERT INTO archivos(IdEstacion, IPOrigen, Mensaje, FileName, Fecha, Hora)
							VALUES($id_estacion, '$ip', '$mensaje', '$filename', $fecha, '$hora')";
					$res_file = DbExecute($qry, true);
					if (is_string($res_file)) {
						$a_result[$i]['msgs'][] = utf8_decode("No se pudo crear el registro del archivo en la BD!!! $filename. $res_file");
					} elseif (!$res_file) {
						$a_result[$i]['msgs'][] = utf8_decode("No se pudo crear el registro del archivo en la BD!!! $filename.");
					}
				} else {
					$a_result[$i]['msgs'][] = utf8_decode("El archivo '$filename' ya existe en la BD, no se creara uno nuevo");
				}
			} else {
				$is_error = 1;
				$error_msg = "Error general al subir el archivo $filename: error # " . $_FILES['file'.$i]['error'];
				$a_result[$i]['msgs'][] = $error_msg;
			}
		}
	} else {
		$is_error = 1;
		$error_msg = $_FILES['file'.$i]['name'] . " no se pudo cargar, estaba vacio el path";
		$a_result[$i]['msgs'][] = $error_msg;
	}
}
$fp = fopen($log_filename, 'a');
$fecha = DtDbLgToday();
fwrite($fp, "date: $fecha, dirip: $ip, files: " . print_r($a_result, true). "\n");
fclose($fp);

$fp_f = fopen($log_filename_files, 'a');
fwrite($fp_f, "date: $fecha, dirip: $ip, all_files: " . print_r($_FILES, true). "\n");
fclose($fp_f);

if ($is_error == 1) {
	$a_ret = array('result' => 0, 'msg' => $error_msg);
	echo json_encode($a_ret, true);
	http_response_code(418);
} else {
	$a_ret = array('result' => 1, 'msg' => 'Test: ' . $total . ' files received');
	echo json_encode($a_ret, true);
	http_response_code(200);
}
exit();
?>
