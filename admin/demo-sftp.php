<?php

// ENVIA AUTOMATICAMENTE ARCHIVOS
// Opcion que sirve para enviar automaticamente los archivos tipo CENSO (S) y ESTADO DE CUENTA PARCIAL (C) que esten en el directorio files2send. Los archivos tipo VALIDACION (V) no se envian por esta opcion.
// Se ejecuta por linea de comandos como un proceso automatico por horario (todos los dias a las 8 am por ejemplo)
// 8:30am se envía el archivo de Censo, y a las 9:00am se envía el corte parcial
// Esto implica que debe haber 2 llamadas a este script, una a las 8:30 para enviarlos archivos tipo CENSO (S) y otra a las 9:00 para enviar los archivos tipo ESTADO DE CUENTA PARCIAL(C).
// Este script tambien recibo como parametro el tipo de archivo a enviar

// Al finalizar el envio, si no hay error, se mueve cada archivo enviado a la carpeta "sent"

// Esta opcion no genera ningun archivo, solo los envia

// ejemplo de ejecucion
// php "ruta/axa_atc_envia_edo_cta_exec.php" "{tipo_validacion]"
// php "ruta/axa_atc_envia_edo_cta_exec.php" "C"

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("sys_root.inc.php");
require "$SYS_ROOT/vendor/autoload.php";
use phpseclib3\Net\SFTP;

chdir(__DIR__); // se cambia al directorio actual

$eol = "\n";



// $hoy = DtDbToday();

// temp
// Datos de conexion (test ofm)
$host = 'barbetinfosistemas.dnsalias.com';
$port = '22';
$user = 'root';
$pass = 'mfjCaj2bRL59';

// // reales
// // $host = 'sftp://ftp.cloudatc.com.mx';
// $host = 'ftp.cloudatc.com.mx';
// $port = '2222';
// $user = 'usercqs';
// $pass = 'KeR$hE42*92';

$result = "";
$msg_alert = "";
$local_dir = "../upload/files/";

echo "Creando conexion a $host $port" . "...";
$sftp = new SFTP($host, $port);
echo " Ok" . PHP_EOL;

if (!$sftp->login($user, $pass)) {
	// exit('Login Failed');
	$msg_alert = "No se pudo conectar al servidor remoto";
	echo $msg_alert . $eol;
} else {
	$full_filename = $local_dir . "PL_20763/PL_20763_EXP_ES_201720230627.235959CDU100309BK7.xml";
	$remote_file = basename($full_filename);
	// $full_full_filename = str_replace("../files2send", "/files2send", $full_filename);
	// $full_full_filename = $SYS_ROOT . $full_full_filename; 
	// echo "Check $full_filename -- $full_full_filename -- $remote_file" . $eol; exit();
	// $res = @$sftp->put($remote_file, $full_full_filename, NET_SFTP_LOCAL_FILE);
	echo "Cambiando al directorio /var/www/html/ordenes...";
	$res_temp = $sftp->chdir("/var/www/html/ordenes");
	echo " Ok" . PHP_EOL;
	echo "Directorio actual: " . $sftp->pwd() . PHP_EOL;
	var_dump($res_temp);
	$res = @$sftp->put($remote_file, $full_filename, SFTP::SOURCE_LOCAL_FILE);
	if ($res !== false) {
		echo "Se subio el archivo $full_filename" . $eol;
	} else {
		$msg_alert = "Error al subir el archivo: ";
		$last = $sftp->getLastSFTPError();
		list($statusCode,) = explode(':', $last, 2);
		// var_dump($statusCode);
		 
		// pick what needs to be handled specifically or generically
		switch ($statusCode) {
			case 'NET_SFTP_STATUS_NO_SUCH_FILE':
				$msg_alert .= "Archivo no encontrado";
				break;
			case 'NET_SFTP_STATUS_PERMISSION_DENIED':
				$msg_alert .= "No tiene permiso para subir archivo";
				break;
			// this is the catch all failure response
			case 'NET_SFTP_STATUS_FAILURE':
			default:
				$msg_alert .= $statusCode;
				break;
		}
		echo $msg_alert . $eol;
	}
}

?>