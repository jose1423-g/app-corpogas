<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require "$SYS_ROOT/vendor/autoload.php";
use phpseclib3\Net\SFTP;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $sftp_last_error;
function sftpConnect($a_data_connect, $v = "") {
	
	$ftp_server = $a_data_connect['FTPIp'];
	$ftp_user = $a_data_connect['FTPUser'];
	$ftp_pass = $a_data_connect['FTPPass'];
	$ftp_port = $a_data_connect['FTPPort'];
				// // demo
			// $ftp_server = 'barbetinfosistemas.dnsalias.com';
			// $ftp_user = 'root';
			// $ftp_pass = 'mfjCaj2bRL59';
			// $ftp_port = '22';
	// $ftp_timeout = 10;
	// if ($v == 'v') { echo "Conectando... "; }
	$sftp = new SFTP($ftp_server, $ftp_port);
	// if (!$conn_id = ftp_connect($ftp_server, $ftp_port, $ftp_timeout)) {
		// $sftp_last_error = "No se pudo realizar la conexion";
		// if ($v == 'v') { echo "No se pudo realizar la conexion" . PHP_EOL; }
		// if ($v == 'v') { echo print_r(error_get_last(), true) . PHP_EOL; }
		// return false;
	// } else {
		// if ($v == 'v') { echo "Conectado" . PHP_EOL; }
	// }

	if ($v == 'v') { echo "Entrando con usuario y contraseña..."; }
	if ($sftp->login($ftp_user, $ftp_pass)) {
		if ($v == 'v') { echo "Loggeado" . PHP_EOL; }
	} else {
		$sftp_last_error = "No se pudo conectar con las credenciales";
		if ($v == 'v') { echo "No se pudo conectar con las credenciales" . PHP_EOL; }
		return false;
	}
	
	// // activar modo pasivo
	// if ($v == 'v') { echo "Activando modo pasivo..."; }
	// ftp_pasv($conn_id, true);
	// if ($v == 'v') { echo "Ok" . PHP_EOL; }
	
	return $sftp;
}

function sftpMultiPutFile($a_data_connect, $a_files = array(), $v = "") {
	global $sftp_last_error;
	
	set_time_limit(0);
	
	// conexion una sola vez para el server
	$sftp = sftpConnect($a_data_connect, $v);
	$remote_dir = $a_data_connect['FTPFolder'];
	
	$result = 1;
	$msg = '';
	$a_files_response = array();
	
	if ($sftp) {
		if ($v == 'v') { echo "Configurando directorio..."; }
		// cambia de directorio
		$dir_name_ori = $sftp->pwd();
		$remote_dir = trim($remote_dir);
		if ($remote_dir != '.' and $remote_dir != "/" and $remote_dir != "") {
			if (!$sftp->chdir($remote_dir)) {
				// si no existe, trata de crearlo
				if ($sftp->mkdir($remote_dir)) {
					$sftp->chdir($remote_dir);
				} else {
					$result = 0;
					$msg = 'El directorio no existe' . $remote_dir;
					$sftp->disconnect();
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
				if (@$sftp->put($server_file, $local_file, SFTP::SOURCE_LOCAL_FILE)) {
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
		
		$sftp->chdir($dir_name_ori);
		// cerrar la conexión sftp
		$sftp->disconnect();
		
	} else {
		$result = -1;
		$msg = (strlen($sftp_last_error)) ? $sftp_last_error : "No se pudo conectar con el servidor sftp";
		if ($v == 'v') { echo $msg . PHP_EOL; }
	}

	return array('result' => $result, 'msg' => $msg, 'a_files_response' => $a_files_response);
}


// function sftpMultiGetFile($remote_dir = ".", $a_files = array()) {
	// global $a_data_connect;
	
	// $ftp_local_dir = $a_data_connect['ftp_local_dir'];
	
	// set_time_limit(0);
	// $msg_id = 605;
	
	// // conexion una sola vez para el server
	// $conn_id = sftpConnect();
	
	// // si no tiene achivos, descarga todo el directorio
	// if (empty($a_files)) {
		// $contents = ftp_nlist($conn_id, $remote_dir);
		
		// foreach ($contents as $file) {
		   // $local_file = $ftp_local_dir . $file;
		   // $server_file = $file;
		   // echo "Descargando achivo $file..." . PHP_EOL;
		   // ftp_get($conn_id, $local_file, $server_file, FTP_BINARY);
		// }
	// } else {
		// // descarga solo los archivos en a_files
		// foreach ($a_files as $file) {
		   // $local_file = $ftp_local_dir . $file;
		   // $server_file = $file;
		   // echo "Descargando achivo $file..." . PHP_EOL;
		   // ftp_get($conn_id, $local_file, $server_file, FTP_BINARY);
		// }
	// }
	
	// // cerrar la conexión ftp
	// ftp_close($conn_id);

	// return array('msg_id' => $msg_id);
// }

?>