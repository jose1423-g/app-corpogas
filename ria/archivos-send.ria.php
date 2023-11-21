<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/admin/archivos-send-events.php");

session_start();

// guarda los datos de configuracion FTPConnType
$empresa_id = 1; // fijo 1

// recupera datos
// header
$id_user = SessGetUserId();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_file = (isset($_REQUEST['IdFile'])) ? $_REQUEST['IdFile'] : '';

$msg = '';
$result = 0;

if ($op == 'send') {
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		// log, igual que en archivos-send.php
		$filename_log = "archivos-send.log";
		$fp = fopen($filename_log, 'a');
		$ts = date('d/m/Y H:i:s');
		fwrite($fp, "Fecha: $ts \n Ejecucion de envio de archivos \n");
		
		$a_result = sendAllPendFiles();
		$a_files_all_responses = (isset($a_result['a_files_all_responses'])) ? $a_result['a_files_all_responses'] : array();
		$result = $a_result['result'];
		$msg = $a_result['msg'];

		fwrite($fp, " Respuesta: " . print_r($a_result, true) . "\n");
		fclose($fp);
		
		$html_files_all_responses = "";
		if (!empty($a_files_all_responses)) {
			$html_files_all_responses = "<ul class=\"list-group\">";
			foreach($a_files_all_responses as $msg_file) {
				$html_files_all_responses .= "<li class=\"list-group-item\">$msg_file</li>";
			}
			$html_files_all_responses .= "</ul>";
		}
		// $msg = "Proceso terminado";
		// $result = 1;
	}
	$a_ret = array('result' => $result, 'msg' => $msg, 'htmlFilesAllResponses' => $html_files_all_responses);
	echo json_encode($a_ret);
	exit();

} elseif ($op == 'sendFile'){
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} elseif (!strlen($id_file)) {
		$msg = 'El archivo no fue seleccionado correctamente';
		$result = -1;
	} else {
		$a_result = sendAllPendFiles($id_file);
		$a_files_all_responses = (isset($a_result['a_files_all_responses'])) ? $a_result['a_files_all_responses'] : array();
		$result = $a_result['result'];
		$msg = $a_result['msg'];
		$html_files_all_responses = "";
		if (!empty($a_files_all_responses)) {
			$html_files_all_responses = "<ul class=\"list-group\">";
			foreach($a_files_all_responses as $msg_file) {
				$html_files_all_responses .= "<li class=\"list-group-item\">$msg_file</li>";
			}
			$html_files_all_responses .= "</ul>";
		}
		// $msg = "Proceso terminado";
		// $result = 1;
	}
	$a_ret = array('result' => $result, 'msg' => $msg, 'htmlFilesAllResponses' => $html_files_all_responses);
	echo json_encode($a_ret);
	exit();
}
?>