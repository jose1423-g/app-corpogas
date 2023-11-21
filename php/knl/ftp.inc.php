<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $ftp_last_error;
function ftpConnect($a_data_connect, $v = "") {
	$ftp_server = $a_data_connect['FTPIp'];
	$ftp_user = $a_data_connect['FTPUser'];
	$ftp_pass = $a_data_connect['FTPPass'];
	$ftp_port = $a_data_connect['FTPPort'];
	$ftp_timeout = 10;
	if ($v == 'v') { echo "Conectando... "; }
	if (!$conn_id = ftp_connect($ftp_server, $ftp_port, $ftp_timeout)) {
		$ftp_last_error = "No se pudo realizar la conexion";
		if ($v == 'v') { echo "No se pudo realizar la conexion" . PHP_EOL; }
		if ($v == 'v') { echo print_r(error_get_last(), true) . PHP_EOL; }
		return false;
	} else {
		if ($v == 'v') { echo "Conectado" . PHP_EOL; }
	}

	if ($v == 'v') { echo "Entrando con usuario y contraseña..."; }
	if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		if ($v == 'v') { echo "Loggeado" . PHP_EOL; }
	} else {
		$ftp_last_error = "No se pudo conectar con las credenciales";
		if ($v == 'v') { echo "No se pudo conectar con las credenciales" . PHP_EOL; }
		return false;
	}
	
	// activar modo pasivo
	if ($v == 'v') { echo "Activando modo pasivo..."; }
	ftp_pasv($conn_id, true);
	if ($v == 'v') { echo "Ok" . PHP_EOL; }
	
	return $conn_id;
}

function ftpMultiPutFile($a_data_connect, $a_files = array(), $v = "") {
	global $ftp_last_error;
	
	set_time_limit(0);
	
	// conexion una sola vez para el server
	$conn_id = ftpConnect($a_data_connect, $v);
	$remote_dir = $a_data_connect['FTPFolder'];
	
	$result = 1;
	$msg = '';
	$a_files_response = array();
	
	if ($conn_id) {
		if ($v == 'v') { echo "Configurando directorio..."; }
		// cambia de directorio
		$dir_name_ori = ftp_pwd($conn_id);
		$remote_dir = trim($remote_dir);
		if ($remote_dir != '.' and $remote_dir != "/" and $remote_dir != "") {
			if (!@ftp_chdir($conn_id, $remote_dir)) {
				// si no existe, trata de crearlo
				if (ftp_mkdir($conn_id, $remote_dir)) {
					ftp_chdir($conn_id, $remote_dir);
				} else {
					$result = 0;
					$msg = 'El directorio no existe' . $remote_dir;
					ftp_close($conn_id);
					return array('result' => $result, 'msg' => $msg, 'a_files_response' => $a_files_response);
				}
			}
		}
		if ($v == 'v') { echo "Ok $msg" . PHP_EOL; }
		// echo ftp_pwd($conn_id) . PHP_EOL;
		
		// carga la lista de archivos
		if (!empty($a_files)) {
			foreach ($a_files as $id_file => $a_file) {
				$server_file = $a_file['FileName'];
				$local_file = $a_file['Path'] . $a_file['FileName'];
				if (ftp_put($conn_id, $server_file, $local_file, FTP_BINARY)) {
					// guarda en respuesta el id del archivo con la indicacion que si se subio
					$a_files_response[$id_file] = array(1, "Archivo enviado", $local_file);
					if ($v == 'v') { echo "Archivo $local_file enviado" . PHP_EOL; }
				} else {
					// guarda en respuesta el id del archivo con la indicacion que no se subio
					$a_files_response[$id_file] = array(0, "No se pudo enviar el archivo", $server_file);
					if ($v == 'v') { echo "No se pudo enviar el archivo $server_file" . PHP_EOL; }
			   }
			}
		} else {
			$result = 1;
			$msg = 'No hay ningun archivo para enviar';
			if ($v == 'v') { echo $msg . PHP_EOL; }
		}
		
		ftp_chdir($conn_id, $dir_name_ori);
		// cerrar la conexión ftp
		ftp_close($conn_id);
		
	} else {
		$result = -1;
		$msg = (strlen($ftp_last_error)) ? $ftp_last_error : "No se pudo conectar con el servidor ftp";
		if ($v == 'v') { echo $msg . PHP_EOL; }
	}

	return array('result' => $result, 'msg' => $msg, 'a_files_response' => $a_files_response);
}


function ftpMultiGetFile($remote_dir = ".", $a_files = array()) {
	global $a_data_connect;
	
	$ftp_local_dir = $a_data_connect['ftp_local_dir'];
	
	set_time_limit(0);
	$msg_id = 605;
	
	// conexion una sola vez para el server
	$conn_id = ftpConnect();
	
	// si no tiene achivos, descarga todo el directorio
	if (empty($a_files)) {
		$contents = ftp_nlist($conn_id, $remote_dir);
		
		foreach ($contents as $file) {
		   $local_file = $ftp_local_dir . $file;
		   $server_file = $file;
		   echo "Descargando achivo $file..." . PHP_EOL;
		   ftp_get($conn_id, $local_file, $server_file, FTP_BINARY);
		}
	} else {
		// descarga solo los archivos en a_files
		foreach ($a_files as $file) {
		   $local_file = $ftp_local_dir . $file;
		   $server_file = $file;
		   echo "Descargando achivo $file..." . PHP_EOL;
		   ftp_get($conn_id, $local_file, $server_file, FTP_BINARY);
		}
	}
	
	// cerrar la conexión ftp
	ftp_close($conn_id);

	return array('msg_id' => $msg_id);
}

// revisa si el directorio existe, si no existe lo crea
function ftpCheckDir($conn_id, $dir_name) {
	$result = 0;
	// intenta cambiar al directorio para ver si ya existe
	$dir_name_ori = ftp_pwd($conn_id);
	if (!ftp_chdir($conn_id, $dir_name)) {
		// intentar crear el directorio $dir_name
		if (ftp_mkdir($conn_id, $dir_name)) {
			$result = 1;
		}
	} else {
		ftp_chdir($conn_id, $dir_name_ori);
		$result = 1;
	}
	return $result;
}