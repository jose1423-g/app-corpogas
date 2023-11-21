<?php

chdir(dirname(__FILE__));

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/admin/archivos-send-events.php");


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// set_time_limit(0);

session_start();
// revisar cada estacion a ver si es hora de mandar archivos
// revisa si hay archivos pendientes de enviar (estatus 0, null o 1), ordenados por estacion
// recorre los archivos a enviar separados por estacion
// busca los datos de comunicacion con la estacion (ftp, usuario, pass, etc)
// envia los archivos
// si se envian correctamente, se marcan como 2 en la BD, con la fecha y la hora de envio
// Si hay un error con cada archivo, se marca como 1 en la BD, no se guarda le fecha y hora de envio

$filename_log = "archivos-send.log";
$fp = fopen($filename_log, 'a');
$ts = date('d/m/Y H:i:s');
fwrite($fp, "Fecha: $ts \n Ejecucion de envio de archivos \n");

$v = "v"; // para imprimir texto, solo cuando es linea de comandos

$id_file_par = (isset($argv[1])) ? $argv[1] : "";
$v = (isset($argv[2])) ? $argv[2] : "";

// ejemplo de llamada
// /opt/remi/php56/root/bin/php archivos-send.php            // esto es para enviar todos los archivos pendientes
// /opt/remi/php56/root/bin/php archivos-send.php "" "v"     // esto es para todos los archivos pendientes pero imprimiendo cada paso
// /opt/remi/php56/root/bin/php archivos-send.php "128"      // esto es para enviar solo el archivo IdFile = 128
// /opt/remi/php56/root/bin/php archivos-send.php "128" "v"  // esto es para enviar solo el archivo IdFile = 128 pero imprimiendo cada paso

$a_result = sendAllPendFiles($id_file_par, $v);
$a_files_all_responses = (isset($a_result['a_files_all_responses'])) ? $a_result['a_files_all_responses'] : array();
//   /opt/remi/php56/root/bin/php archivos-send.php

fwrite($fp, " Respuesta: " . print_r($a_result, true) . "\n");
fclose($fp);
exit();

?>